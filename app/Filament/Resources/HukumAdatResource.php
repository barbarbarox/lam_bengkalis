<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HukumAdatResource\Pages;
use App\Models\HukumAdat;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HukumAdatResource extends Resource
{
    protected static ?string $model            = HukumAdat::class;
    protected static ?string $navigationIcon   = 'heroicon-o-scale';
    protected static ?string $navigationLabel  = 'Hukum Adat';
    protected static ?string $navigationGroup  = 'Hukum & Regulasi';
    protected static ?int    $navigationSort   = 20;
    protected static ?string $modelLabel       = 'Hukum Adat';
    protected static ?string $pluralModelLabel = 'Hukum Adat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Dokumen')
                ->schema([
                    TextInput::make('judul')->label('Judul Hukum/Peraturan Adat')
                        ->required()->maxLength(500)->columnSpanFull(),
                    Select::make('jenis')->label('Jenis Dokumen')
                        ->options(HukumAdat::JENIS)->required()->native(false),
                    TextInput::make('nomor_dokumen')->label('Nomor Dokumen')->maxLength(100),
                    TextInput::make('tahun')->label('Tahun')->numeric()->minValue(1900)->maxValue(2100),
                ])->columns(2),

            Section::make('Konten')
                ->schema([
                    Textarea::make('ringkasan')->label('Ringkasan Singkat')
                        ->rows(3)->maxLength(1000)->columnSpanFull(),
                    RichEditor::make('konten')->label('Isi Lengkap (HTML)')
                        ->disableToolbarButtons(['attachFiles'])->columnSpanFull(),
                ]),

            Section::make('File & Pengaturan')
                ->schema([
                    FileUpload::make('file_path')->label('File PDF/Dokumen')
                        ->disk('public')->directory('hukum-adat')
                        ->acceptedFileTypes(['application/pdf'])->maxSize(10240)
                        ->helperText('File PDF, maks 10 MB.'),
                    FileUpload::make('thumbnail')->label('Thumbnail')
                        ->image()->disk('public')->directory('hukum-adat')->maxSize(2048),
                    Toggle::make('is_aktif')->label('Aktif / Tampil')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis')->label('Jenis')->badge()
                    ->formatStateUsing(fn($state) => HukumAdat::JENIS[$state] ?? $state),
                TextColumn::make('judul')->label('Judul')->limit(60)->searchable(),
                TextColumn::make('nomor_dokumen')->label('Nomor')->limit(25),
                TextColumn::make('tahun')->label('Tahun')->sortable(),
                TextColumn::make('jumlah_unduh')->label('Unduhan')->sortable(),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->filters([SelectFilter::make('jenis')->options(HukumAdat::JENIS)->label('Jenis')])
            ->defaultSort('tahun', 'desc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHukumAdats::route('/'),
            'create' => Pages\CreateHukumAdat::route('/create'),
            'edit'   => Pages\EditHukumAdat::route('/{record}/edit'),
        ];
    }
}
