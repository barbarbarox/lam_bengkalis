<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaResource\Pages;
use App\Models\Berita;
use App\Models\BeritaKategori;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon       = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel      = 'Daftar Berita';
    protected static ?string $navigationGroup      = 'Berita';
    protected static ?int    $navigationSort        = 1;
    protected static ?string $modelLabel           = 'Berita';
    protected static ?string $pluralModelLabel     = 'Berita';
    protected static ?string $recordTitleAttribute = 'judul';

    // ── Form ─────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── Kolom kiri (2/3) ─────────────────────────────────────────────
            Section::make('Konten Berita')
                ->schema([

                    TextInput::make('judul')
                        ->label('Judul Berita')
                        ->required()
                        ->maxLength(500)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug($state ?? ''));
                            }
                        })
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('Slug URL')
                        ->required()
                        ->maxLength(250)
                        ->unique(table: 'berita', column: 'slug', ignoreRecord: true)
                        ->rules(['regex:/^[a-z0-9\-]+$/'])
                        ->helperText('Diisi otomatis dari judul. Ubah hanya jika diperlukan.')
                        ->columnSpanFull(),

                    // ── Pilihan Cara Input Konten ────────────────────────────
                    \Filament\Forms\Components\Radio::make('input_mode')
                        ->label('Cara Input Konten')
                        ->options([
                            'manual' => 'Buat Manual (Ketik Sendiri)',
                            'word'   => 'Import dari File Word (.docx)',
                            'pdf'    => 'Import dari File PDF (.pdf)',
                        ])
                        ->default('manual')
                        ->inline()
                        ->live()
                        ->hidden(fn (string $operation) => $operation === 'edit')
                        ->columnSpanFull(),

                    // ── Mode: Buat Manual ────────────────────────────────────
                    \AmidEsfahani\FilamentTinyEditor\TinyEditor::make('konten')
                        ->label('Isi Berita')
                        ->required(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'edit' || $get('input_mode') === 'manual')
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsVisibility('public')
                        ->fileAttachmentsDirectory('berita/lampiran')
                        ->profile('default')
                        ->columnSpanFull()
                        ->extraInputAttributes([
                            'style' => 'min-height: 400px;',
                        ])
                        ->hidden(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'create' && in_array($get('input_mode'), ['word', 'pdf'])),

                    // ── Mode: Import Word ────────────────────────────────────
                    FileUpload::make('word_file')
                        ->label('Upload File Word (.docx)')
                        ->disk('public')
                        ->directory('temp/word-import')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/msword',
                        ])
                        ->maxSize(10240) // 10 MB
                        ->storeFileNamesIn('word_file_original_name')
                        ->helperText('Upload file .docx (maks. 10 MB).')
                        ->required(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'create' && $get('input_mode') === 'word')
                        ->hidden(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'edit' || $get('input_mode') !== 'word')
                        ->columnSpanFull(),

                    // ── Mode: Import PDF ─────────────────────────────────────
                    FileUpload::make('pdf_file')
                        ->label('Upload File PDF (.pdf)')
                        ->disk('public')
                        ->directory('temp/pdf-import')
                        ->visibility('private')
                        ->acceptedFileTypes([
                            'application/pdf',
                        ])
                        ->maxSize(10240) // 10 MB
                        ->storeFileNamesIn('pdf_file_original_name')
                        ->helperText('Upload file .pdf (maks. 10 MB).')
                        ->required(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'create' && $get('input_mode') === 'pdf')
                        ->hidden(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'edit' || $get('input_mode') !== 'pdf')
                        ->columnSpanFull(),

                    // ── Peringatan (Warning) untuk Word & PDF ────────────────
                    Placeholder::make('import_warning')
                        ->label('')
                        ->content(fn (\Filament\Forms\Get $get) => new HtmlString(
                            '<div class="rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm text-warning-900 dark:border-warning-800 dark:bg-warning-950 dark:text-warning-200">' .
                            '<div class="flex items-start gap-3">' .
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>' .
                            '<div>' .
                            '<p class="font-bold mb-1">⚠️ PERINGATAN PENTING SETELAH IMPORT!</p>' .
                            '<p class="mb-2">Harap <strong>segera cek dan edit kembali</strong> artikel ini setelah tersimpan. Tergantung pada format file dokumen asli:</p>' .
                            '<ul class="list-disc list-inside space-y-1">' .
                            ($get('input_mode') === 'pdf' ? 
                                '<li>Format PDF didesain untuk percetakan, sehingga hanya Teks yang akan di-import (Gambar & Tabel <strong>tidak akan</strong> terbaca).</li>' .
                                '<li>Struktur paragraf mungkin akan terpotong secara tidak rapi.</li>' 
                                : 
                                '<li>Beberapa bagian (seperti tabel kompleks atau posisi gambar) mungkin ter-import kurang rapi.</li>'
                            ) .
                            '<li>Selalu lakukan penyesuaian akhir menggunakan editor manual (Tab Buat Manual).</li>' .
                            '</ul>' .
                            '</div>' .
                            '</div>' .
                            '</div>'
                        ))
                        ->hidden(fn (\Filament\Forms\Get $get, string $operation) => $operation === 'edit' || !in_array($get('input_mode'), ['word', 'pdf']))
                        ->columnSpanFull(),
                ])
                ->columnSpan(2),

            // ── Kolom kanan (1/3) ─────────────────────────────────────────────
            Section::make('Metadata & Publikasi')
                ->schema([
                    Select::make('berita_kategori_id')
                        ->label('Kategori')
                        ->required()
                        ->options(BeritaKategori::pluck('nama', 'id'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('nama')
                                ->label('Nama Kategori')
                                ->required()
                                ->maxLength(100)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) =>
                                    $set('slug', Str::slug($state ?? ''))
                                ),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(100)
                                ->unique(table: \App\Models\BeritaKategori::class, column: 'slug'),
                        ])
                        ->createOptionUsing(function (array $data) {
                            return BeritaKategori::create($data)->id;
                        }),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            Berita::STATUS_DRAFT     => 'Draft',
                            Berita::STATUS_PUBLISHED => 'Terbit',
                        ])
                        ->default(Berita::STATUS_DRAFT)
                        ->required()
                        ->native(false),

                    DateTimePicker::make('tanggal_publish')
                        ->label('Tanggal Terbit')
                        ->helperText('Kosongkan untuk terbit segera saat status diubah ke Terbit.')
                        ->nullable()
                        ->seconds(false)
                        ->displayFormat('d M Y, H:i'),

                    FileUpload::make('thumbnail')
                        ->label('Thumbnail')
                        ->image()
                        ->disk('public')
                        ->directory('berita/thumbnail')
                        ->imageEditor()
                        ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                        ->maxSize(2048)
                        ->helperText('Maks. 2 MB. Rasio 16:9 direkomendasikan.'),
                ])
                ->columnSpan(1),

        ])->columns(3);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->disk('public')
                    ->width(64)
                    ->height(40)
                    ->defaultImageUrl(asset('images/no-image.svg')),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(55)
                    ->weight('semibold')
                    ->description(fn (Berita $r) => $r->kategori?->nama),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Berita::STATUS_PUBLISHED => 'success',
                        Berita::STATUS_DRAFT     => 'gray',
                        default                  => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        Berita::STATUS_PUBLISHED => 'Terbit',
                        Berita::STATUS_DRAFT     => 'Draft',
                        default                  => $state,
                    })
                    ->sortable(),

                TextColumn::make('tanggal_publish')
                    ->label('Tanggal Terbit')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('Belum terjadwal'),

                TextColumn::make('penulis.name')
                    ->label('Penulis')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('jumlah_dilihat')
                    ->label('Dilihat')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Berita::STATUS_DRAFT     => 'Draft',
                        Berita::STATUS_PUBLISHED => 'Terbit',
                    ])
                    ->native(false),

                SelectFilter::make('berita_kategori_id')
                    ->label('Kategori')
                    ->options(BeritaKategori::pluck('nama', 'id'))
                    ->searchable()
                    ->preload(),
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
            'index'  => Pages\ListBerita::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit'   => Pages\EditBerita::route('/{record}/edit'),
        ];
    }

    // ── EloquentQuery ─────────────────────────────────────────────────────────

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['kategori', 'penulis']);
    }
}
