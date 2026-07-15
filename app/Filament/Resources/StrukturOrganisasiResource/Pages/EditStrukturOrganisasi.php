<?php

namespace App\Filament\Resources\StrukturOrganisasiResource\Pages;

use App\Filament\Resources\StrukturOrganisasiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStrukturOrganisasi extends EditRecord
{
    protected static string $resource = StrukturOrganisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()->requiresConfirmation()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
