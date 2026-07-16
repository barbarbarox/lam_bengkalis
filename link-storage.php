<?php
/**
 * Script murni PHP untuk memperbaiki symlink rusak di Shared Hosting.
 */

$rootDir = __DIR__;
if (basename($rootDir) === 'public') {
    $rootDir = dirname($rootDir);
}

$targetFolder = $rootDir . '/storage/app/public';
$linkFolder = $rootDir . '/public/storage';

echo "<h2>Proses Perbaikan Symlink (Force Re-link)</h2>";
echo "<p>Jalur Target (Asli): <code>{$targetFolder}</code></p>";
echo "<p>Jalur Publik (Tautan): <code>{$linkFolder}</code></p>";
echo "<hr>";

// 1. Deteksi dan hapus link lama/rusak
if (is_link($linkFolder) || file_exists($linkFolder)) {
    echo "<p style='color:orange;'>⚠️ Ditemukan tautan (symlink) lama. Memproses penghapusan...</p>";
    
    // Jika bentuknya folder fisik (biasanya karena salah upload), kita ganti nama saja agar aman
    if (is_dir($linkFolder) && !is_link($linkFolder)) {
        $backupName = $linkFolder . '_backup_' . time();
        rename($linkFolder, $backupName);
        echo "<p>Folder lama diubah namanya menjadi: <code>" . basename($backupName) . "</code> untuk keamanan.</p>";
    } else {
        // Jika bentuknya file/symlink rusak, langsung hapus
        unlink($linkFolder);
        echo "<p>Tautan rusak bawaan dari komputer lokal berhasil dihapus!</p>";
    }
}

// 2. Buat symlink baru dengan path server yang benar
if (@symlink($targetFolder, $linkFolder)) {
    echo "<h3 style='color:green;'>✅ SUKSES! Symlink baru yang benar telah dibuat.</h3>";
    echo "<p>Silakan muat ulang (refresh) halaman website Anda, gambar pasti sudah muncul.</p>";
    echo "<p style='color:red;'>PENTING: Jangan lupa hapus file ini dari hosting Anda.</p>";
} else {
    echo "<h3 style='color:red;'>❌ GAGAL membuat symlink.</h3>";
    echo "<p>Fungsi symlink() di PHP sepertinya dikunci (disabled) oleh pengaturan keamanan cPanel hosting Anda.</p>";
}
