<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokohAdatResource\Pages;
use App\Models\TokohAdat;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TokohAdatResource extends Resource
{
    protected static ?string $model            = TokohAdat::class;
    protected static ?string $navigationIcon   = 'heroicon-o-user-group';
    protected static ?string $navigationLabel  = 'Tokoh Adat';
    protected static ?string $navigationGroup  = 'Kelembagaan';
    protected static ?int    $navigationSort   = 12;
    protected static ?string $modelLabel       = 'Tokoh Adat';
    protected static ?string $pluralModelLabel = 'Tokoh Adat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identitas')
                ->schema([
                    TextInput::make('nama')->label('Nama')->required()->maxLength(200),
                    TextInput::make('gelar_adat')->label('Gelar Adat')->maxLength(150),
                    TextInput::make('jabatan')->label('Jabatan')->maxLength(200),
                    TextInput::make('kecamatan')->label('Kecamatan')->maxLength(100),
                    TextInput::make('tahun_lahir')->label('Tahun Lahir')->numeric()->minValue(1900)->maxValue(2100),
                    TextInput::make('tahun_wafat')->label('Tahun Wafat')->numeric()->minValue(1900)->maxValue(2100)
                        ->helperText('Kosongkan jika masih hidup'),
                ])->columns(2),

            Section::make('Biografi')
                ->schema([
                    Textarea::make('ringkasan')->label('Ringkasan Singkat')
                        ->rows(3)->maxLength(500)->columnSpanFull(),
                    RichEditor::make('biografi')->label('Biografi Lengkap')
                        ->disableToolbarButtons(['attachFiles'])->columnSpanFull(),
                ]),

            Section::make('Foto & Pengaturan')
                ->schema([
                    FileUpload::make('foto_path')->label('Foto Tokoh')
                        ->image()->disk('public')->directory('tokoh-adat')
                        ->imageEditor()->maxSize(2048),
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
                ImageColumn::make('foto_path')->label('Foto')->circular()->size(40)->disk('public'),
                TextColumn::make('nama')->label('Nama')->searchable()->sortable(),
                TextColumn::make('gelar_adat')->label('Gelar')->limit(30),
                TextColumn::make('kecamatan')->label('Kecamatan')->searchable(),
                TextColumn::make('jabatan')->label('Jabatan')->limit(35),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('kecamatan')
                    ->options(fn() => TokohAdat::aktif()->whereNotNull('kecamatan')
                        ->distinct()->orderBy('kecamatan')->pluck('kecamatan', 'kecamatan')
                    )->label('Kecamatan'),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTokohAdats::route('/'),
            'create' => Pages\CreateTokohAdat::route('/create'),
            'edit'   => Pages\EditTokohAdat::route('/{record}/edit'),
        ];
    }
}
