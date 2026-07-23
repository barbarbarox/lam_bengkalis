<?php
namespace App\Filament\Resources\DokumenPeraturanResource\Pages;
use App\Filament\Resources\DokumenPeraturanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditDokumenPeraturan extends EditRecord {
    protected static string $resource = DokumenPeraturanResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
