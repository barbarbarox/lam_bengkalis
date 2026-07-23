<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendaKegiatanResource\Pages;
use App\Models\AgendaKegiatan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
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

class AgendaKegiatanResource extends Resource
{
    protected static ?string $model            = AgendaKegiatan::class;
    protected static ?string $navigationIcon   = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel  = 'Agenda Kegiatan';
    protected static ?string $navigationGroup  = 'Kegiatan';
    protected static ?int    $navigationSort   = 30;
    protected static ?string $modelLabel       = 'Agenda';
    protected static ?string $pluralModelLabel = 'Agenda Kegiatan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Kegiatan')
                ->schema([
                    TextInput::make('judul')->label('Judul Kegiatan')->required()->maxLength(500)->columnSpanFull(),
                    Select::make('jenis')->label('Jenis Kegiatan')
                        ->options(AgendaKegiatan::JENIS)->required()->native(false),
                    Select::make('status')->label('Status')
                        ->options(AgendaKegiatan::STATUS)->required()->native(false)->default('akan_datang'),
                    TextInput::make('penyelenggara')->label('Penyelenggara')
                        ->default('LAMR Kabupaten Bengkalis')->maxLength(200),
                    TextInput::make('lokasi')->label('Lokasi')->maxLength(300),
                    TextInput::make('kuota')->label('Kuota Peserta')->numeric()
                        ->helperText('Kosongkan jika tidak dibatasi'),
                ])->columns(2),

            Section::make('Waktu Pelaksanaan')
                ->schema([
                    DatePicker::make('tanggal_mulai')->label('Tanggal Mulai')->required(),
                    DatePicker::make('tanggal_selesai')->label('Tanggal Selesai')->helperText('Kosongkan jika 1 hari'),
                    TimePicker::make('waktu_mulai')->label('Waktu Mulai')->seconds(false),
                    TimePicker::make('waktu_selesai')->label('Waktu Selesai')->seconds(false),
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
                        ->image()->disk('public')->directory('agenda')->maxSize(2048),
                    Toggle::make('is_aktif')->label('Aktif')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')->label('')->square()->size(40)->disk('public'),
                TextColumn::make('judul')->label('Judul')->limit(55)->searchable(),
                TextColumn::make('tanggal_mulai')->label('Tanggal Mulai')->date('d M Y')->sortable(),
                TextColumn::make('lokasi')->label('Lokasi')->limit(30),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn($state) => match($state) {
                        'akan_datang' => 'success',
                        'berlangsung' => 'warning',
                        'selesai'     => 'gray',
                        'dibatalkan'  => 'danger',
                        default       => 'gray',
                    }),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('status')->options(AgendaKegiatan::STATUS),
                SelectFilter::make('jenis')->options(AgendaKegiatan::JENIS),
            ])
            ->defaultSort('tanggal_mulai', 'asc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAgendaKegiatans::route('/'),
            'create' => Pages\CreateAgendaKegiatan::route('/create'),
            'edit'   => Pages\EditAgendaKegiatan::route('/{record}/edit'),
        ];
    }
}
