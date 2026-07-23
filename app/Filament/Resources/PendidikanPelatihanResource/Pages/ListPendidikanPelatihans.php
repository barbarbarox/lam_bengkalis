<?php
namespace App\Filament\Resources\PendidikanPelatihanResource\Pages;
use App\Filament\Resources\PendidikanPelatihanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListPendidikanPelatihans extends ListRecords {
    protected static string $resource = PendidikanPelatihanResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
