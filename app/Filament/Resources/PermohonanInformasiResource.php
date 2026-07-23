<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermohonanInformasiResource\Pages;
use App\Models\PermohonanInformasi;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PermohonanInformasiResource extends Resource
{
    protected static ?string $model            = PermohonanInformasi::class;
    protected static ?string $navigationIcon   = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel  = 'Permohonan Informasi';
    protected static ?string $navigationGroup  = 'Pelayanan';
    protected static ?int    $navigationSort   = 40;
    protected static ?string $modelLabel       = 'Permohonan';
    protected static ?string $pluralModelLabel = 'Permohonan Informasi';

    // Admin hanya bisa edit, tidak bisa create
    public static function canCreate(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Data Pemohon')
                ->schema([
                    TextInput::make('nomor_tiket')->label('No. Tiket')->disabled(),
                    TextInput::make('nama_pemohon')->label('Nama Pemohon')->disabled(),
                    TextInput::make('email')->label('Email')->disabled(),
                    TextInput::make('no_hp')->label('No. HP')->disabled(),
                    TextInput::make('instansi')->label('Instansi')->disabled(),
                ])->columns(2),

            Section::make('Permohonan')
                ->schema([
                    TextInput::make('jenis_informasi')->label('Jenis Informasi')->disabled()->columnSpanFull(),
                    Textarea::make('uraian_permohonan')->label('Uraian')->disabled()->rows(5)->columnSpanFull(),
                ]),

            Section::make('Penanganan Admin')
                ->schema([
                    Select::make('status')->label('Status')
                        ->options(PermohonanInformasi::STATUS)->required()->native(false),
                    Textarea::make('catatan_admin')->label('Catatan / Balasan')
                        ->rows(4)->columnSpanFull()
                        ->helperText('Catatan ini akan terlihat oleh pemohon saat mengecek status tiket.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_tiket')->label('Tiket')->searchable()->copyable()->fontFamily('mono'),
                TextColumn::make('nama_pemohon')->label('Pemohon')->searchable()->limit(30),
                TextColumn::make('email')->label('Email')->limit(30),
                TextColumn::make('jenis_informasi')->label('Jenis')->limit(35),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn($state) => match($state) {
                        'baru'     => 'info',
                        'diproses' => 'warning',
                        'selesai'  => 'success',
                        'ditolak'  => 'danger',
                        default    => 'gray',
                    }),
                TextColumn::make('created_at')->label('Dikirim')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([SelectFilter::make('status')->options(PermohonanInformasi::STATUS)])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make()->label('Proses')
                    ->successNotification(Notification::make()->title('Status permohonan diperbarui')->success()),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermohonanInformasis::route('/'),
            'edit'  => Pages\EditPermohonanInformasi::route('/{record}/edit'),
        ];
    }
}
