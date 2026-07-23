<?php
namespace App\Filament\Resources\HukumAdatResource\Pages;
use App\Filament\Resources\HukumAdatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListHukumAdats extends ListRecords {
    protected static string $resource = HukumAdatResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
