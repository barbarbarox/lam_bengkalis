<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * RecaptchaRule
 *
 * Custom Validation Rule untuk memverifikasi token Google reCAPTCHA v3
 * ke endpoint siteverify Google menggunakan HTTP server-side request.
 *
 * KEAMANAN:
 *  - Verifikasi dilakukan sepenuhnya di server (bukan browser).
 *  - Secret key TIDAK pernah terekspos ke klien.
 *  - Threshold score default 0.5 (turunkan untuk lebih longgar, naikkan lebih ketat).
 *  - Action name dicocokkan untuk mencegah token dari form lain digunakan kembali.
 *
 * PENGGUNAAN:
 *   // Dalam validator:
 *   'g-recaptcha-response' => [new RecaptchaRule(action: 'kontak_kirim')]
 *
 *   // Dengan threshold custom:
 *   'g-recaptcha-response' => [new RecaptchaRule(action: 'login', threshold: 0.7)]
 *
 *   // Setelah validasi, ambil score:
 *   $rule = new RecaptchaRule('login');
 *   // ... validate ...
 *   $score = $rule->getScore();
 */
class RecaptchaRule implements ValidationRule
{
    /** Threshold skor minimum (0.0 = bot pasti, 1.0 = manusia pasti). */
    public float $threshold;

    /** Action yang diharapkan dari form (untuk mencegah token replay lintas form). */
    public string $expectedAction;

    /** IP address request saat ini untuk dikirim ke Google. */
    private string $remoteIp;

    /** Skor yang diterima dari Google setelah verifikasi berhasil. */
    private float $score = 0.0;

    /** Pesan kesalahan yang akan ditampilkan ke pengguna. */
    private string $failureMessage = '';

    public function __construct(
        string $action    = '',
        float  $threshold = 0.5,
        string $remoteIp  = ''
    ) {
        $this->expectedAction = $action;
        $this->threshold      = $threshold;
        $this->remoteIp       = $remoteIp ?: (request()->ip() ?? '0.0.0.0');
    }

    /**
     * Jalankan validasi rule ini.
     *
     * @param  string   $attribute   Nama field yang divalidasi.
     * @param  mixed    $value       Nilai token reCAPTCHA dari form.
     * @param  Closure  $fail        Callback yang dipanggil jika validasi gagal.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');

        // Jika secret key belum dikonfigurasi, lewati validasi (dev mode)
        if (empty($secretKey)) {
            Log::warning('RecaptchaRule: secret_key belum dikonfigurasi. Validasi dilewati.');
            $this->score = 1.0; // Asumsikan valid di mode dev
            return;
        }

        // Token wajib ada (divalidasi sebelum rule ini via 'required')
        if (empty($value) || ! is_string($value)) {
            $this->failureMessage = 'Verifikasi keamanan gagal. Muat ulang halaman dan coba kembali.';
            $fail($this->failureMessage);
            return;
        }

        // Kirim request verifikasi ke Google
        try {
            $response = Http::timeout(6)
                ->retry(2, sleepMilliseconds: 500)
                ->asForm()
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $secretKey,
                    'response' => $value,
                    'remoteip' => $this->remoteIp,
                ]);
        } catch (\Throwable $e) {
            // Kegagalan jaringan → log tapi jangan blokir pengguna legit
            Log::error('RecaptchaRule: Gagal menghubungi API Google siteverify.', [
                'error'  => $e->getMessage(),
                'action' => $this->expectedAction,
                'ip'     => $this->remoteIp,
            ]);

            // Fail-open: izinkan request dengan skor 0 agar pengguna legit
            // tidak terblokir saat infrastruktur Google bermasalah.
            // Admin dapat mengaudit via kolom recaptcha_score di database.
            $this->score = 0.0;
            return;
        }

        if (! $response->successful()) {
            Log::warning('RecaptchaRule: Response Google tidak sukses.', [
                'status' => $response->status(),
            ]);
            $this->score = 0.0;
            return;
        }

        $data = $response->json();

        // --- Cek 1: success flag ---
        if (! ($data['success'] ?? false)) {
            $errorCodes = implode(', ', $data['error-codes'] ?? []);
            Log::warning('RecaptchaRule: Verifikasi ditolak Google.', [
                'error_codes' => $errorCodes,
                'action'      => $this->expectedAction,
                'ip'          => $this->remoteIp,
            ]);

            $this->failureMessage = 'Verifikasi keamanan gagal. Muat ulang halaman dan coba kembali.';
            $fail($this->failureMessage);
            return;
        }

        $this->score = (float) ($data['score'] ?? 0.0);
        $receivedAction = $data['action'] ?? '';

        // --- Cek 2: action name ---
        if (! empty($this->expectedAction) && $receivedAction !== $this->expectedAction) {
            Log::warning('RecaptchaRule: Action tidak cocok.', [
                'expected' => $this->expectedAction,
                'received' => $receivedAction,
                'score'    => $this->score,
                'ip'       => $this->remoteIp,
            ]);

            $this->failureMessage = 'Verifikasi keamanan gagal. Muat ulang halaman dan coba kembali.';
            $fail($this->failureMessage);
            return;
        }

        // --- Cek 3: threshold skor ---
        if ($this->score < $this->threshold) {
            Log::warning('RecaptchaRule: Skor reCAPTCHA terlalu rendah (kemungkinan bot).', [
                'score'     => $this->score,
                'threshold' => $this->threshold,
                'action'    => $this->expectedAction,
                'ip'        => $this->remoteIp,
            ]);

            $this->failureMessage = 'Permintaan Anda terdeteksi mencurigakan. Silakan coba kembali atau hubungi kami langsung.';
            $fail($this->failureMessage);
            return;
        }

        // --- Sukses ---
        Log::info('RecaptchaRule: Verifikasi berhasil.', [
            'score'  => $this->score,
            'action' => $this->expectedAction,
        ]);
    }

    /**
     * Dapatkan skor reCAPTCHA setelah verifikasi.
     * Gunakan ini untuk menyimpan score ke database untuk keperluan audit.
     */
    public function getScore(): float
    {
        return $this->score;
    }
}
