<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PengaturanSitusPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Situs';
    protected static ?string $navigationGroup = null;
    protected static ?int    $navigationSort   = 99;
    protected static ?string $title           = 'Pengaturan Situs';
    protected static string  $view            = 'filament.pages.pengaturan-situs-page';

    // ── State ─────────────────────────────────────────────────────────────────
    public ?string $nama_lembaga    = null;
    public ?string $singkatan       = null;
    public $logo_path       = null;
    public $favicon_path    = null;
    public ?string $meta_deskripsi  = null;
    public ?string $meta_keywords   = null;
    public ?int    $tahun_berdiri   = null;

    // Sosial media sebagai array (Repeater)
    public array $sosial_media = [];

    // URL eksternal khusus
    public ?string $url_museum = null;  // disimpan di meta_keywords JSON sementara

    // Hero backgrounds per halaman
    public $hero_profil_path = null;
    public $hero_berita_path = null;
    public $hero_kontak_path = null;
    public $hero_galeri_path = null;
    // Hero halaman modul baru
    public $hero_lam_kecamatan_path = null;
    public $hero_hukum_adat_path    = null;
    public $hero_tokoh_adat_path    = null;
    public $hero_gelar_adat_path    = null;
    public $hero_agenda_path        = null;
    public $hero_dokumen_path       = null;
    public $hero_permohonan_path    = null;
    public $hero_pendidikan_path    = null;
    public $hero_cari_path          = null;

    public function mount(): void
    {
        $setting = SiteSetting::instance();

        $this->identitasForm->fill([
            'nama_lembaga'  => $setting->nama_lembaga,
            'singkatan'     => $setting->singkatan,
            'logo_path'     => $setting->logo_path,
            'favicon_path'  => $setting->favicon_path,
            'tahun_berdiri' => $setting->tahun_berdiri,
        ]);

        $this->seoForm->fill([
            'meta_deskripsi' => $setting->meta_deskripsi,
        ]);

        // Sosial media disimpan sebagai JSON di meta_keywords
        $raw = $setting->meta_keywords;
        $decoded = json_decode($raw ?? '{}', true);

        if (is_array($decoded) && isset($decoded['sosmed'])) {
            $this->sosial_media = $decoded['sosmed'];
            $this->url_museum   = $decoded['url_museum'] ?? '';
        } else {
            // Backward compat: meta_keywords mungkin plain text
            $this->sosial_media = [
                ['platform' => 'facebook',  'url' => $setting->facebook_url  ?? ''],
                ['platform' => 'instagram', 'url' => $setting->instagram_url ?? ''],
                ['platform' => 'youtube',   'url' => $setting->youtube_url   ?? ''],
                ['platform' => 'twitter',   'url' => $setting->twitter_url   ?? ''],
            ];
            $this->url_museum = '';
        }

        // Hero backgrounds
        $this->heroForm->fill([
            'hero_profil_path'        => $setting->hero_profil_path,
            'hero_berita_path'        => $setting->hero_berita_path,
            'hero_kontak_path'        => $setting->hero_kontak_path,
            'hero_galeri_path'        => $setting->hero_galeri_path,
            'hero_lam_kecamatan_path' => $setting->hero_lam_kecamatan_path,
            'hero_hukum_adat_path'    => $setting->hero_hukum_adat_path,
            'hero_tokoh_adat_path'    => $setting->hero_tokoh_adat_path,
            'hero_gelar_adat_path'    => $setting->hero_gelar_adat_path,
            'hero_agenda_path'        => $setting->hero_agenda_path,
            'hero_dokumen_path'       => $setting->hero_dokumen_path,
            'hero_permohonan_path'    => $setting->hero_permohonan_path,
            'hero_pendidikan_path'    => $setting->hero_pendidikan_path,
            'hero_cari_path'          => $setting->hero_cari_path,
        ]);
    }

    // ── Forms ─────────────────────────────────────────────────────────────────

    public function identitasForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Identitas Lembaga')
                ->icon('heroicon-o-identification')
                ->schema([
                    TextInput::make('nama_lembaga')
                        ->label('Nama Resmi Lembaga')
                        ->required()
                        ->maxLength(200)
                        ->columnSpanFull(),

                    TextInput::make('singkatan')
                        ->label('Singkatan')
                        ->required()
                        ->maxLength(20)
                        ->placeholder('LAM Bengkalis'),

                    TextInput::make('tahun_berdiri')
                        ->label('Tahun Berdiri')
                        ->numeric()
                        ->minValue(1900)
                        ->maxValue(2100),

                    FileUpload::make('logo_path')
                        ->label('Logo Lembaga')
                        ->image()
                        ->disk('public')
                        ->directory('logo')
                        ->imageEditor()
                        ->maxSize(1024)
                        ->helperText('PNG/SVG transparan direkomendasikan. Maks. 1 MB.'),

                    FileUpload::make('favicon_path')
                        ->label('Favicon')
                        ->image()
                        ->disk('public')
                        ->directory('logo')
                        ->maxSize(512)
                        ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml'])
                        ->helperText('File .ico atau .png 32x32px. Maks. 512 KB.'),
                ])
                ->columns(2),
        ])->statePath('');
    }

    public function seoForm(Form $form): Form
    {
        return $form->schema([
            Section::make('SEO Global')
                ->icon('heroicon-o-magnifying-glass')
                ->description('Meta description dan keywords default untuk semua halaman.')
                ->schema([
                    Textarea::make('meta_deskripsi')
                        ->label('Meta Deskripsi')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Deskripsi singkat situs (maks. 160 karakter direkomendasikan).')
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    public function sosialMediaForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Tautan Media Sosial')
                ->icon('heroicon-o-share')
                ->schema([
                    Repeater::make('sosial_media')
                        ->label('')
                        ->schema([
                            Select::make('platform')
                                ->label('Platform')
                                ->required()
                                ->options([
                                    'facebook'  => 'Facebook',
                                    'instagram' => 'Instagram',
                                    'youtube'   => 'YouTube',
                                    'twitter'   => 'Twitter / X',
                                    'tiktok'    => 'TikTok',
                                    'lainnya'   => 'Lainnya',
                                ])
                                ->native(false),

                            TextInput::make('url')
                                ->label('URL Profil')
                                ->url()
                                ->required()
                                ->maxLength(500)
                                ->placeholder('https://...'),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->addActionLabel('Tambah Akun Media Sosial')
                        ->columnSpanFull(),
                ]),

            Section::make('URL Eksternal')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->schema([
                    TextInput::make('url_museum')
                        ->label('URL Jejak Layar (Museum Digital)')
                        ->url()
                        ->maxLength(500)
                        ->placeholder('https://museum.lam-bengkalis.go.id')
                        ->helperText('URL ke platform museum digital Jejak Layar LAM Bengkalis.')
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    public function heroForm(Form $form): Form
    {
        $uploadField = fn(string $field, string $label) => FileUpload::make($field)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('hero')
            ->imagePreviewHeight('100')
            ->maxSize(5120)
            ->helperText('JPG/PNG/WebP. Min 1600×500px. Maks 5MB.');

        return $form->schema([
            Section::make('Hero — Halaman Utama')
                ->icon('heroicon-o-photo')
                ->description('Background header untuk halaman inti website.')
                ->schema([
                    $uploadField('hero_profil_path', 'Profil / Tentang LAMR'),
                    $uploadField('hero_berita_path', 'Berita & Pengumuman'),
                    $uploadField('hero_kontak_path', 'Halaman Kontak'),
                    $uploadField('hero_galeri_path', 'Galeri Foto'),
                ])
                ->columns(2),

            Section::make('Hero — Halaman Adat & Kelembagaan')
                ->icon('heroicon-o-building-library')
                ->description('Background header khusus modul adat. Jika kosong, akan memakai fallback hero halaman Profil.')
                ->schema([
                    $uploadField('hero_lam_kecamatan_path', 'LAM Kecamatan'),
                    $uploadField('hero_hukum_adat_path',    'Hukum Adat'),
                    $uploadField('hero_tokoh_adat_path',    'Tokoh Adat'),
                    $uploadField('hero_gelar_adat_path',    'Gelar & Kehormatan Adat'),
                ])
                ->columns(2),

            Section::make('Hero — Halaman Informasi & Layanan')
                ->icon('heroicon-o-clipboard-document-list')
                ->description('Background header untuk modul informasi publik. Jika kosong, akan memakai fallback hero halaman Profil/Berita.')
                ->schema([
                    $uploadField('hero_agenda_path',     'Agenda Kegiatan'),
                    $uploadField('hero_dokumen_path',    'Dokumen & Peraturan'),
                    $uploadField('hero_permohonan_path', 'Permohonan Informasi'),
                    $uploadField('hero_pendidikan_path', 'Pendidikan & Pelatihan'),
                    $uploadField('hero_cari_path',       'Halaman Pencarian'),
                ])
                ->columns(2),
        ])->statePath('');
    }

    protected function getForms(): array
    {
        return ['identitasForm', 'seoForm', 'sosialMediaForm', 'heroForm'];
    }


    // ── Save Actions ──────────────────────────────────────────────────────────

    public function saveIdentitas(): void
    {
        $data = $this->identitasForm->getState();
        SiteSetting::updateSettings([
            'nama_lembaga'  => $data['nama_lembaga'],
            'singkatan'     => $data['singkatan'],
            'logo_path'     => $data['logo_path'],
            'favicon_path'  => $data['favicon_path'],
            'tahun_berdiri' => $data['tahun_berdiri'],
        ]);

        Notification::make()
            ->title('Identitas lembaga berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    public function saveSeo(): void
    {
        $data = $this->seoForm->getState();
        SiteSetting::updateSettings([
            'meta_deskripsi' => $data['meta_deskripsi'],
        ]);

        Notification::make()
            ->title('Pengaturan SEO berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    public function saveSosialMedia(): void
    {
        $data = $this->sosialMediaForm->getState();
        $sosmed = $data['sosial_media'] ?? [];
        $url_museum = $data['url_museum'] ?? '';

        // Simpan sosmed + url_museum sebagai JSON di meta_keywords
        $json = json_encode([
            'sosmed'     => $sosmed,
            'url_museum' => $url_museum,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Juga update kolom individual sosial media untuk backward compat
        $urls = collect($sosmed)->keyBy('platform');

        SiteSetting::updateSettings([
            'meta_keywords' => $json,
            'facebook_url'  => $urls->get('facebook.url') ?? ($urls->get('facebook')['url'] ?? null),
            'instagram_url' => $urls->get('instagram.url') ?? ($urls->get('instagram')['url'] ?? null),
            'youtube_url'   => $urls->get('youtube.url') ?? ($urls->get('youtube')['url'] ?? null),
            'twitter_url'   => $urls->get('twitter.url') ?? ($urls->get('twitter')['url'] ?? null),
        ]);

        Notification::make()
            ->title('Media sosial berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    public function saveHero(): void
    {
        $data = $this->heroForm->getState();
        SiteSetting::updateSettings([
            'hero_profil_path'        => $data['hero_profil_path'],
            'hero_berita_path'        => $data['hero_berita_path'],
            'hero_kontak_path'        => $data['hero_kontak_path'],
            'hero_galeri_path'        => $data['hero_galeri_path'],
            'hero_lam_kecamatan_path' => $data['hero_lam_kecamatan_path'] ?? null,
            'hero_hukum_adat_path'    => $data['hero_hukum_adat_path']    ?? null,
            'hero_tokoh_adat_path'    => $data['hero_tokoh_adat_path']    ?? null,
            'hero_gelar_adat_path'    => $data['hero_gelar_adat_path']    ?? null,
            'hero_agenda_path'        => $data['hero_agenda_path']        ?? null,
            'hero_dokumen_path'       => $data['hero_dokumen_path']       ?? null,
            'hero_permohonan_path'    => $data['hero_permohonan_path']    ?? null,
            'hero_pendidikan_path'    => $data['hero_pendidikan_path']    ?? null,
            'hero_cari_path'          => $data['hero_cari_path']          ?? null,
        ]);

        Notification::make()
            ->title('Background hero berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array { return []; }
}
