<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriResource\Pages;
use App\Models\Galeri;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class GaleriResource extends Resource
{
    protected static ?string $model = Galeri::class;

    protected static ?string $navigationIcon    = 'heroicon-o-photo';
    protected static ?string $navigationLabel   = 'Galeri Foto';
    protected static ?string $navigationGroup   = 'Konten Situs';
    protected static ?int    $navigationSort    = 6;
    protected static ?string $modelLabel        = 'Foto Galeri';
    protected static ?string $pluralModelLabel  = 'Galeri';

    // ── Form ─────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Foto')
                ->schema([
                    FileUpload::make('foto_path')
                        ->label('Foto')
                        ->image()
                        ->required()
                        ->directory('galeri')
                        ->imagePreviewHeight('200')
                        ->maxSize(5120)
                        ->helperText('Format: JPG/PNG/WebP, maks 5 MB'),
                ]),

            Section::make('Informasi (Opsional)')
                ->schema([
                    TextInput::make('judul')
                        ->label('Judul Foto')
                        ->maxLength(200)
                        ->placeholder('Biarkan kosong jika tidak perlu judul'),

                    Textarea::make('deskripsi')
                        ->label('Deskripsi')
                        ->rows(2)
                        ->maxLength(500),

                    TextInput::make('urutan')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),

                    Toggle::make('is_aktif')
                        ->label('Aktif / Tampil di Galeri')
                        ->default(true),
                ])
                ->columns(2),
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

                ImageColumn::make('foto_path')
                    ->label('Foto')
                    ->height(60)
                    ->width(80)
                    ->disk('public'),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->default('—'),

                ToggleColumn::make('is_aktif')
                    ->label('Aktif'),

                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->since()
                    ->sortable(),
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
            'index'  => Pages\ListGaleris::route('/'),
            'create' => Pages\CreateGaleri::route('/create'),
            'edit'   => Pages\EditGaleri::route('/{record}/edit'),
        ];
    }
}
