<?php
namespace App\Filament\Resources\AgendaKegiatanResource\Pages;
use App\Filament\Resources\AgendaKegiatanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditAgendaKegiatan extends EditRecord {
    protected static string $resource = AgendaKegiatanResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
