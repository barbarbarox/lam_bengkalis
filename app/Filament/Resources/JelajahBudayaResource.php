<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JelajahBudayaResource\Pages;
use App\Models\JelajahBudaya;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JelajahBudayaResource extends Resource
{
    protected static ?string $model = JelajahBudaya::class;

    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Jelajah Budaya';
    protected static ?string $navigationGroup = 'Konten Beranda';
    protected static ?string $modelLabel      = 'Kartu Jelajah';
    protected static ?string $pluralModelLabel = 'Kartu Jelajah Budaya';
    protected static ?int    $navigationSort  = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Kartu')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Kategori')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('Contoh: Upacara Adat, Seni & Tradisi'),

                    Forms\Components\TextInput::make('url')
                        ->label('URL Tujuan (Link)')
                        ->url()
                        ->maxLength(500)
                        ->placeholder('https://... atau /halaman-internal')
                        ->helperText('Kosongkan untuk nonaktifkan link. Bisa link internal (/berita?q=adat) atau eksternal.'),

                    Forms\Components\ColorPicker::make('warna')
                        ->label('Warna Aksen')
                        ->default('#1B5E20')
                        ->helperText('Digunakan sebagai fallback jika foto tidak ada, dan sebagai overlay warna.'),

                    Forms\Components\TextInput::make('urutan')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),

                    Forms\Components\Toggle::make('is_aktif')
                        ->label('Aktif / Tampil di beranda')
                        ->default(true)
                        ->inline(false),
                ])->columns(2),

            Forms\Components\Section::make('Foto Background Kartu')
                ->schema([
                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto Kartu')
                        ->image()
                        ->disk('public')
                        ->directory('jelajah-budaya')
                        ->imageEditor()
                        ->imageCropAspectRatio('3:4')
                        ->imageResizeTargetWidth('600')
                        ->imageResizeTargetHeight('800')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(3072)
                        ->helperText('Rasio ideal 3:4 (portrait). Maks 3 MB. Jika kosong, kartu tampil dengan warna solid.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->height(60)
                    ->width(50)
                    ->defaultImageUrl(fn (JelajahBudaya $r): string =>
                        'https://ui-avatars.com/api/?name='.urlencode($r->nama).'&background='.ltrim($r->warna,'#').'&color=fff&size=100'
                    ),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\ColorColumn::make('warna')
                    ->label('Warna'),

                Tables\Columns\TextColumn::make('url')
                    ->label('Link')
                    ->limit(50)
                    ->placeholder('(tidak ada)')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_aktif')
                    ->label('Aktif'),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_aktif')->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJelajahBudayas::route('/'),
            'create' => Pages\CreateJelajahBudaya::route('/create'),
            'edit'   => Pages\EditJelajahBudaya::route('/{record}/edit'),
        ];
    }
}
