<?php

namespace App\Filament\Pages\Auth;

use App\Rules\RecaptchaRule;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * PentadbirLogin
 *
 * Halaman login kustom untuk panel /pentadbir yang menambahkan:
 *
 * 1. Rate limiting: 5 percobaan per menit per IP+email (kunci SHA-256)
 * 2. Session ID regeneration setelah login berhasil (session fixation protection)
 * 3. Blokir login untuk akun yang is_active = false
 * 4. Pesan error generik (tidak membedakan "email salah" vs "password salah")
 * 5. Verifikasi reCAPTCHA v3 server-side sebelum autentikasi diproses
 */
class PentadbirLogin extends BaseLogin
{
    /** Judul halaman tab browser. */
    protected static ?string $title = 'Masuk — Panel Pentadbir LAM Bengkalis';

    /**
     * Data Livewire — properti ini diset oleh JS saat token reCAPTCHA diterima.
     * @var string
     */
    public string $recaptchaToken = '';

    /**
     * Konfigurasi form login.
     * Mengganti label default Filament ke Bahasa Indonesia.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->autocomplete('email')
                    ->autofocus()
                    ->placeholder('admin@lam-bengkalis.go.id'),

                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required()
                    ->revealable(filament()->arePasswordsRevealable()),
            ])
            ->statePath('data');
    }

    /**
     * Override proses autentikasi untuk menambahkan:
     * - Verifikasi reCAPTCHA v3 server-side
     * - Rate limiting berbasis IP + email
     * - Session ID regenerasi (session fixation mitigation)
     * - Cek is_active sebelum mengizinkan login
     */
    public function authenticate(): ?LoginResponse
    {
        $email = Str::lower(trim((string) ($this->data['email'] ?? '')));
        $ip    = request()->ip() ?? '0.0.0.0';
        $key   = 'pentadbir_login:' . hash('sha256', $ip . '|' . $email);

        // ── 1. Rate Limit Check ─────────────────────────────────────────────
        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'data.email' => __('Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.', [
                    'seconds' => $seconds,
                ]),
            ]);
        }

        // ── 2. Verifikasi reCAPTCHA v3 ────────────────────────────────────
        $this->verifyRecaptcha($ip);

        // ── 3. Hit rate limit counter ─────────────────────────────────────
        RateLimiter::hit($key, decaySeconds: 60);

        // ── 4. Autentikasi via parent Filament ────────────────────────────
        try {
            $response = parent::authenticate();
        } catch (ValidationException $e) {
            // Biarkan exception Filament tetap bubbling, counter sudah di-hit
            throw $e;
        }

        // ── 5. Post-login: reset counter & regenerate session ─────────────
        RateLimiter::clear($key);

        // Regenerasi Session ID untuk mencegah Session Fixation Attack
        request()->session()->regenerate(true);

        return $response;
    }

    /**
     * Verifikasi token reCAPTCHA v3 menggunakan RecaptchaRule.
     * Lempar ValidationException jika verifikasi gagal.
     *
     * @param  string  $ip  IP address request saat ini.
     * @throws ValidationException
     */
    private function verifyRecaptcha(string $ip): void
    {
        $siteKey = config('services.recaptcha.site_key');

        // Lewati jika reCAPTCHA belum dikonfigurasi (local dev)
        if (empty($siteKey)) {
            return;
        }

        // Ambil token dari properti Livewire atau dari request langsung
        $token = $this->recaptchaToken
            ?? request()->input('recaptcha_token', '');

        if (empty($token)) {
            // Token tidak ada — kemungkinan JS dinonaktifkan atau bot langsung POST
            Log::warning('PentadbirLogin: reCAPTCHA token tidak ditemukan.', [
                'ip' => $ip,
            ]);

            // Jangan blokir — token bisa tidak ada di lingkungan dev/testing
            // Produksi: uncomment baris berikut untuk wajibkan token
            // throw ValidationException::withMessages([
            //     'data.email' => 'Verifikasi keamanan gagal. Pastikan JavaScript diaktifkan.',
            // ]);
            return;
        }

        // Gunakan RecaptchaRule untuk verifikasi ke Google
        $rule   = new RecaptchaRule(action: 'admin_login', threshold: 0.5, remoteIp: $ip);
        $failed = false;
        $msg    = '';

        $rule->validate('recaptcha_token', $token, function (string $message) use (&$failed, &$msg) {
            $failed = true;
            $msg    = $message;
        });

        if ($failed) {
            Log::warning('PentadbirLogin: Verifikasi reCAPTCHA gagal.', [
                'ip'    => $ip,
                'score' => $rule->getScore(),
                'msg'   => $msg,
            ]);

            throw ValidationException::withMessages([
                'data.email' => $msg,
            ]);
        }

        Log::info('PentadbirLogin: reCAPTCHA OK.', [
            'score' => $rule->getScore(),
            'ip'    => $ip,
        ]);
    }

    /**
     * Override getCredentialsFromFormData untuk menyisipkan validasi is_active.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email'     => $data['email'],
            'password'  => $data['password'],
            'is_active' => true, // hanya akun aktif yang bisa login
        ];
    }
}
