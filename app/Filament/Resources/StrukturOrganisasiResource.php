<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StrukturOrganisasiResource\Pages;
use App\Models\StrukturOrganisasi;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StrukturOrganisasiResource extends Resource
{
    protected static ?string $model = StrukturOrganisasi::class;

    protected static ?string $navigationIcon   = 'heroicon-o-user-group';
    protected static ?string $navigationLabel  = 'Struktur Organisasi';
    protected static ?string $navigationGroup  = 'Profil Lembaga';
    protected static ?int    $navigationSort    = 2;
    protected static ?string $modelLabel       = 'Anggota Struktur';
    protected static ?string $pluralModelLabel = 'Struktur Organisasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Anggota')
                ->schema([
                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('jabatan')
                        ->label('Jabatan')
                        ->required()
                        ->maxLength(200)
                        ->placeholder('contoh: Penyelaras, Anggota'),

                    Select::make('kategori')
                        ->label('Kategori / Majelis')
                        ->required()
                        ->options([
                            StrukturOrganisasi::KATEGORI_MKA        => 'MKA — Majelis Kerapatan Adat',
                            StrukturOrganisasi::KATEGORI_DPH        => 'DPH — Dewan Pengurus Harian',
                            StrukturOrganisasi::KATEGORI_BIDANG     => 'Bidang — Bidang Kerja DPH',
                            StrukturOrganisasi::KATEGORI_DKA        => 'DKA — Dewan Kehormatan Adat',
                            StrukturOrganisasi::KATEGORI_PEMBIMBING => 'Pembimbing Utama',
                            StrukturOrganisasi::KATEGORI_PENASEHAT  => 'Penasehat',
                        ])
                        ->native(false)
                        ->live(), // reactive agar field nama_bidang muncul kondisional

                    // Nama Bidang — hanya ditampilkan jika kategori = 'Bidang'
                    TextInput::make('nama_bidang')
                        ->label('Nama Bidang')
                        ->maxLength(200)
                        ->placeholder('contoh: Bidang Organisasi Dan Tata Laksana')
                        ->helperText('Wajib diisi jika kategori "Bidang". Harus konsisten persis dengan bidang lain agar pengelompokan di situs berfungsi.')
                        ->required(fn (Get $get) => $get('kategori') === StrukturOrganisasi::KATEGORI_BIDANG)
                        ->visible(fn (Get $get) => $get('kategori') === StrukturOrganisasi::KATEGORI_BIDANG),

                    // Tingkat Jabatan — menentukan posisi anggota di tampilan publik
                    Select::make('tingkat_jabatan')
                        ->label('Tingkat Jabatan')
                        ->required()
                        ->options([
                            StrukturOrganisasi::TINGKAT_PIMPINAN => 'Pimpinan (Penyelaras / Ketua)',
                            StrukturOrganisasi::TINGKAT_ANGGOTA  => 'Anggota Biasa',
                        ])
                        ->default(StrukturOrganisasi::TINGKAT_ANGGOTA)
                        ->native(false)
                        ->helperText('Pimpinan selalu tampil langsung di kartu publik. Anggota Biasa tersembunyi dan hanya muncul saat pengunjung klik "Lihat Anggota Lainnya".'),

                    TextInput::make('periode')
                        ->label('Periode')
                        ->maxLength(20)
                        ->placeholder('contoh: 2024–2029'),

                    TextInput::make('urutan')
                        ->label('Urutan Tampil')
                        ->required()
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Semakin kecil angka, semakin atas posisinya. Untuk kategori Bidang: pimpinan biasanya urutan 1, anggota mulai urutan 2.'),

                    Toggle::make('is_active')
                        ->label('Tampilkan di Situs')
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpan(2),

            Section::make('Foto')
                ->schema([
                    FileUpload::make('foto')
                        ->label('Foto Anggota')
                        ->image()
                        ->disk('public')
                        ->directory('struktur')
                        ->imageEditor()
                        ->imageEditorAspectRatios(['1:1', '3:4'])
                        ->maxSize(1024)
                        ->helperText('Maks. 1 MB. Rasio 1:1 direkomendasikan. Jika kosong, avatar inisial nama akan ditampilkan.'),
                ])
                ->columnSpan(1),

        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('No.')
                    ->sortable()
                    ->width(50),

                ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->width(40)
                    ->height(40)
                    ->defaultImageUrl(fn () => null),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn (StrukturOrganisasi $r) => $r->jabatan),

                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        StrukturOrganisasi::KATEGORI_MKA        => 'primary',
                        StrukturOrganisasi::KATEGORI_DPH        => 'info',
                        StrukturOrganisasi::KATEGORI_BIDANG     => 'success',
                        StrukturOrganisasi::KATEGORI_DKA        => 'warning',
                        StrukturOrganisasi::KATEGORI_PEMBIMBING => 'gray',
                        StrukturOrganisasi::KATEGORI_PENASEHAT  => 'gray',
                        default                                  => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('nama_bidang')
                    ->label('Bidang')
                    ->searchable()
                    ->placeholder('—')
                    ->limit(35)
                    ->tooltip(fn (TextColumn $column): ?string => strlen($column->getState() ?? '') > 35 ? $column->getState() : null),

                TextColumn::make('tingkat_jabatan')
                    ->label('Tingkat')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pimpinan' => 'warning',
                        'anggota'  => 'gray',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->placeholder('—'),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
            ])
            ->defaultSort('kategori')
            ->reorderable('urutan')
            ->filters([
                SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        StrukturOrganisasi::KATEGORI_MKA        => 'MKA — Majelis Kerapatan Adat',
                        StrukturOrganisasi::KATEGORI_DPH        => 'DPH — Dewan Pengurus Harian',
                        StrukturOrganisasi::KATEGORI_BIDANG     => 'Bidang — Bidang Kerja DPH',
                        StrukturOrganisasi::KATEGORI_DKA        => 'DKA — Dewan Kehormatan Adat',
                        StrukturOrganisasi::KATEGORI_PEMBIMBING => 'Pembimbing Utama',
                        StrukturOrganisasi::KATEGORI_PENASEHAT  => 'Penasehat',
                    ])
                    ->native(false),

                SelectFilter::make('tingkat_jabatan')
                    ->label('Tingkat Jabatan')
                    ->options([
                        'pimpinan' => 'Pimpinan',
                        'anggota'  => 'Anggota Biasa',
                    ])
                    ->native(false),

                TernaryFilter::make('is_active')
                    ->label('Status Tampil')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton()->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStrukturOrganisasi::route('/'),
            'create' => Pages\CreateStrukturOrganisasi::route('/create'),
            'edit'   => Pages\EditStrukturOrganisasi::route('/{record}/edit'),
        ];
    }
}
