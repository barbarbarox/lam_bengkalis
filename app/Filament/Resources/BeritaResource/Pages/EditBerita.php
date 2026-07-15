<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use App\Models\Berita;
use App\Services\HtmlSanitizer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol publish/unpublish cepat
            Action::make('toggleStatus')
                ->label(fn () => $this->record->status === Berita::STATUS_PUBLISHED
                    ? 'Tarik ke Draft'
                    : 'Terbitkan'
                )
                ->icon(fn () => $this->record->status === Berita::STATUS_PUBLISHED
                    ? 'heroicon-o-arrow-uturn-left'
                    : 'heroicon-o-arrow-up-tray'
                )
                ->color(fn () => $this->record->status === Berita::STATUS_PUBLISHED
                    ? 'warning'
                    : 'success'
                )
                ->action(function () {
                    if ($this->record->status === Berita::STATUS_PUBLISHED) {
                        $this->record->unpublish();
                        Notification::make()
                            ->title('Berita dikembalikan ke Draft')
                            ->icon('heroicon-o-arrow-uturn-left')
                            ->warning()
                            ->send();
                    } else {
                        $this->record->publish();
                        Notification::make()
                            ->title('Berita berhasil diterbitkan')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->send();
                    }
                    $this->refreshFormData(['status', 'tanggal_publish']);
                }),

            DeleteAction::make()->requiresConfirmation(),
        ];
    }

    /**
     * Sanitasi konten HTML sebelum disimpan saat update.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Wajib: sanitasi konten rich text via HTMLPurifier
        $data['konten'] = HtmlSanitizer::clean($data['konten'] ?? '');

        // Auto-set tanggal publish jika status berubah ke published
        if (
            $data['status'] === Berita::STATUS_PUBLISHED
            && empty($data['tanggal_publish'])
            && $this->record->tanggal_publish === null
        ) {
            $data['tanggal_publish'] = now();
        }

        // Update excerpt
        $data['excerpt'] = HtmlSanitizer::excerpt($data['konten'], 200);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
