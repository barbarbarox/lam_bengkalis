<?php

namespace App\Filament\Pages;

use App\Models\ProfilKonten;
use App\Services\HtmlSanitizer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ProfilPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Profil Lembaga';
    protected static ?string $navigationGroup = 'Profil Lembaga';
    protected static ?int    $navigationSort   = 1;
    protected static ?string $title           = 'Konten Profil Lembaga';
    protected static string  $view            = 'filament.pages.profil-page';

    // State fields per konten
    public array   $sejarah_konten     = [];
    public ?string $visi_konten        = null;
    public array   $misi_poin          = [];
    public ?string $tugas_fungsi_konten = null;
    public ?string $dasar_hukum_konten  = null;

    private array $slugMap = [
        'sejarah'      => 'sejarah',
        'visi'         => 'visi-misi',
        'tugas_fungsi' => 'tugas-fungsi',
        'dasar_hukum'  => 'dasar-hukum',
    ];

    public function mount(): void
    {
        $sejarahRaw = $this->loadKonten('sejarah-lam');
        $sejarahDecoded = json_decode($sejarahRaw ?? '[]', true);
        $this->sejarahForm->fill([
            'sejarah_konten' => is_array($sejarahDecoded) ? $sejarahDecoded : [],
        ]);

        $misiRaw = $this->loadKonten('misi-lam');
        $decoded = json_decode($misiRaw ?? '[]', true);
        $misiPoin = is_array($decoded)
            ? array_map(fn ($p) => ['poin' => $p], $decoded)
            : [];

        $this->visiMisiForm->fill([
            'visi_konten' => $this->loadKonten('visi-lam'),
            'misi_poin'   => $misiPoin,
        ]);

        $this->tugasFungsiForm->fill([
            'tugas_fungsi_konten' => $this->loadKonten('tugas-fungsi'),
        ]);

        $this->dasarHukumForm->fill([
            'dasar_hukum_konten' => $this->loadKonten('dasar-hukum'),
        ]);
    }

    private function loadKonten(string $slug): ?string
    {
        return ProfilKonten::where('slug', $slug)->value('konten');
    }

    private function saveKonten(string $slug, string $judul, string $konten): void
    {
        ProfilKonten::updateOrCreate(
            ['slug' => $slug],
            ['judul' => $judul, 'konten' => $konten, 'is_active' => true]
        );
    }

    // ── Forms ─────────────────────────────────────────────────────────────────

    public function sejarahForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Sejarah LAM Bengkalis (Timeline)')
                ->icon('heroicon-o-clock')
                ->schema([
                    Repeater::make('sejarah_konten')
                        ->label('Item Sejarah')
                        ->schema([
                            TextInput::make('tahun')
                                ->label('Tahun / Judul')
                                ->required()
                                ->maxLength(255),
                            FileUpload::make('gambar')
                                ->label('Gambar (Opsional)')
                                ->image()
                                ->directory('sejarah'),
                            RichEditor::make('deskripsi')
                                ->label('Deskripsi')
                                ->required()
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline', 'strike',
                                    'bulletList', 'orderedList',
                                    'link', 'redo', 'undo',
                                ])
                        ])
                        ->reorderable()
                        ->cloneable()
                        ->addActionLabel('Tambah Item Sejarah')
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    public function visiMisiForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Visi dan Misi')
                ->icon('heroicon-o-eye')
                ->schema([
                    Textarea::make('visi_konten')
                        ->label('Visi')
                        ->required()
                        ->rows(3)
                        ->maxLength(1000)
                        ->helperText('Pernyataan visi tunggal LAM Bengkalis.')
                        ->columnSpanFull(),

                    Repeater::make('misi_poin')
                        ->label('Poin-Poin Misi')
                        ->schema([
                            TextInput::make('poin')
                                ->label('Poin Misi')
                                ->required()
                                ->maxLength(500),
                        ])
                        ->reorderable()
                        ->cloneable()
                        ->addActionLabel('Tambah Poin Misi')
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    public function tugasFungsiForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Tugas dan Fungsi')
                ->icon('heroicon-o-briefcase')
                ->schema([
                    TinyEditor::make('tugas_fungsi_konten')
                        ->label('Isi Tugas dan Fungsi')
                        ->required()
                        ->profile('default')
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    public function dasarHukumForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Dasar Hukum')
                ->icon('heroicon-o-scale')
                ->schema([
                    RichEditor::make('dasar_hukum_konten')
                        ->label('Dasar Hukum LAM Bengkalis')
                        ->toolbarButtons([
                            'bold', 'italic', 'h2', 'h3',
                            'bulletList', 'orderedList', 'link', 'redo', 'undo',
                        ])
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    protected function getForms(): array
    {
        return ['sejarahForm', 'visiMisiForm', 'tugasFungsiForm', 'dasarHukumForm'];
    }

    // ── Save Actions ──────────────────────────────────────────────────────────

    public function saveSejarah(): void
    {
        $data = $this->sejarahForm->getState();
        $sejarahList = $data['sejarah_konten'] ?? [];
        $this->saveKonten(
            'sejarah-lam',
            'Sejarah LAM Bengkalis',
            json_encode(array_values($sejarahList), JSON_UNESCAPED_UNICODE)
        );
        $this->notifSuccess('Sejarah berhasil disimpan');
    }

    public function saveVisiMisi(): void
    {
        $data = $this->visiMisiForm->getState();
        
        $this->saveKonten('visi-lam', 'Visi', $data['visi_konten'] ?? '');

        $misiPoin = $data['misi_poin'] ?? [];
        $poinList = array_values(array_map(fn ($p) => $p['poin'], $misiPoin));
        $this->saveKonten('misi-lam', 'Misi', json_encode($poinList, JSON_UNESCAPED_UNICODE));

        $this->notifSuccess('Visi dan Misi berhasil disimpan');
    }

    public function saveTugasFungsi(): void
    {
        $data = $this->tugasFungsiForm->getState();
        $this->saveKonten(
            'tugas-fungsi',
            'Tugas dan Fungsi',
            HtmlSanitizer::clean($data['tugas_fungsi_konten'] ?? '')
        );
        $this->notifSuccess('Tugas dan Fungsi berhasil disimpan');
    }

    public function saveDasarHukum(): void
    {
        $data = $this->dasarHukumForm->getState();
        $this->saveKonten(
            'dasar-hukum',
            'Dasar Hukum',
            HtmlSanitizer::clean($data['dasar_hukum_konten'] ?? '')
        );
        $this->notifSuccess('Dasar Hukum berhasil disimpan');
    }

    private function notifSuccess(string $title): void
    {
        Notification::make()
            ->title($title)
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array { return []; }
}
