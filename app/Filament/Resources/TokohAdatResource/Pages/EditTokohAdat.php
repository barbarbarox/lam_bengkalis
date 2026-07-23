<?php
namespace App\Filament\Resources\TokohAdatResource\Pages;
use App\Filament\Resources\TokohAdatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditTokohAdat extends EditRecord {
    protected static string $resource = TokohAdatResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
