<?php

namespace App\Filament\Resources\KontakAduanResource\Pages;

use App\Filament\Resources\KontakAduanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewKontakAduan extends ViewRecord
{
    protected static string $resource = KontakAduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }
}
