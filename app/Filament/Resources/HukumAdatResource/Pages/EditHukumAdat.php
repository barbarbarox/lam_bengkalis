<?php
namespace App\Filament\Resources\HukumAdatResource\Pages;
use App\Filament\Resources\HukumAdatResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditHukumAdat extends EditRecord {
    protected static string $resource = HukumAdatResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
