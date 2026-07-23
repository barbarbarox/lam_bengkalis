<?php
namespace App\Filament\Resources\DokumenPeraturanResource\Pages;
use App\Filament\Resources\DokumenPeraturanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListDokumenPeraturans extends ListRecords {
    protected static string $resource = DokumenPeraturanResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
