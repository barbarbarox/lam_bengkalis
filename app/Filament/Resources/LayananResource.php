<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananResource\Pages;
use App\Models\Layanan;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationIcon    = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel   = 'Daftar Layanan';
    protected static ?string $navigationGroup   = 'Konten Situs';
    protected static ?int    $navigationSort    = 5;
    protected static ?string $modelLabel        = 'Layanan';
    protected static ?string $pluralModelLabel  = 'Layanan';

    // ── Form ─────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Informasi Layanan')
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Layanan')
                        ->required()
                        ->maxLength(200),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi Singkat')
                        ->rows(3)
                        ->maxLength(500),

                    TextInput::make('url')
                        ->label('URL / Tautan')
                        ->url()
                        ->placeholder('https://... (kosongkan jika tidak ada link)')
                        ->helperText('Untuk Museum: masukkan URL Jejak Layar. Bisa link eksternal atau internal.'),

                    \Filament\Forms\Components\Radio::make('jenis_icon')
                        ->label('Gunakan Icon atau Gambar Sendiri?')
                        ->options([
                            'icon' => 'Gunakan Icon/Emote',
                            'image' => 'Upload Gambar Sendiri',
                        ])
                        ->default('icon')
                        ->live(),

                    IconPicker::make('icon')
                        ->label('Icon')
                        ->sets(['heroicons', 'fontawesome-solid', 'fontawesome-regular', 'fontawesome-brands'])
                        ->columns([
                            'default' => 3,
                            'lg'      => 5,
                        ])
                        ->preload()
                        ->visible(fn (\Filament\Forms\Get $get) => $get('jenis_icon') === 'icon')
                        ->helperText('Pilih icon yang tersedia.'),

                    \Filament\Forms\Components\FileUpload::make('image')
                        ->label('Upload Gambar/Icon')
                        ->image()
                        ->directory('layanan')
                        ->visible(fn (\Filament\Forms\Get $get) => $get('jenis_icon') === 'image')
                        ->helperText('Upload gambar atau icon SVG/PNG Anda sendiri.'),
                ])
                ->columns(1),

            Section::make('Tampilan & Urutan')
                ->schema([
                    ColorPicker::make('warna')
                        ->label('Warna Aksen Kartu')
                        ->helperText('Default: kuning emas #F99522'),

                    TextInput::make('urutan')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),

                    Toggle::make('is_aktif')
                        ->label('Aktif / Tampil di Situs')
                        ->default(true),
                ])
                ->columns(3),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                TextColumn::make('nama')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar/Icon')
                    ->defaultImageUrl(fn ($record) => $record->jenis_icon === 'image' && $record->image ? \Illuminate\Support\Facades\Storage::url($record->image) : null)
                    ->square()
                    ->size(40)
                    ->visible(fn ($record) => true)
                    ->getStateUsing(function ($record) {
                        if ($record->jenis_icon === 'image' && $record->image) {
                            return $record->image;
                        }
                        return null;
                    }),

                IconColumn::make('icon')
                    ->label('Icon')
                    ->size(IconColumn\IconColumnSize::Medium)
                    ->visible(fn ($record) => true) // we might want to hide this if we only show one, but we can't easily dynamically show/hide per row in the same column unless we use view
                    // actually, better to just use a custom view or let ImageColumn and IconColumn show if they exist.
                    // Wait, IconColumn only renders if there's a valid icon string. If image is selected, icon might be null.
                    ->getStateUsing(fn ($record) => $record->jenis_icon === 'icon' ? $record->icon : null),

                TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->url(fn ($record) => $record->url, true),

                ColorColumn::make('warna')
                    ->label('Warna')
                    ->sortable(false),

                ToggleColumn::make('is_aktif')
                    ->label('Aktif'),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ── Pages ─────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'edit'   => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }
}
