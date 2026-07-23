<?php
namespace App\Filament\Resources\GelarAdatResource\Pages;
use App\Filament\Resources\GelarAdatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListGelarAdats extends ListRecords {
    protected static string $resource = GelarAdatResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
