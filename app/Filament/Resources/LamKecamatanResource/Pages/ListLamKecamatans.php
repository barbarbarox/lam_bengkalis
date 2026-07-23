<?php
namespace App\Filament\Resources\LamKecamatanResource\Pages;
use App\Filament\Resources\LamKecamatanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListLamKecamatans extends ListRecords {
    protected static string $resource = LamKecamatanResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
