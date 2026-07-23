<?php
namespace App\Filament\Resources\PendidikanPelatihanResource\Pages;
use App\Filament\Resources\PendidikanPelatihanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditPendidikanPelatihan extends EditRecord {
    protected static string $resource = PendidikanPelatihanResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
