<?php
namespace App\Filament\Resources\TokohAdatResource\Pages;
use App\Filament\Resources\TokohAdatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListTokohAdats extends ListRecords {
    protected static string $resource = TokohAdatResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
