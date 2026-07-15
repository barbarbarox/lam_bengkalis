<?php

namespace App\Filament\Resources\KontakAduanResource\Pages;

use App\Filament\Resources\KontakAduanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\KontakAduan;

class ListKontakAduan extends ListRecords
{
    protected static string $resource = KontakAduanResource::class;

    protected function getHeaderActions(): array { return []; }

    public function getTabs(): array
    {
        return [
            'semua'    => Tab::make('Semua'),
            'baru'     => Tab::make('Baru')
                ->icon('heroicon-o-bell-alert')
                ->badge(KontakAduan::baru()->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', KontakAduan::STATUS_BARU)),
            'diproses' => Tab::make('Diproses')
                ->icon('heroicon-o-arrow-path')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', KontakAduan::STATUS_DIPROSES)),
            'selesai'  => Tab::make('Selesai')
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', KontakAduan::STATUS_SELESAI)),
        ];
    }
}
