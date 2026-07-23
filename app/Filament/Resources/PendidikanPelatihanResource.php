<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendidikanPelatihanResource\Pages;
use App\Models\PendidikanPelatihan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PendidikanPelatihanResource extends Resource
{
    protected static ?string $model            = PendidikanPelatihan::class;
    protected static ?string $navigationIcon   = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel  = 'Pendidikan & Pelatihan';
    protected static ?string $navigationGroup  = 'Kegiatan';
    protected static ?int    $navigationSort   = 32;
    protected static ?string $modelLabel       = 'Program';
    protected static ?string $pluralModelLabel = 'Pendidikan & Pelatihan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Program')
                ->schema([
                    TextInput::make('judul')->label('Judul Program')->required()->maxLength(500)->columnSpanFull(),
                    Select::make('jenis')->label('Jenis Program')
                        ->options(PendidikanPelatihan::JENIS)->required()->native(false)->default('pelatihan'),
                    TextInput::make('penyelenggara')->label('Penyelenggara')
                        ->default('LAMR Kabupaten Bengkalis')->maxLength(200),
                    TextInput::make('lokasi')->label('Lokasi')->maxLength(300),
                    TextInput::make('kuota')->label('Kuota Peserta')->numeric()
                        ->helperText('Kosongkan jika tidak dibatasi'),
                    TextInput::make('biaya')->label('Biaya (Rp)')->numeric()
                        ->helperText('Isi 0 atau kosongkan untuk Gratis'),
                    TextInput::make('link_pendaftaran')->label('Link Pendaftaran Eksternal')
                        ->url()->maxLength(500)->columnSpanFull()
                        ->placeholder('https://...'),
                ])->columns(2),

            Section::make('Jadwal')
                ->schema([
                    DatePicker::make('tanggal_mulai')->label('Tanggal Mulai'),
                    DatePicker::make('tanggal_selesai')->label('Tanggal Selesai'),
                ])->columns(2),

            Section::make('Konten')
                ->schema([
                    Textarea::make('deskripsi')->label('Deskripsi Singkat')->rows(3)->columnSpanFull(),
                    RichEditor::make('konten')->label('Informasi Lengkap')
                        ->disableToolbarButtons(['attachFiles'])->columnSpanFull(),
                ]),

            Section::make('Thumbnail & Pengaturan')
                ->schema([
                    FileUpload::make('thumbnail')->label('Thumbnail')
                        ->image()->disk('public')->directory('pendidikan')->maxSize(2048),
                    Toggle::make('is_aktif')->label('Aktif / Tampil')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')->label('')->square()->size(40)->disk('public'),
                TextColumn::make('judul')->label('Judul')->limit(50)->searchable(),
                TextColumn::make('jenis')->label('Jenis')->badge()
                    ->formatStateUsing(fn(string $state) => PendidikanPelatihan::JENIS[$state] ?? $s),
                TextColumn::make('tanggal_mulai')->label('Mulai')->date('d M Y')->sortable(),
                TextColumn::make('biaya_human')->label('Biaya'),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->filters([SelectFilter::make('jenis')->options(PendidikanPelatihan::JENIS)])
            ->defaultSort('tanggal_mulai', 'asc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPendidikanPelatihans::route('/'),
            'create' => Pages\CreatePendidikanPelatihan::route('/create'),
            'edit'   => Pages\EditPendidikanPelatihan::route('/{record}/edit'),
        ];
    }
}
