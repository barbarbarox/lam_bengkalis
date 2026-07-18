<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\AbstractElement;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\Line;

/**
 * WordImportService
 *
 * Mengkonversi file .docx ke HTML yang dapat digunakan di TipTap editor.
 * Mendukung: heading, paragraf, bold/italic/underline, tabel, list, gambar.
 */
class WordImportService
{
    /** @var list<string> Gambar yang berhasil diekstrak */
    protected array $extractedImages = [];

    /**
     * Proses file .docx dari Storage dan kembalikan data HTML.
     *
     * @param  string  $storagePath  Path relatif dari disk public (misal: 'temp/word-import/file.docx')
     * @param  string  $disk         Nama disk storage (default: 'public')
     * @return array{judul: string, konten_html: string, extracted_images: list<string>}
     */
    public function importFromStorage(string $storagePath, string $disk = 'public'): array
    {
        $absolutePath = Storage::disk($disk)->path($storagePath);
        return $this->import($absolutePath);
    }

    /**
     * Proses file .docx dari path absolut dan kembalikan data HTML.
     *
     * @param  string  $filePath  Path absolut ke file .docx
     * @return array{judul: string, konten_html: string, extracted_images: list<string>}
     */
    public function import(string $filePath): array
    {
        $this->extractedImages = [];

        if (!file_exists($filePath)) {
            throw new \RuntimeException("File tidak ditemukan: {$filePath}");
        }

        $phpWord = IOFactory::load($filePath);

        $judul     = '';
        $htmlParts = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $html = $this->elementToHtml($element);
                if ($html === '') {
                    continue;
                }
                // Ambil judul dari elemen pertama yang merupakan heading
                if ($judul === '' && str_starts_with($html, '<h')) {
                    $judul = strip_tags($html);
                }
                $htmlParts[] = $html;
            }
        }

        // Gabungkan list items yang berurutan menjadi satu <ul>
        $htmlParts = $this->mergeListItems($htmlParts);

        $kontenHtml = implode("\n", $htmlParts);

        return [
            'judul'            => $judul,
            'konten_html'      => $kontenHtml,
            'extracted_images' => $this->extractedImages,
        ];
    }

    /**
     * Gabungkan multiple <ul><li> yang terpisah menjadi satu <ul>.
     *
     * @param  list<string>  $parts
     * @return list<string>
     */
    protected function mergeListItems(array $parts): array
    {
        $merged  = [];
        $inList  = false;
        $listBuf = '';

        foreach ($parts as $part) {
            $trimmed = trim($part);

            if (str_starts_with($trimmed, '<ul><li>') && str_ends_with($trimmed, '</li></ul>')) {
                // Ekstrak isi <li> saja
                $liContent = preg_replace('#^<ul><li>(.*)</li></ul>$#s', '$1', $trimmed);
                if (!$inList) {
                    $inList  = true;
                    $listBuf = '<ul>';
                }
                $listBuf .= "<li>{$liContent}</li>";
            } else {
                if ($inList) {
                    $merged[] = $listBuf . '</ul>';
                    $listBuf  = '';
                    $inList   = false;
                }
                $merged[] = $part;
            }
        }

        if ($inList) {
            $merged[] = $listBuf . '</ul>';
        }

        return $merged;
    }

    /**
     * Konversi elemen PHPWord ke HTML string.
     */
    protected function elementToHtml(AbstractElement $element): string
    {
        // ── Title / Heading ──────────────────────────────────────────────────
        if ($element instanceof Title) {
            $depth = (int) $element->getDepth();
            $tag   = 'h' . max(1, min($depth, 6));
            $obj   = $element->getTextObject() ?? $element->getText();
            $text  = $this->extractInlineText($obj);
            if (trim(strip_tags($text)) === '') {
                return '';
            }
            return "<{$tag}>{$text}</{$tag}>";
        }

        // ── ListItem ─────────────────────────────────────────────────────────
        if ($element instanceof ListItem) {
            $text = $this->textRunToHtml($element->getTextObject());
            if (trim(strip_tags($text)) === '') {
                return '';
            }
            return "<ul><li>{$text}</li></ul>";
        }

        // ── ListItemRun ──────────────────────────────────────────────────────
        if ($element instanceof ListItemRun) {
            $html = '';
            foreach ($element->getElements() as $el) {
                $html .= $this->inlineElementToHtml($el);
            }
            if (trim(strip_tags($html)) === '') {
                return '';
            }
            return "<ul><li>{$html}</li></ul>";
        }

        // ── Table ────────────────────────────────────────────────────────────
        if ($element instanceof Table) {
            return $this->tableToHtml($element);
        }

        // ── Image (block-level) ──────────────────────────────────────────────
        if ($element instanceof Image) {
            $img = $this->imageToHtml($element);
            return $img !== '' ? "<p>{$img}</p>" : '';
        }

        // ── PageBreak ────────────────────────────────────────────────────────
        if ($element instanceof PageBreak) {
            return '<hr>';
        }

        // ── TextRun ──────────────────────────────────────────────────────────
        if ($element instanceof TextRun) {
            $html = $this->textRunToHtml($element);
            if (trim(strip_tags($html)) === '') {
                return '';
            }
            return "<p>{$html}</p>";
        }

        // ── Text (inline) ────────────────────────────────────────────────────
        if ($element instanceof Text) {
            $html = $this->textToHtml($element);
            if (trim(strip_tags($html)) === '') {
                return '';
            }
            return "<p>{$html}</p>";
        }

        return '';
    }

    /**
     * Konversi inline element (Text atau Image) ke HTML.
     */
    protected function inlineElementToHtml(AbstractElement $element): string
    {
        if ($element instanceof Text) {
            return $this->textToHtml($element);
        }
        if ($element instanceof Image) {
            return $this->imageToHtml($element);
        }
        return '';
    }

    /**
     * Konversi TextRun ke HTML inline (bold, italic, underline).
     */
    protected function textRunToHtml(mixed $textRun): string
    {
        if ($textRun === null) {
            return '';
        }
        if (is_string($textRun)) {
            return htmlspecialchars($textRun, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if ($textRun instanceof Text) {
            return $this->textToHtml($textRun);
        }
        if (!($textRun instanceof TextRun)) {
            return '';
        }

        $parts = [];
        foreach ($textRun->getElements() as $el) {
            $parts[] = $this->inlineElementToHtml($el);
        }
        return implode('', $parts);
    }

    /**
     * Konversi Text element ke HTML dengan format bold/italic/underline/strike.
     */
    protected function textToHtml(Text $text): string
    {
        $raw  = htmlspecialchars((string) $text->getText(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $font = $text->getFontStyle();

        if (!is_object($font)) {
            return $raw;
        }

        // Urutan wrapping penting untuk HTML yang valid
        if (method_exists($font, 'getStrikethrough') && $font->getStrikethrough()) {
            $raw = "<s>{$raw}</s>";
        }
        if (method_exists($font, 'getUnderline') && $font->getUnderline() && $font->getUnderline() !== 'none') {
            $raw = "<u>{$raw}</u>";
        }
        if (method_exists($font, 'getItalic') && $font->getItalic()) {
            $raw = "<em>{$raw}</em>";
        }
        if (method_exists($font, 'getBold') && $font->getBold()) {
            $raw = "<strong>{$raw}</strong>";
        }

        return $raw;
    }

    /**
     * Konversi Table ke HTML.
     */
    protected function tableToHtml(Table $table): string
    {
        $rows = $table->getRows();
        if (empty($rows)) {
            return '';
        }

        $html = '<table><tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row->getCells() as $cell) {
                $cellContent = '';
                foreach ($cell->getElements() as $el) {
                    $inner = $this->elementToHtml($el);
                    // Hapus <p> wrapper di dalam sel tabel
                    $inner = preg_replace('#^<p>(.*)</p>$#s', '$1', trim($inner)) ?? $inner;
                    $cellContent .= $inner;
                }
                $html .= '<td>' . trim($cellContent) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        return $html;
    }

    protected function imageToHtml(Image $image): string
    {
        try {
            // 1. Coba ambil data binary/base64 langsung dari objek Image
            if (method_exists($image, 'getImageStringData')) {
                $base64 = $image->getImageStringData(true);
                if (!empty($base64)) {
                    $ext = method_exists($image, 'getImageExtension') ? $image->getImageExtension() : 'png';
                    $ext = trim(strtolower($ext), '.');
                    if (empty($ext)) $ext = 'png';
                    
                    $filename = 'berita/lampiran/' . Str::uuid() . '.' . $ext;
                    Storage::disk('public')->put($filename, base64_decode($base64));
                    $this->extractedImages[] = $filename;
                    
                    $url = Storage::url($filename);
                    return "<img src=\"{$url}\" alt=\"\" style=\"max-width:100%;height:auto;\">";
                }
            }

            $source = $image->getSource();

            // PHPWord bisa kembalikan path atau data URI
            if (empty($source)) {
                return '';
            }

            // Jika data URI (base64)
            if (str_starts_with($source, 'data:')) {
                return $this->saveBase64Image($source);
            }

            // Jika file path (abaikan cek file_exists untuk wrapper zip)
            if (!file_exists($source) && !str_starts_with($source, 'zip://')) {
                return '';
            }

            $ext      = strtolower(pathinfo($source, PATHINFO_EXTENSION)) ?: 'png';
            $filename = 'berita/lampiran/' . Str::uuid() . '.' . $ext;

            Storage::disk('public')->put($filename, @file_get_contents($source));
            $this->extractedImages[] = $filename;

            $url = Storage::url($filename);
            return "<img src=\"{$url}\" alt=\"\" style=\"max-width:100%;height:auto;\">";

        } catch (\Throwable) {
            return '';
        }
    }

    /**
     * Simpan gambar dari data URI base64 ke storage.
     */
    protected function saveBase64Image(string $dataUri): string
    {
        try {
            if (!preg_match('#^data:(image/[a-z]+);base64,(.+)$#', $dataUri, $m)) {
                return '';
            }

            $mime    = $m[1];
            $data    = base64_decode($m[2]);
            $ext     = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
                default      => 'png',
            };

            $filename = 'berita/lampiran/' . Str::uuid() . '.' . $ext;
            Storage::disk('public')->put($filename, $data);
            $this->extractedImages[] = $filename;

            $url = Storage::url($filename);
            return "<img src=\"{$url}\" alt=\"\" style=\"max-width:100%;height:auto;\">";

        } catch (\Throwable) {
            return '';
        }
    }

    /**
     * Ekstrak teks dari berbagai tipe objek PHPWord.
     */
    protected function extractInlineText(mixed $obj): string
    {
        if ($obj === null) {
            return '';
        }
        if (is_string($obj)) {
            return htmlspecialchars($obj, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if ($obj instanceof TextRun) {
            return $this->textRunToHtml($obj);
        }
        if ($obj instanceof Text) {
            return $this->textToHtml($obj);
        }
        if (method_exists($obj, 'getText')) {
            return htmlspecialchars((string) $obj->getText(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        return '';
    }
}
