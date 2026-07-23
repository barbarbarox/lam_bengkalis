<?php
namespace App\Filament\Resources\GelarAdatResource\Pages;
use App\Filament\Resources\GelarAdatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditGelarAdat extends EditRecord {
    protected static string $resource = GelarAdatResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
