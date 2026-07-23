<?php
/**
 * File Setup Otomatis LAMR Bengkalis
 * 
 * Digunakan untuk deployment pertama kali di shared hosting
 * yang tidak memiliki akses terminal (SSH).
 * 
 * PERINGATAN: HAPUS FILE INI SETELAH DIGUNAKAN UNTUK KEAMANAN.
 */

// Memuat Autoloader Composer
require __DIR__.'/../vendor/autoload.php';

// Memuat Aplikasi Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Menjalankan Kernel HTTP agar semua komponen (Database, Config, dsb) dimuat
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Artisan;

echo "<!DOCTYPE html>";
echo "<html lang='id'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Setup Database & Cache - LAMR Bengkalis</title>";
echo "<style>";
echo "body { font-family: system-ui, -apple-system, sans-serif; background: #f8f9fa; color: #333; max-width: 800px; margin: 40px auto; padding: 20px; line-height: 1.6; }";
echo "h1 { color: #0d3b2e; border-bottom: 2px solid #f99522; padding-bottom: 10px; }";
echo "h3 { color: #0d3b2e; margin-top: 25px; }";
echo "pre { background: #1e1e1e; color: #4ade80; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 13px; }";
echo ".alert { background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; font-weight: bold; margin-bottom: 20px; border: 1px solid #f87171; }";
echo ".success { background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; font-weight: bold; margin-top: 30px; border: 1px solid #4ade80; }";
echo ".btn { display: inline-block; background: #0d3b2e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-right: 10px; margin-top: 15px; font-weight: 600; }";
echo ".btn:hover { background: #f99522; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>🚀 Setup Awal Aplikasi LAMR Bengkalis</h1>";
echo "<div class='alert'>PENTING: Segera hapus file <code>setup.php</code> ini dari server Anda setelah proses di bawah ini selesai demi alasan keamanan.</div>";

try {
    // 1. Membersihkan Cache
    echo "<h3>1. Membersihkan Seluruh Cache (Optimize Clear)...</h3>";
    Artisan::call('optimize:clear');
    Artisan::call('view:clear');
    
    // Hapus file cache livewire secara manual jika masih tersangkut
    $livewireCache = __DIR__.'/../bootstrap/cache/livewire-components.php';
    if (file_exists($livewireCache)) {
        unlink($livewireCache);
        echo "<p>File cache Livewire berhasil dihapus manual.</p>";
    }

    try {
        Artisan::call('livewire:discover');
        echo "<pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {}

    try {
        Artisan::call('filament:clear-cached-components');
        echo "<pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {}

    echo "<pre>" . Artisan::output() . "</pre>";

    // 2. Migrasi Database
    echo "<h3>2. Menjalankan Migrasi Database...</h3>";
    Artisan::call('migrate', ['--force' => true]);
    echo "<pre>" . Artisan::output() . "</pre>";

    // 3. Menjalankan Seeder
    echo "<h3>3. Mengirim Data Awal (Seeder)...</h3>";
    Artisan::call('db:seed', ['--force' => true]);
    echo "<pre>" . Artisan::output() . "</pre>";

    // Membuat Storage Link (Opsional, sangat disarankan)
    echo "<h3>4. Membuat Tautan Storage (Storage Link)...</h3>";
    Artisan::call('storage:link');
    echo "<pre>" . Artisan::output() . "</pre>";

    if (function_exists('opcache_reset')) {
        opcache_reset();
        echo "<div class='success'>✅ PHP Opcache berhasil di-reset.</div>";
    }

    echo "<div class='success'>✅ Semua proses setup berhasil dijalankan tanpa masalah!</div>";
    
    echo "<div>";
    echo "<a href='/' class='btn'>Buka Halaman Utama</a>";
    echo "<a href='/admin' class='btn'>Buka Halaman Admin</a>";
    echo "</div>";

} catch (\Exception $e) {
    echo "<h3>❌ Terjadi Kesalahan:</h3>";
    echo "<pre style='color: #f87171;'>" . $e->getMessage() . "</pre>";
    echo "<p>Pastikan Anda telah mengatur konfigurasi database di file <code>.env</code> dengan benar sebelum memanggil file ini.</p>";
}

echo "</body>";
echo "</html>";
