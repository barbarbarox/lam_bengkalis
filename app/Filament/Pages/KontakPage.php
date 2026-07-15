<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class KontakPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Info Kontak';
    protected static ?string $navigationGroup = 'Kontak';
    protected static ?int    $navigationSort   = 1;
    protected static ?string $title           = 'Informasi Kontak';
    protected static string  $view            = 'filament.pages.kontak-page';

    // State: info kontak statis
    public ?string $alamat       = null;
    public ?string $email_kontak = null;
    public ?string $no_telp      = null;
    public ?string $embed_peta   = null;   // disimpan di site_settings via meta field

    public function mount(): void
    {
        $setting = SiteSetting::instance();

        $this->alamat       = $setting->alamat;
        $this->email_kontak = $setting->email_kontak;
        $this->no_telp      = $setting->no_telp;
        $this->embed_peta   = $setting->embed_peta;
    }

    public function kontakForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Kontak Lembaga')
                ->icon('heroicon-o-map-pin')
                ->description('Data ini ditampilkan di halaman Kontak situs publik.')
                ->schema([
                    Textarea::make('alamat')
                        ->label('Alamat Kantor')
                        ->rows(3)
                        ->maxLength(500)
                        ->placeholder('Jl. ..., Bengkalis, Riau')
                        ->columnSpanFull(),

                    TextInput::make('email_kontak')
                        ->label('Email Resmi')
                        ->email()
                        ->maxLength(200)
                        ->placeholder('info@lam-bengkalis.go.id'),

                    TextInput::make('no_telp')
                        ->label('No. Telepon / WhatsApp')
                        ->tel()
                        ->maxLength(30)
                        ->placeholder('+6276...')
                        ->helperText('Sertakan kode negara untuk WhatsApp, misal: +62766...'),
                ])
                ->columns(2),

            Section::make('Embed Peta')
                ->icon('heroicon-o-globe-alt')
                ->description('Tempel kode iframe dari Google Maps. Hanya kode iframe yang diizinkan.')
                ->schema([
                    Textarea::make('embed_peta')
                        ->label('Kode Iframe Google Maps')
                        ->rows(4)
                        ->maxLength(2000)
                        ->placeholder('<iframe src="https://www.google.com/maps/embed?..." ...></iframe>')
                        ->helperText(
                            'Buka Google Maps → bagikan → embed map → salin kode iframe. '
                            . 'Konten akan divalidasi agar hanya iframe Google Maps yang diterima.'
                        )
                        ->columnSpanFull(),
                ]),
        ])->statePath('');
    }

    protected function getForms(): array
    {
        return ['kontakForm'];
    }

    public function saveKontak(): void
    {
        // Validasi manual: embed_peta harus kosong atau berupa iframe Google Maps
        if (! empty($this->embed_peta)) {
            $allowed = str_contains($this->embed_peta, 'google.com/maps/embed')
                       && str_starts_with(trim($this->embed_peta), '<iframe');

            if (! $allowed) {
                Notification::make()
                    ->title('Kode embed peta tidak valid')
                    ->body('Hanya kode iframe dari Google Maps yang diizinkan.')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->danger()
                    ->send();
                return;
            }
        }

        // Simpan info kontak ke site_settings
        SiteSetting::updateSettings([
            'alamat'       => $this->alamat,
            'email_kontak' => $this->email_kontak,
            'no_telp'      => $this->no_telp,
        ]);

        SiteSetting::updateSettings(['embed_peta' => $this->embed_peta]);

        Notification::make()
            ->title('Informasi kontak berhasil disimpan')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array { return []; }
}
