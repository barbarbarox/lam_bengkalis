<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;

class KesehatanSistemPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Kesehatan Sistem';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 100;
    protected static ?string $title = 'Kesehatan & Pemeliharaan Sistem';
    protected static string $view = 'filament.pages.kesehatan-sistem-page';

    public ?string $commandError = null;

    protected function getViewData(): array
    {
        return [
            'totalSubscribers' => \App\Models\Subscriber::count(),
            'subscribers' => \App\Models\Subscriber::latest()->limit(50)->get(),
        ];
    }

    public function clearCache()
    {
        $this->commandError = null;
        try {
            Artisan::call('cache:clear');
            Notification::make()->title('Cache Aplikasi berhasil dibersihkan')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Gagal membersihkan Cache Aplikasi:\n" . $e->getMessage();
            Notification::make()->title('Terjadi Kesalahan')->danger()->send();
        }
    }

    public function clearView()
    {
        $this->commandError = null;
        try {
            Artisan::call('view:clear');
            Notification::make()->title('Cache View (Tampilan) berhasil dibersihkan')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Gagal membersihkan Cache View:\n" . $e->getMessage();
            Notification::make()->title('Terjadi Kesalahan')->danger()->send();
        }
    }

    public function clearConfig()
    {
        $this->commandError = null;
        try {
            Artisan::call('config:clear');
            Notification::make()->title('Cache Konfigurasi berhasil dibersihkan')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Gagal membersihkan Cache Konfigurasi:\n" . $e->getMessage();
            Notification::make()->title('Terjadi Kesalahan')->danger()->send();
        }
    }

    public function clearRoute()
    {
        $this->commandError = null;
        try {
            Artisan::call('route:clear');
            Notification::make()->title('Cache Route berhasil dibersihkan')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Gagal membersihkan Cache Route:\n" . $e->getMessage();
            Notification::make()->title('Terjadi Kesalahan')->danger()->send();
        }
    }

    public function optimizeClear()
    {
        $this->commandError = null;
        try {
            Artisan::call('optimize:clear');
            Notification::make()->title('Semua Cache (Optimize Clear) berhasil dibersihkan')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Gagal membersihkan Semua Cache:\n" . $e->getMessage();
            Notification::make()->title('Terjadi Kesalahan')->danger()->send();
        }
    }

    public function migrateAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('migrateAction')
            ->label('Jalankan Migrasi')
            ->icon('heroicon-o-server-stack')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading('Jalankan Migrasi Database')
            ->modalDescription('Apakah Anda yakin ingin menjalankan migrasi database? Pastikan Anda telah melakukan pencadangan data (backup) sebelum melanjutkan, karena ini akan mengubah struktur tabel.')
            ->modalSubmitActionLabel('Ya, Jalankan')
            ->action(function () {
                $this->runMigrate();
            });
    }

    public function seederAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('seederAction')
            ->label('Jalankan Seeder')
            ->icon('heroicon-o-circle-stack')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Jalankan Seeder Database')
            ->modalDescription('Apakah Anda yakin ingin menjalankan seeder? Tindakan ini dapat menimpa atau menambahkan data default/dummy ke dalam database Anda.')
            ->modalSubmitActionLabel('Ya, Jalankan')
            ->action(function () {
                $this->runSeeder();
            });
    }

    public function deepClearAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('deepClearAction')
            ->label('Pembersihan Mendalam')
            ->icon('heroicon-o-sparkles')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Pembersihan Cache Mendalam')
            ->modalDescription('Fitur ini akan mereset PHP Opcache, membersihkan cache Livewire/Filament, serta membersihkan semua jenis cache aplikasi. Sangat berguna jika perubahan kode tidak langsung muncul di hosting.')
            ->modalSubmitActionLabel('Ya, Bersihkan Sekarang')
            ->action(function () {
                $this->deepClearCache();
            });
    }

    public function deepClearCache()
    {
        $this->commandError = null;
        try {
            Artisan::call('optimize:clear');
            Artisan::call('view:clear');
            
            $livewireCache = base_path('bootstrap/cache/livewire-components.php');
            if (file_exists($livewireCache)) {
                @unlink($livewireCache);
            }
            
            try { Artisan::call('livewire:discover'); } catch (\Exception $e) {}
            try { Artisan::call('filament:clear-cached-components'); } catch (\Exception $e) {}
            
            if (function_exists('opcache_reset')) {
                @opcache_reset();
            }
            
            Notification::make()->title('Pembersihan Cache Mendalam (Deep Clear) Berhasil')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Gagal melakukan pembersihan mendalam:\n" . $e->getMessage();
            Notification::make()->title('Terjadi Kesalahan')->danger()->send();
        }
    }

    public function runMigrate()
    {
        $this->commandError = null;
        try {
            Artisan::call('migrate', ['--force' => true]);
            Notification::make()->title('Migrasi Database berhasil dijalankan.')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Migrasi gagal:\n" . $e->getMessage();
            Notification::make()->title('Migrasi gagal')->danger()->send();
        }
    }

    public function runSeeder()
    {
        $this->commandError = null;
        try {
            Artisan::call('db:seed', ['--force' => true]);
            Notification::make()->title('Seeder Database berhasil dijalankan.')->success()->send();
        } catch (\Exception $e) {
            $this->commandError = "Seeder gagal:\n" . $e->getMessage();
            Notification::make()->title('Seeder gagal')->danger()->send();
        }
    }
}
