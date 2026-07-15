<?php

namespace App\Filament\Resources\BeritaKategoriResource\Pages;

use App\Filament\Resources\BeritaKategoriResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBeritaKategori extends EditRecord
{
    protected static string $resource = BeritaKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->requiresConfirmation()
                ->modalDescription('Kategori hanya bisa dihapus jika tidak ada berita yang menggunakannya.'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
