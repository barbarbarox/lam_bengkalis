<?php
// Force output langsung
ob_implicit_flush(true);
ob_end_clean();

header('Content-Type: text/html; charset=utf-8');
header('X-Accel-Buffering: no');

echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Setup</title></head><body>';
echo '<h2>✅ PHP Berjalan — Versi: ' . PHP_VERSION . '</h2>';
flush();

define('SECRET_KEY', 'LamSetup2026');
if (!isset($_GET['key']) || $_GET['key'] !== SECRET_KEY) {
    die('<p style="color:red">❌ 403 — Akses ditolak. Tambahkan ?key=LamSetup2026 di URL</p></body></html>');
}

echo '<p>🔑 Key valid. Memulai operasi...</p><hr>';
flush();

$base = realpath(dirname(__DIR__));
echo '<p>📂 Base path: <code>' . htmlspecialchars($base) . '</code></p>';
flush();

// Cek folder penting
$folders = ['storage', 'storage/logs', 'storage/framework', 'storage/framework/cache',
            'storage/framework/sessions', 'storage/framework/views', 'bootstrap/cache'];
echo '<h3>📁 Status Folder:</h3><ul>';
foreach ($folders as $f) {
    $full = $base . '/' . $f;
    $exists  = is_dir($full) ? '✅ Ada' : '❌ Tidak ada';
    $writable = is_writable($full) ? '✅ Writable' : '⚠️ Tidak writable';
    $perms = is_dir($full) ? substr(sprintf('%o', fileperms($full)), -4) : '-';
    echo "<li><code>$f</code> — $exists — $writable — Perm: $perms</li>";
    flush();
}
echo '</ul>';

// Chmod
echo '<h3>🔧 Chmod:</h3><ul>';
$targets = ['storage', 'bootstrap/cache'];
foreach ($targets as $t) {
    $full = $base . '/' . $t;
    if (is_dir($full)) {
        $r = @chmod($full, 0775);
        echo '<li><code>' . $t . '</code>: ' . ($r ? '✅ chmod 775 OK' : '⚠️ chmod gagal (mungkin tidak diperlukan)') . '</li>';
    } else {
        echo '<li><code>' . $t . '</code>: ❌ Folder tidak ditemukan</li>';
    }
    flush();
}
echo '</ul>';

// Shell exec check
$shellOk = function_exists('shell_exec') && !in_array('shell_exec',
    array_map('trim', explode(',', ini_get('disable_functions') ?? '')));
echo '<h3>🖥 Shell:</h3>';
echo '<p>shell_exec: ' . ($shellOk ? '✅ Tersedia' : '❌ Dinonaktifkan') . '</p>';
flush();

if ($shellOk) {
    $phpBin = PHP_BINARY;
    echo '<ul>';
    foreach (['config:clear', 'config:cache', 'route:clear', 'route:cache', 'view:clear', 'view:cache', 'storage:link'] as $cmd) {
        $out = @shell_exec("cd " . escapeshellarg($base) . " && $phpBin artisan $cmd 2>&1");
        $ok  = $out !== null && stripos($out, 'error') === false && stripos($out, 'exception') === false;
        echo '<li><code>artisan ' . $cmd . '</code>: ' . ($ok ? '✅' : '⚠️') . ' <small>' . htmlspecialchars(trim($out ?? 'no output')) . '</small></li>';
        flush();
    }
    echo '</ul>';
} else {
    echo '<p>⚠️ Artisan tidak bisa dijalankan. Hubungi hosting atau gunakan cPanel Terminal.</p>';
}

echo '<hr><p style="color:orange;font-weight:bold">⚠️ HAPUS file setup-run.php dari hosting setelah selesai!</p>';
echo '</body></html>';
