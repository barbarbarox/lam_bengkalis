<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PdfImportService
{
    /**
     * Parse file PDF dan kembalikan array berisi ['judul' => string, 'konten' => string]
     *
     * @param string $path Path relatif di disk public, contoh: temp/word-import/file.pdf
     * @return array<string, string>
     */
    public function importFromPath(string $path): array
    {
        $fullPath = Storage::disk('public')->path($path);

        if (!file_exists($fullPath)) {
            throw new \Exception("File PDF tidak ditemukan: {$fullPath}");
        }

        $text = $this->extractText($fullPath);
        return $this->processText($text);
    }

    /**
     * Ekstrak teks menggunakan pdftotext (jika ada) karena hasilnya jauh lebih rapi,
     * fallback ke Smalot\PdfParser.
     */
    protected function extractText(string $fullPath): string
    {
        // Coba gunakan pdftotext (Poppler) yang memberikan hasil jauh lebih rapi (layout, kolom, spasi)
        $output = [];
        $returnVar = -1;
        // Gunakan exec untuk mengecek apakah pdftotext tersedia
        exec('command -v pdftotext', $output, $returnVar);
        
        if ($returnVar === 0) {
            // Jalankan pdftotext, output diarahkan ke standard output (-)
            $text = shell_exec('pdftotext ' . escapeshellarg($fullPath) . ' - 2>/dev/null');
            if (!empty(trim((string)$text))) {
                return $text;
            }
        }

        // Fallback: gunakan Smalot\PdfParser
        $parser = new Parser();
        $pdf = $parser->parseFile($fullPath);
        return $pdf->getText();
    }

    /**
     * Olah raw text dari PDF menjadi HTML sederhana (p) dengan perbaikan kerapian
     */
    protected function processText(string $text): array
    {
        // 1. Bersihkan carriage return
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);

        // 2. Normalisasi spasi berlebih antar kata dalam satu baris (kecuali baris baru)
        $text = preg_replace('/[ \t]+/', ' ', $text);

        // 3. Pisahkan berdasarkan double newline (paragraf terpisah)
        // Kadang PDF menghasilkan lebih dari 2 newline untuk pemisah blok
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        
        $blocks = explode("\n\n", trim($text));
        
        $judul = '';
        $html = '';

        foreach ($blocks as $block) {
            $block = trim($block);
            if (empty($block)) continue;

            // 4. Dalam satu blok paragraf, perbaiki baris yang terputus (single newline)
            $lines = explode("\n", $block);
            $paragraphText = '';

            foreach ($lines as $idx => $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Jika baris diakhiri hyphen (-), gabungkan tanpa spasi (kata terputus)
                if (str_ends_with($line, '-')) {
                    $paragraphText .= rtrim($line, '-');
                } else {
                    $paragraphText .= ($paragraphText === '' ? '' : ' ') . $line;
                }
            }

            // Bersihkan kembali multiple space akibat pergantian
            $paragraphText = preg_replace('/ +/', ' ', trim($paragraphText));

            // Lewati jika kosong setelah dibersihkan
            if (empty($paragraphText)) continue;

            // 5. Ambil judul dari baris pertama yang tidak kosong
            if (empty($judul)) {
                $judul = Str::limit($paragraphText, 250, '');
                // Jika ingin teks judul tidak diulang di konten, aktifkan `continue;`
                continue; 
            }

            // Tambahkan sebagai paragraf HTML
            $html .= "<p>" . e($paragraphText) . "</p>\n";
        }

        return [
            'judul'  => $judul,
            'konten' => $html,
        ];
    }
}
