<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * RecaptchaConfigServiceProvider
 *
 * Membaca konfigurasi Google reCAPTCHA dari file rahasia `recaptcha.txt`
 * yang terletak di root proyek (BUKAN di dalam direktori public/).
 *
 * File tersebut TIDAK boleh dapat diakses via HTTP dan sudah tercantum
 * dalam .gitignore untuk mencegah kebocoran ke version control.
 *
 * Format file recaptcha.txt:
 *   RECAPTCHA_SITE_KEY=isi_dengan_site_key_anda
 *   RECAPTCHA_SECRET_KEY=isi_dengan_secret_key_anda
 */
class RecaptchaConfigServiceProvider extends ServiceProvider
{
    /**
     * Path absolut ke file konfigurasi reCAPTCHA.
     */
    private string $recaptchaFile;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->recaptchaFile = base_path('recaptcha.txt');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Tidak diperlukan binding DI untuk provider ini.
    }

    /**
     * Bootstrap any application services.
     *
     * Membaca `recaptcha.txt`, mem-parse pasangan KEY=VALUE,
     * dan menginjeksikan nilainya ke config runtime.
     */
    public function boot(): void
    {
        if (! file_exists($this->recaptchaFile)) {
            // File belum ada — konfigurasi dibiarkan kosong (default dari services.php).
            return;
        }

        $parsed = $this->parseKeyValueFile($this->recaptchaFile);

        config([
            'services.recaptcha.site_key'   => $parsed['RECAPTCHA_SITE_KEY']   ?? config('services.recaptcha.site_key'),
            'services.recaptcha.secret_key' => $parsed['RECAPTCHA_SECRET_KEY'] ?? config('services.recaptcha.secret_key'),
        ]);
    }

    /**
     * Mem-parse file teks dengan format KEY=VALUE per baris.
     *
     * Fitur:
     * - Mengabaikan baris kosong
     * - Mengabaikan baris komentar (diawali #)
     * - Membuang tanda kutip di sekitar nilai (jika ada)
     * - Trim whitespace
     *
     * @param  string  $filePath  Path absolut ke file yang akan di-parse.
     * @return array<string, string>
     */
    private function parseKeyValueFile(string $filePath): array
    {
        $result = [];

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return $result;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Lewati baris komentar
            if (str_starts_with($line, '#')) {
                continue;
            }

            // Hanya proses baris yang mengandung tanda =
            if (! str_contains($line, '=')) {
                continue;
            }

            // Pisahkan hanya pada = pertama untuk menangani nilai yang mengandung =
            [$key, $value] = explode('=', $line, 2);

            $key   = trim($key);
            $value = trim($value);

            // Buang tanda kutip ganda atau tunggal yang mengapit nilai
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            if ($key !== '') {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
