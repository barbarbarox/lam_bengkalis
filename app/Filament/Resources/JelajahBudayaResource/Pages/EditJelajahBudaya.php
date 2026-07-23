<?php

namespace App\Filament\Resources\JelajahBudayaResource\Pages;

use App\Filament\Resources\JelajahBudayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJelajahBudaya extends EditRecord
{
    protected static string $resource = JelajahBudayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
