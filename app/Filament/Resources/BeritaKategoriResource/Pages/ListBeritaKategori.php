<?php

namespace App\Filament\Resources\BeritaKategoriResource\Pages;

use App\Filament\Resources\BeritaKategoriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBeritaKategori extends ListRecords
{
    protected static string $resource = BeritaKategoriResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()->label('Tambah Kategori')];
    }
}
