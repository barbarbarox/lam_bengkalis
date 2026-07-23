<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GelarAdatResource\Pages;
use App\Models\GelarAdat;
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

class GelarAdatResource extends Resource
{
    protected static ?string $model            = GelarAdat::class;
    protected static ?string $navigationIcon   = 'heroicon-o-star';
    protected static ?string $navigationLabel  = 'Gelar & Kehormatan Adat';
    protected static ?string $navigationGroup  = 'Kelembagaan';
    protected static ?int    $navigationSort   = 14;
    protected static ?string $modelLabel       = 'Gelar Adat';
    protected static ?string $pluralModelLabel = 'Gelar Adat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identitas Gelar')
                ->schema([
                    TextInput::make('nama_gelar')->label('Nama Gelar')->required()->maxLength(200),
                    Select::make('jenis')->label('Jenis Gelar')
                        ->options(GelarAdat::JENIS)->required()->native(false),
                    TextInput::make('tingkatan')->label('Tingkatan')->maxLength(100)
                        ->placeholder('maharaja / dato / seri / dll.'),
                ])->columns(2),

            Section::make('Keterangan')
                ->schema([
                    Textarea::make('deskripsi')->label('Deskripsi Singkat')
                        ->rows(3)->maxLength(1000)->columnSpanFull(),
                    RichEditor::make('makna')->label('Makna & Filosofi Gelar')
                        ->disableToolbarButtons(['attachFiles'])->columnSpanFull(),
                    RichEditor::make('syarat_pemberian')->label('Syarat & Tata Cara Pemberian Gelar')
                        ->disableToolbarButtons(['attachFiles'])->columnSpanFull(),
                    Textarea::make('penerima_terkini')->label('Penerima Terkini')
                        ->rows(2)->maxLength(500)->columnSpanFull(),
                ]),

            Section::make('Pengaturan')
                ->schema([
                    TextInput::make('urutan')->label('Urutan')->numeric()->default(0),
                    Toggle::make('is_aktif')->label('Aktif')->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->label('#')->sortable()->width(50),
                TextColumn::make('nama_gelar')->label('Nama Gelar')->searchable()->sortable(),
                TextColumn::make('jenis')->label('Jenis')->badge()
                    ->formatStateUsing(fn(string $state) => GelarAdat::JENIS[$state] ?? $state),
                TextColumn::make('tingkatan')->label('Tingkatan')->limit(30),
                ToggleColumn::make('is_aktif')->label('Aktif'),
            ])
            ->filters([SelectFilter::make('jenis')->options(GelarAdat::JENIS)])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGelarAdats::route('/'),
            'create' => Pages\CreateGelarAdat::route('/create'),
            'edit'   => Pages\EditGelarAdat::route('/{record}/edit'),
        ];
    }
}
