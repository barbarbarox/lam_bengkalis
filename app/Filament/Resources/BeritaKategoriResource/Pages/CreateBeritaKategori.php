<?php

namespace App\Filament\Resources\BeritaKategoriResource\Pages;

use App\Filament\Resources\BeritaKategoriResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBeritaKategori extends CreateRecord
{
    protected static string $resource = BeritaKategoriResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
