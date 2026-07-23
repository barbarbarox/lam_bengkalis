<?php
namespace App\Filament\Resources\LamKecamatanResource\Pages;
use App\Filament\Resources\LamKecamatanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditLamKecamatan extends EditRecord {
    protected static string $resource = LamKecamatanResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
