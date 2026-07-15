<?php

namespace App\Filament\Pages;

use App\Models\BackgroundSlide;
use App\Models\BannerIklan;
use App\Models\SambutanBph;
use App\Services\HtmlSanitizer;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class BerandaPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Beranda';
    protected static ?string $navigationGroup = null;
    protected static ?int    $navigationSort   = 1;
    protected static ?string $title           = 'Kelola Konten Beranda';
    protected static string  $view            = 'filament.pages.beranda-page';

    // ── State form fields ─────────────────────────────────────────────────────

    // Sambutan BPH
    public ?string $sambutan_nama_ketua    = null;
    public ?string $sambutan_jabatan       = null;
    public ?string $sambutan_isi           = null;
    public $sambutan_foto          = [];
    public ?int    $sambutan_periode_mulai = null;
    public ?int    $sambutan_periode_selesai = null;

    // Background Slides — array untuk Repeater
    public array $slides  = [];

    // Banner Iklan — array untuk Repeater
    public array $banners = [];

    // ── Mount: load data awal ─────────────────────────────────────────────────

    public function mount(): void
    {
        // Load Sambutan BPH aktif
        $sambutan = SambutanBph::aktif()->first();
        $this->sambutan_bphForm->fill([
            'sambutan_nama_ketua'      => $sambutan?->nama_ketua,
            'sambutan_jabatan'         => $sambutan?->jabatan,
            'sambutan_isi'             => $sambutan?->isi_sambutan,
            'sambutan_foto'            => $sambutan?->foto,
            'sambutan_periode_mulai'   => $sambutan?->periode_mulai,
            'sambutan_periode_selesai' => $sambutan?->periode_selesai,
        ]);

        // Load Background Slides
        $this->slidesForm->fill([
            'slides' => BackgroundSlide::orderBy('urutan')
                ->get()
                ->map(fn ($s) => [
                    'id'         => $s->id,
                    'image_path' => $s->image_path,
                    'alt_text'   => $s->alt_text,
                    'urutan'     => $s->urutan,
                    'is_active'  => $s->is_active,
                ])->toArray()
        ]);

        // Load Banner Iklan
        $this->bannersForm->fill([
            'banners' => BannerIklan::orderBy('urutan')
                ->get()
                ->map(fn ($b) => [
                    'id'              => $b->id,
                    'image_path'      => $b->image_path,
                    'link_url'        => $b->link_url,
                    'alt_text'        => $b->alt_text,
                    'urutan'          => $b->urutan,
                    'is_active'       => $b->is_active,
                    'tanggal_mulai'   => $b->tanggal_mulai?->format('Y-m-d'),
                    'tanggal_selesai' => $b->tanggal_selesai?->format('Y-m-d'),
                ])->toArray()
        ]);
    }

    // ── Forms ─────────────────────────────────────────────────────────────────

    public function sambutan_bphForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sambutan Ketua BPH')
                    ->icon('heroicon-o-user-circle')
                    ->description('Pesan sambutan yang ditampilkan di halaman beranda.')
                    ->schema([
                        TextInput::make('sambutan_nama_ketua')
                            ->label('Nama Ketua')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('sambutan_jabatan')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('Ketua Umum LAM Bengkalis'),

                        TextInput::make('sambutan_periode_mulai')
                            ->label('Tahun Mulai Periode')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(2100),

                        TextInput::make('sambutan_periode_selesai')
                            ->label('Tahun Selesai Periode')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(2100),

                        FileUpload::make('sambutan_foto')
                            ->label('Foto Ketua')
                            ->image()
                            ->disk('public')
                            ->directory('sambutan')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1', '3:4'])
                            ->maxSize(1024)
                            ->columnSpanFull(),

                        RichEditor::make('sambutan_isi')
                            ->label('Isi Sambutan')
                            ->required()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'bulletList', 'orderedList',
                                'redo', 'undo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('');
    }

    public function slidesForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Background Carousel')
                    ->icon('heroicon-o-photo')
                    ->description('Gambar latar yang berputar di halaman beranda. Urutkan dengan drag.')
                    ->schema([
                        Repeater::make('slides')
                            ->label('')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Gambar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('slides')
                                    ->imageEditor()
                                    ->required()
                                    ->maxSize(3072)
                                    ->columnSpan(2),

                                TextInput::make('alt_text')
                                    ->label('Alt Text (Aksesibilitas)')
                                    ->required()
                                    ->maxLength(300)
                                    ->helperText('Deskripsi singkat gambar untuk pembaca layar & SEO.')
                                    ->columnSpan(2),

                                TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Toggle::make('is_active')
                                    ->label('Tampilkan')
                                    ->default(true)
                                    ->columnSpan(1),
                            ])
                            ->columns(6)
                            ->reorderable()
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state) => $state['alt_text'] ?? 'Slide baru')
                            ->addActionLabel('Tambah Slide'),
                    ]),
            ])
            ->statePath('');
    }

    public function bannersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Banner Iklan')
                    ->icon('heroicon-o-megaphone')
                    ->description('Banner gambar yang ditampilkan di area tertentu situs.')
                    ->schema([
                        Repeater::make('banners')
                            ->label('')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Gambar Banner')
                                    ->image()
                                    ->disk('public')
                                    ->directory('banners')
                                    ->required()
                                    ->maxSize(2048)
                                    ->columnSpan(2),

                                TextInput::make('link_url')
                                    ->label('URL Tujuan (opsional)')
                                    ->url()
                                    ->maxLength(500)
                                    ->placeholder('https://...')
                                    ->columnSpan(2),

                                TextInput::make('alt_text')
                                    ->label('Alt Text')
                                    ->maxLength(300)
                                    ->columnSpan(2),

                                TextInput::make('urutan')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),

                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->columnSpan(1),

                                DatePicker::make('tanggal_mulai')
                                    ->label('Mulai Tayang')
                                    ->nullable()
                                    ->displayFormat('d M Y')
                                    ->columnSpan(2),

                                DatePicker::make('tanggal_selesai')
                                    ->label('Selesai Tayang')
                                    ->nullable()
                                    ->displayFormat('d M Y')
                                    ->columnSpan(2),
                            ])
                            ->columns(8)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['alt_text'] ?? 'Banner baru')
                            ->addActionLabel('Tambah Banner'),
                    ]),
            ])
            ->statePath('');
    }

    protected function getForms(): array
    {
        return ['sambutan_bphForm', 'slidesForm', 'bannersForm'];
    }

    // ── Save Actions ──────────────────────────────────────────────────────────

    public function saveSambutan(): void
    {
        $data = $this->sambutan_bphForm->getState();

        // Deaktifkan semua sambutan lama
        SambutanBph::query()->update(['is_active' => false]);

        // Buat atau update sambutan
        SambutanBph::create([
            'nama_ketua'      => $data['sambutan_nama_ketua'],
            'jabatan'         => $data['sambutan_jabatan'],
            'isi_sambutan'    => HtmlSanitizer::clean($data['sambutan_isi'] ?? ''),
            'foto'            => $data['sambutan_foto'],
            'is_active'       => true,
            'periode_mulai'   => $data['sambutan_periode_mulai'],
            'periode_selesai' => $data['sambutan_periode_selesai'],
        ]);

        Notification::make()
            ->title('Sambutan BPH berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    public function saveSlides(): void
    {
        $data = $this->slidesForm->getState();
        // Delete all dan recreate (simpel untuk repeater)
        BackgroundSlide::query()->delete();

        foreach ($data['slides'] ?? [] as $idx => $slide) {
            BackgroundSlide::create([
                'image_path' => $slide['image_path'],
                'alt_text'   => $slide['alt_text'],
                'urutan'     => $slide['urutan'] ?? $idx,
                'is_active'  => $slide['is_active'] ?? true,
            ]);
        }

        Notification::make()
            ->title('Background slides berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    public function saveBanners(): void
    {
        $data = $this->bannersForm->getState();
        BannerIklan::query()->delete();

        foreach ($data['banners'] ?? [] as $idx => $banner) {
            BannerIklan::create([
                'image_path'      => $banner['image_path'],
                'link_url'        => $banner['link_url'] ?? null,
                'alt_text'        => $banner['alt_text'] ?? null,
                'urutan'          => $banner['urutan'] ?? $idx,
                'is_active'       => $banner['is_active'] ?? true,
                'tanggal_mulai'   => $banner['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $banner['tanggal_selesai'] ?? null,
            ]);
        }

        Notification::make()
            ->title('Banner iklan berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
