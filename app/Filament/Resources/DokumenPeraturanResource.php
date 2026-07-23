<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenPeraturanResource\Pages;
use App\Models\DokumenPeraturan;
use Filament\Forms\Components\FileUpload;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DokumenPeraturanResource extends Resource
{
    protected static ?string $model            = DokumenPeraturan::class;
    protected static ?string $navigationIcon   = 'heroicon-o-document-text';
    protected static ?string $navigationLabel  = 'Dokumen & Peraturan';
    protected static ?string $navigationGroup  = 'Hukum & Regulasi';
    protected static ?int    $navigationSort   = 22;
    protected static ?string $modelLabel       = 'Dokumen';
    protected static ?string $pluralModelLabel = 'Dokumen & Peraturan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Dokumen')
                ->schema([
                    TextInput::make('judul')->label('Judul Dokumen')->required()->maxLength(500)->columnSpanFull(),
                    Select::make('jenis')->label('Jenis Dokumen')
                        ->options(DokumenPeraturan::JENIS)->required()->native(false),
                    TextInput::make('nomor')->label('Nomor Dokumen')->maxLength(100),
                    TextInput::make('tahun')->label('Tahun')->numeric()->minValue(1945)->maxValue(2100),
                    Textarea::make('deskripsi')->label('Deskripsi Singkat')
                        ->rows(3)->columnSpanFull(),
                ])->columns(2),

            Section::make('File')
                ->schema([
                    FileUpload::make('file_path')->label('Upload File (PDF/Word)')
                        ->disk('public')->directory('dokumen')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ])
                        ->maxSize(20480) // 20 MB
                        ->helperText('PDF atau Word Document. Maks 20 MB.')
                        ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                            if ($state) {
                                $path = is_array($state) ? ($state[0] ?? null) : $state;
                                if ($path) {
                                    $fullPath = storage_path('app/public/' . $path);
                                    if (file_exists($fullPath)) {
                                        $set('ukuran_file', filesize($fullPath));
                                        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                                        $set('mime_type', $ext === 'pdf' ? 'application/pdf' : 'application/msword');
                                    }
                                }
                            }
                        })->live(),
                    TextInput::make('mime_type')->label('Tipe MIME')->hidden(),
                    TextInput::make('ukuran_file')->label('Ukuran (bytes)')->numeric()->hidden(),
                    Toggle::make('is_aktif')->label('Aktif / Tampil')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis')->label('Jenis')->badge()
                    ->formatStateUsing(fn(string $state) => DokumenPeraturan::JENIS[$state] ?? $s),
                TextColumn::make('judul')->label('Judul')->limit(55)->searchable(),
                TextColumn::make('nomor')->label('Nomor')->limit(25)->searchable(),
                TextColumn::make('tahun')->label('Tahun')->sortable(),
                TextColumn::make('jumlah_unduh')->label('Unduhan')->sortable(),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->filters([SelectFilter::make('jenis')->options(DokumenPeraturan::JENIS)])
            ->defaultSort('tahun', 'desc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDokumenPeraturans::route('/'),
            'create' => Pages\CreateDokumenPeraturan::route('/create'),
            'edit'   => Pages\EditDokumenPeraturan::route('/{record}/edit'),
        ];
    }
}
