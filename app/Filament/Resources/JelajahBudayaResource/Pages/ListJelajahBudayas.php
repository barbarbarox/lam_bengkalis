<?php

namespace App\Filament\Resources\JelajahBudayaResource\Pages;

use App\Filament\Resources\JelajahBudayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJelajahBudayas extends ListRecords
{
    protected static string $resource = JelajahBudayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
