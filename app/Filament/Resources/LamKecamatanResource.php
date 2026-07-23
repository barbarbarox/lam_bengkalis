<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LamKecamatanResource\Pages;
use App\Models\LamKecamatan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class LamKecamatanResource extends Resource
{
    protected static ?string $model             = LamKecamatan::class;
    protected static ?string $navigationIcon    = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel   = 'LAM Kecamatan';
    protected static ?string $navigationGroup   = 'Kelembagaan';
    protected static ?int    $navigationSort    = 10;
    protected static ?string $modelLabel        = 'LAM Kecamatan';
    protected static ?string $pluralModelLabel  = 'LAM Kecamatan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Kecamatan')
                ->schema([
                    TextInput::make('nama_kecamatan')->label('Nama Kecamatan')->required()->maxLength(100),
                    TextInput::make('nama_ketua')->label('Nama Ketua LAM')->maxLength(150),
                    TextInput::make('jabatan_ketua')->label('Jabatan')->default('Ketua LAM Kecamatan')->maxLength(100),
                    TextInput::make('jumlah_nagori')->label('Jumlah Desa/Kelurahan')->numeric()->minValue(0),
                ])->columns(2),

            Section::make('Kontak')
                ->schema([
                    Textarea::make('alamat')->label('Alamat Sekretariat')->rows(2),
                    TextInput::make('no_telp')->label('No. Telepon')->maxLength(30)->tel(),
                    TextInput::make('email')->label('Email')->email()->maxLength(150),
                ])->columns(2),

            Section::make('Media')
                ->schema([
                    FileUpload::make('foto_ketua_path')->label('Foto Ketua')
                        ->image()->disk('public')->directory('lam-kecamatan')->imageEditor()->maxSize(2048),
                    FileUpload::make('foto_gedung_path')->label('Foto Gedung/Balai')
                        ->image()->disk('public')->directory('lam-kecamatan')->maxSize(2048),
                ])->columns(2),

            Section::make('Deskripsi & Pengaturan')
                ->schema([
                    Textarea::make('deskripsi')->label('Keterangan Singkat')->rows(3)->columnSpanFull(),
                    TextInput::make('urutan')->label('Urutan')->numeric()->default(0),
                    Toggle::make('is_aktif')->label('Aktif / Tampil')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->label('#')->sortable()->width(50),
                ImageColumn::make('foto_ketua_path')->label('Foto')->square()->size(42)->disk('public'),
                TextColumn::make('nama_kecamatan')->label('Kecamatan')->searchable()->sortable(),
                TextColumn::make('nama_ketua')->label('Ketua')->searchable(),
                TextColumn::make('no_telp')->label('Telepon'),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLamKecamatans::route('/'),
            'create' => Pages\CreateLamKecamatan::route('/create'),
            'edit'   => Pages\EditLamKecamatan::route('/{record}/edit'),
        ];
    }
}
