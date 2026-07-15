<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use App\Models\Berita;
use App\Services\HtmlSanitizer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    /**
     * Sanitasi konten HTML sebelum disimpan ke database.
     * Mencegah XSS dan injeksi HTML berbahaya dari rich text editor.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Wajib: sanitasi konten rich text via HTMLPurifier
        $data['konten'] = HtmlSanitizer::clean($data['konten'] ?? '');

        // Auto-set tanggal publish jika status published dan belum ada tanggal
        if ($data['status'] === Berita::STATUS_PUBLISHED && empty($data['tanggal_publish'])) {
            $data['tanggal_publish'] = now();
        }

        // Set penulis sebagai user yang sedang login
        $data['penulis_id'] = Auth::id();

        // Auto-generate excerpt bersih dari konten
        $data['excerpt'] = HtmlSanitizer::excerpt($data['konten'], 200);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
