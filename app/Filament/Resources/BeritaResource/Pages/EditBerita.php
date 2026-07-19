<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use App\Models\Berita;
use App\Services\HtmlSanitizer;
use App\Services\WordImportService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ── Tombol publish/unpublish cepat ────────────────────────────────
            Action::make('toggleStatus')
                ->label(fn () => $this->record->status === Berita::STATUS_PUBLISHED
                    ? 'Tarik ke Draft'
                    : 'Terbitkan'
                )
                ->icon(fn () => $this->record->status === Berita::STATUS_PUBLISHED
                    ? 'heroicon-o-arrow-uturn-left'
                    : 'heroicon-o-arrow-up-tray'
                )
                ->color(fn () => $this->record->status === Berita::STATUS_PUBLISHED
                    ? 'warning'
                    : 'success'
                )
                ->action(function () {
                    if ($this->record->status === Berita::STATUS_PUBLISHED) {
                        $this->record->unpublish();
                        Notification::make()
                            ->title('Berita dikembalikan ke Draft')
                            ->icon('heroicon-o-arrow-uturn-left')
                            ->warning()
                            ->send();
                    } else {
                        $this->record->publish();
                        Notification::make()
                            ->title('Berita berhasil diterbitkan')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->send();
                    }
                    $this->refreshFormData(['status', 'tanggal_publish']);
                }),

            // ── Tombol Import dari Word (ganti konten) ────────────────────────
            Action::make('importWord')
                ->label('Import dari Word')
                ->icon('heroicon-o-document-arrow-up')
                ->color('gray')
                ->modalHeading('Import Konten dari File Word')
                ->modalDescription(
                    'Upload file .docx untuk mengganti konten berita ini. ' .
                    'Judul yang sudah ada tidak akan berubah.'
                )
                ->modalSubmitActionLabel('Proses & Terapkan')
                ->form([
                    FileUpload::make('word_file_modal')
                        ->label('Pilih File Word (.docx)')
                        ->disk('public')
                        ->directory('temp/word-import')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/msword',
                        ])
                        ->maxSize(10240)
                        ->required()
                        ->helperText('Maks. 10 MB. Konten saat ini akan digantikan oleh isi dokumen Word.'),
                ])
                ->action(function (array $data) {
                    $this->processWordImportForEdit($data['word_file_modal'] ?? null);
                }),

            // ── Tombol Import dari PDF (ganti konten) ─────────────────────────
            Action::make('importPdf')
                ->label('Import dari PDF')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->modalHeading('Import Teks dari File PDF')
                ->modalDescription(
                    'Upload file .pdf untuk mengganti konten berita ini dengan ekstraksi teks dari PDF. ' .
                    'Judul yang sudah ada tidak akan berubah. (Catatan: Gambar/Tabel tidak ter-import)'
                )
                ->modalSubmitActionLabel('Proses & Terapkan')
                ->form([
                    FileUpload::make('pdf_file_modal')
                        ->label('Pilih File PDF (.pdf)')
                        ->disk('public')
                        ->directory('temp/pdf-import')
                        ->visibility('private')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240)
                        ->required()
                        ->helperText('Maks. 10 MB. Konten saat ini akan digantikan oleh teks dari dokumen PDF.'),
                ])
                ->action(function (array $data) {
                    $this->processPdfImportForEdit($data['pdf_file_modal'] ?? null);
                }),

            DeleteAction::make()->requiresConfirmation(),
        ];
    }

    /**
     * Proses import Word saat edit — update konten record secara langsung.
     */
    protected function processWordImportForEdit(?string $relativeWordPath): void
    {
        if (!$relativeWordPath) {
            return;
        }

        try {
            $absolutePath = Storage::disk('public')->path($relativeWordPath);

            if (!file_exists($absolutePath)) {
                Notification::make()
                    ->title('File tidak ditemukan di server')
                    ->danger()
                    ->send();
                return;
            }

            $service = new WordImportService();
            $result  = $service->import($absolutePath);

            if (empty($result['konten_html'])) {
                Notification::make()
                    ->title('Dokumen kosong atau tidak dapat dibaca')
                    ->warning()
                    ->send();
                return;
            }

            $cleanHtml = HtmlSanitizer::clean($result['konten_html']);

            $updateData = [
                'konten'  => $cleanHtml,
                'excerpt' => HtmlSanitizer::excerpt($cleanHtml, 200),
            ];

            // Jika judul record masih kosong, ambil dari Word
            if (empty($this->record->judul) && !empty($result['judul'])) {
                $updateData['judul'] = $result['judul'];
                $updateData['slug']  = Str::slug($result['judul']);
            }

            $this->record->update($updateData);
            $this->refreshFormData(['konten', 'judul', 'slug', 'excerpt']);

            $imageCount = count($result['extracted_images']);
            Notification::make()
                ->title('Konten berhasil diperbarui dari Word')
                ->body(
                    'Konten berhasil dikonversi dan diterapkan.' .
                    ($imageCount > 0 ? " {$imageCount} gambar berhasil diekstrak." : '')
                )
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal import Word')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        } finally {
            // Hapus file temp
            if (!empty($relativeWordPath)) {
                Storage::disk('public')->delete($relativeWordPath);
            }
        }
    }

    /**
     * Proses import PDF saat edit — update konten record secara langsung.
     */
    protected function processPdfImportForEdit(?string $relativePdfPath): void
    {
        if (!$relativePdfPath) {
            return;
        }

        try {
            $importer = new \App\Services\PdfImportService();
            $result   = $importer->importFromPath($relativePdfPath);

            if (empty($result['konten'])) {
                Notification::make()
                    ->title('Dokumen kosong atau teks tidak dapat dibaca')
                    ->warning()
                    ->send();
                return;
            }

            $cleanHtml = HtmlSanitizer::clean($result['konten']);

            $updateData = [
                'konten'  => $cleanHtml,
                'excerpt' => HtmlSanitizer::excerpt($cleanHtml, 200),
            ];

            // Jika judul record masih kosong, ambil dari PDF
            if (empty($this->record->judul) && !empty($result['judul'])) {
                $updateData['judul'] = $result['judul'];
                $updateData['slug']  = Str::slug($result['judul']);
            }

            $this->record->update($updateData);
            $this->refreshFormData(['konten', 'judul', 'slug', 'excerpt']);

            Notification::make()
                ->title('Konten teks berhasil diperbarui dari PDF')
                ->body('Teks dari PDF berhasil di-import. Harap periksa dan rapikan kembali konten Anda.')
                ->warning() // Menggunakan warna kuning/warning agar sesuai dengan alert peringatan
                ->send();

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal import PDF')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        } finally {
            // Hapus file temp
            if (!empty($relativePdfPath) && Storage::disk('public')->exists($relativePdfPath)) {
                Storage::disk('public')->delete($relativePdfPath);
            }
        }
    }

    /**
     * Sanitasi konten HTML sebelum disimpan saat update.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sanitasi konten rich text via HTMLPurifier
        $data['konten'] = HtmlSanitizer::clean($data['konten'] ?? '');

        // Auto-set tanggal publish jika status berubah ke published
        if (
            ($data['status'] ?? '') === Berita::STATUS_PUBLISHED
            && empty($data['tanggal_publish'])
            && $this->record->tanggal_publish === null
        ) {
            $data['tanggal_publish'] = now();
        }

        // Update excerpt
        $data['excerpt'] = HtmlSanitizer::excerpt($data['konten'], 200);

        // Hapus field sementara (bukan kolom database)
        unset($data['word_file'], $data['word_file_original_name']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
