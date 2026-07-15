<?php

namespace App\Filament\Resources\KontakAduanResource\Pages;

use App\Filament\Resources\KontakAduanResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditKontakAduan extends EditRecord
{
    protected static string $resource = KontakAduanResource::class;

    protected function getHeaderActions(): array { return []; }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Status aduan diperbarui')
            ->icon('heroicon-o-check-circle')
            ->success();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
