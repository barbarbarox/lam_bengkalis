<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class TwoFactorSetup extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Akun';
    protected static ?string $title = 'Pengaturan 2FA';
    protected static string $view = 'filament.pages.two-factor-setup';

    // Sembunyikan dari navigasi jika 2FA sudah aktif
    public static function shouldRegisterNavigation(): bool
    {
        return ! Auth::user()?->hasTwoFactorEnabled();
    }

    public ?string $code = null;
    public $qrCodeSvg = null;
    public $recoveryCodes = null;
    public $secret = null;
    public $isAlreadyEnabled = false;

    public function mount()
    {
        $user = Auth::user();
        
        if ($user->hasTwoFactorEnabled()) {
            $this->isAlreadyEnabled = true;
            return;
        }

        if (! $user->twoFactorAuth->exists) {
            $user->createTwoFactorAuth();
            $user->refresh();
        }

        // Perbaiki label jika kosong agar toQr() tidak error
        if (empty($user->twoFactorAuth->label)) {
            $user->twoFactorAuth->update(['label' => config('app.name') . ':' . $user->email]);
        }
        
        $this->secret = $user->twoFactorAuth->shared_secret;
        
        try {
            $this->qrCodeSvg = $user->twoFactorAuth->toQr();
        } catch (\Exception $e) {
            $this->qrCodeSvg = null;
        }
        
        // Ambil recovery codes
        $recoveryCodes = $user->getRecoveryCodes();
        
        if (! $recoveryCodes || $recoveryCodes->isEmpty()) {
            $recoveryCodes = $user->generateRecoveryCodes();
        }

        $this->recoveryCodes = collect($recoveryCodes->toArray())->pluck('code')->toArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->label('Kode Authenticator')
                    ->helperText('Masukkan 6 digit angka dari aplikasi Google Authenticator atau Authy Anda.')
                    ->numeric()
                    ->required()
                    ->length(6),
            ]);
    }

    public function confirm()
    {
        $user = Auth::user();
        if ($user->confirmTwoFactorAuth($this->code)) {
            Notification::make()
                ->title('2FA berhasil diaktifkan!')
                ->success()
                ->send();
            
            return redirect('/pentadbir');
        }

        Notification::make()
            ->title('Kode salah atau kadaluarsa.')
            ->danger()
            ->send();
    }
}
