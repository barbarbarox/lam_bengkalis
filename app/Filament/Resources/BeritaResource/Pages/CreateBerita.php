<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use App\Models\Berita;
use App\Services\HtmlSanitizer;
use App\Services\WordImportService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    /**
     * Proses form sebelum disimpan.
     * Menangani dua mode input: manual (TipTap) dan import dari file Word.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ── Mode: Import dari Word ────────────────────────────────────────────
        if (!empty($data['word_file'])) {
            $data = $this->processWordImport($data);
        }

        // ── Mode: Import dari PDF ─────────────────────────────────────────────
        if (!empty($data['pdf_file'])) {
            $data = $this->processPdfImport($data);
        }

        // ── Sanitasi HTML (XSS protection) ───────────────────────────────────
        $data['konten'] = HtmlSanitizer::clean($data['konten'] ?? '');

        // ── Auto-set tanggal publish ──────────────────────────────────────────
        if (($data['status'] ?? '') === Berita::STATUS_PUBLISHED && empty($data['tanggal_publish'])) {
            $data['tanggal_publish'] = now();
        }

        // ── Set penulis ───────────────────────────────────────────────────────
        $data['penulis_id'] = Auth::id();

        // ── Auto-generate excerpt ─────────────────────────────────────────────
        $data['excerpt'] = HtmlSanitizer::excerpt($data['konten'], 200);

        // ── Hapus field sementara (bukan kolom database) ──────────────────────
        unset($data['word_file'], $data['word_file_original_name'], $data['pdf_file'], $data['pdf_file_original_name'], $data['input_mode']);

        return $data;
    }

    /**
     * Proses file PDF: ekstrak teks ke paragraf HTML, auto-fill judul.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function processPdfImport(array $data): array
    {
        try {
            // Ambil path file yang baru di-upload (biasanya dalam array jika multiple, 
            // tapi FileUpload secara default string atau array 1 elemen)
            $filePath = is_array($data['pdf_file']) ? array_values($data['pdf_file'])[0] : $data['pdf_file'];

            $importer = new \App\Services\PdfImportService();
            $result   = $importer->importFromPath($filePath);

            // Auto-fill judul jika user tidak mengisi manual
            if (empty($data['judul']) && !empty($result['judul'])) {
                $data['judul'] = $result['judul'];
                $data['slug']  = Str::slug($result['judul']);
            }

            // Set konten HTML dari hasil PDF
            $data['konten'] = $result['konten'];

        } catch (\Throwable $e) {
            \Filament\Notifications\Notification::make()
                ->title('Gagal mengimport PDF')
                ->body('Terjadi kesalahan saat memproses file PDF: ' . $e->getMessage())
                ->danger()
                ->send();
        } finally {
            // Bersihkan file PDF temporary agar storage tidak penuh
            if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        return $data;
    }

    /**
     * Proses file Word: konversi ke HTML, auto-fill judul jika kosong.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function processWordImport(array $data): array
    {
        $relativePath = $data['word_file'];

        try {
            // FileUpload disk='public' menyimpan path relatif dari storage/app/public
            $absolutePath = Storage::disk('public')->path($relativePath);

            if (!file_exists($absolutePath)) {
                $this->sendWordImportWarning("File Word tidak ditemukan: {$absolutePath}");
                return $data;
            }

            $service = new WordImportService();
            $result  = $service->import($absolutePath);

            // Isi konten dari hasil import (hanya jika berhasil)
            if (!empty($result['konten_html'])) {
                $data['konten'] = $result['konten_html'];
            }

            // Auto-fill judul dari dokumen jika belum diisi
            if (empty($data['judul']) && !empty($result['judul'])) {
                $data['judul'] = $result['judul'];
                $data['slug']  = Str::slug($result['judul']);
            }

            // Notifikasi sukses
            $imageCount = count($result['extracted_images']);
            Notification::make()
                ->title('Dokumen Word berhasil diimport')
                ->body(
                    'Konten berhasil dikonversi.' .
                    ($imageCount > 0 ? " {$imageCount} gambar berhasil diekstrak." : '')
                )
                ->success()
                ->send();

        } catch (\Throwable $e) {
            $this->sendWordImportWarning('Gagal memproses file Word: ' . $e->getMessage());
        } finally {
            // Selalu hapus file temp dari disk public
            if (!empty($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }

        return $data;
    }

    /**
     * Kirim notifikasi peringatan import Word.
     */
    protected function sendWordImportWarning(string $message): void
    {
        Notification::make()
            ->title('Gagal import Word')
            ->body($message)
            ->warning()
            ->persistent()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
