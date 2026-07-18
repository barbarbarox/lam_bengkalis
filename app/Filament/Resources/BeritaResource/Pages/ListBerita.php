<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use App\Models\Berita;
use App\Models\BeritaKategori;
use App\Services\HtmlSanitizer;
use App\Services\WordImportService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListBerita extends ListRecords
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ── Tulis Berita Manual ──────────────────────────────────────────
            CreateAction::make()
                ->label('Tulis Berita Baru')
                ->icon('heroicon-o-pencil-square'),

            // ── Import dari Word (Quick Import) ──────────────────────────────
            Action::make('quickImportWord')
                ->label('Import dari Word')
                ->icon('heroicon-o-document-arrow-up')
                ->color('gray')
                ->modalHeading('Import Berita dari File Word')
                ->modalDescription(
                    'Upload file .docx untuk membuat berita baru secara otomatis. ' .
                    'Anda tetap bisa mengedit hasilnya setelah disimpan.'
                )
                ->modalSubmitActionLabel('Proses & Buat Berita')
                ->form([
                    FileUpload::make('word_file')
                        ->label('File Word (.docx)')
                        ->disk('public')
                        ->directory('temp/word-import')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/msword',
                        ])
                        ->maxSize(10240)
                        ->required()
                        ->helperText('Maks. 10 MB. Format: .docx (Microsoft Word 2007+)'),

                    Select::make('berita_kategori_id')
                        ->label('Kategori')
                        ->required()
                        ->options(BeritaKategori::pluck('nama', 'id'))
                        ->searchable()
                        ->preload(),

                    Select::make('status')
                        ->label('Status Publikasi')
                        ->options([
                            Berita::STATUS_DRAFT     => 'Simpan sebagai Draft',
                            Berita::STATUS_PUBLISHED => 'Terbitkan Langsung',
                        ])
                        ->default(Berita::STATUS_DRAFT)
                        ->required()
                        ->native(false),
                ])
                ->action(function (array $data) {
                    $this->processQuickWordImport($data);
                }),
        ];
    }

    /**
     * Proses quick import Word langsung dari halaman daftar berita.
     *
     * @param  array<string, mixed>  $data
     */
    protected function processQuickWordImport(array $data): void
    {
        $relativePath = $data['word_file'] ?? null;

        if (!$relativePath) {
            Notification::make()->title('File tidak dipilih')->warning()->send();
            return;
        }

        try {
            // Gunakan disk public (sesuai FileUpload disk)
            $absolutePath = Storage::disk('public')->path($relativePath);

            if (!file_exists($absolutePath)) {
                Notification::make()
                    ->title('File Word tidak ditemukan di server')
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

            $judul   = !empty($result['judul'])
                ? $result['judul']
                : 'Artikel dari Word — ' . now()->format('d M Y H:i');
            $slug    = Str::slug($judul);
            $konten  = HtmlSanitizer::clean($result['konten_html']);
            $excerpt = HtmlSanitizer::excerpt($konten, 200);

            // Pastikan slug unik
            $baseSlug = $slug;
            $counter  = 1;
            while (Berita::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $berita = Berita::create([
                'judul'              => $judul,
                'slug'               => $slug,
                'berita_kategori_id' => $data['berita_kategori_id'],
                'konten'             => $konten,
                'excerpt'            => $excerpt,
                'status'             => $data['status'],
                'penulis_id'         => Auth::id(),
                'tanggal_publish'    => $data['status'] === Berita::STATUS_PUBLISHED ? now() : null,
            ]);

            $imageCount = count($result['extracted_images']);
            Notification::make()
                ->title('Berita berhasil dibuat dari Word')
                ->body(
                    "Berita \"<strong>{$judul}</strong>\" berhasil diimport." .
                    ($imageCount > 0 ? " {$imageCount} gambar diekstrak." : '') .
                    ' Silakan klik Edit untuk menyempurnakan.'
                )
                ->success()
                ->duration(8000)
                ->send();

            $this->resetTable();

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal import Word')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        } finally {
            // Selalu hapus file temp
            if (!empty($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        }
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua')
                ->icon('heroicon-o-queue-list'),

            'terbit' => Tab::make('Terbit')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Berita::STATUS_PUBLISHED)),

            'draft' => Tab::make('Draft')
                ->icon('heroicon-o-pencil')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Berita::STATUS_DRAFT)),
        ];
    }
}
