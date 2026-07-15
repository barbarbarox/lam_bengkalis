<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaKategoriResource\Pages;
use App\Models\BeritaKategori;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BeritaKategoriResource extends Resource
{
    protected static ?string $model = BeritaKategori::class;

    protected static ?string $navigationIcon        = 'heroicon-o-tag';
    protected static ?string $navigationLabel       = 'Kategori Berita';
    protected static ?string $navigationGroup       = 'Berita';
    protected static ?int    $navigationSort         = 2;
    protected static ?string $modelLabel            = 'Kategori Berita';
    protected static ?string $pluralModelLabel      = 'Kategori Berita';
    protected static ?string $recordTitleAttribute  = 'nama';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(100)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) =>
                    $set('slug', Str::slug($state ?? ''))
                ),

            TextInput::make('slug')
                ->label('Slug URL')
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true)
                ->helperText('Diisi otomatis dari nama. Gunakan huruf kecil dan tanda hubung.')
                ->rules(['regex:/^[a-z0-9\-]+$/']),

            Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->maxLength(500)
                ->rows(2)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('berita_count')
                    ->label('Jumlah Berita')
                    ->counts('berita')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama')
            ->actions([
                EditAction::make()->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Kategori hanya bisa dihapus jika tidak ada berita di dalamnya.'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBeritaKategori::route('/'),
            'create' => Pages\CreateBeritaKategori::route('/create'),
            'edit'   => Pages\EditBeritaKategori::route('/{record}/edit'),
        ];
    }
}
