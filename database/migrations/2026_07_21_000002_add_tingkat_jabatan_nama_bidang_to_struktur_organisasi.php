<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah dua kolom baru ke tabel struktur_organisasi:
 *
 * 1. `nama_bidang` (VARCHAR 200, nullable) — menyimpan nama bidang untuk anggota
 *    berkategori 'Bidang', contoh: "Bidang Organisasi Dan Tata Laksana".
 *    Null untuk kategori MKA/DPH/DKA/Pembimbing/Penasehat.
 *
 * 2. `tingkat_jabatan` (ENUM 'pimpinan'|'anggota', default 'anggota') — menandai
 *    apakah anggota adalah pimpinan bidang (Penyelaras/Ketua) atau anggota biasa.
 *    Digunakan untuk memisahkan tampilan: pimpinan selalu tampil di kartu,
 *    anggota biasa masuk ke modal "Lihat Anggota Lainnya".
 *
 *    Dipilih ENUM eksplisit (bukan deteksi string jabatan) agar:
 *    - Robust terhadap variasi ketikan admin di masa depan
 *    - Tidak perlu update query jika sinonim jabatan bertambah
 *    - Database-level constraint yang jelas
 *
 * Backfill otomatis untuk data lama:
 *    - 'pimpinan' jika jabatan mengandung kata: Ketua, Penyelaras, Timbalan,
 *      Sekretaris Umum, Bendahara Umum (case-insensitive)
 *    - 'anggota' untuk semua lainnya
 *
 * Migration ini NON-DESTRUCTIVE — tidak mengubah/menghapus kolom yang sudah ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->string('nama_bidang', 200)
                ->nullable()
                ->after('kategori')
                ->comment('Nama bidang untuk kategori Bidang, contoh: Bidang Organisasi Dan Tata Laksana');

            $table->enum('tingkat_jabatan', ['pimpinan', 'anggota'])
                ->default('anggota')
                ->after('nama_bidang')
                ->comment('Pimpinan tampil langsung di kartu publik; anggota masuk ke modal');
        });

        // Backfill tingkat_jabatan untuk data lama berdasarkan kata kunci jabatan
        // Rule: kata kunci = Ketua Umum, Timbalan Ketua, Ketua, Penyelaras,
        //       Sekretaris Umum, Bendahara Umum — maka 'pimpinan', selain itu 'anggota'
        DB::statement("
            UPDATE struktur_organisasi
            SET tingkat_jabatan = 'pimpinan'
            WHERE (
                jabatan LIKE '%Ketua Umum%'
                OR jabatan LIKE '%Timbalan Ketua%'
                OR jabatan LIKE '%Ketua%'
                OR jabatan LIKE '%Penyelaras%'
                OR jabatan LIKE '%Sekretaris Umum%'
                OR jabatan LIKE '%Bendahara Umum%'
            )
        ");

        // Tambah index komposit untuk query publik bidang
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->index(
                ['kategori', 'nama_bidang', 'is_active', 'urutan'],
                'struktur_bidang_active_order_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->dropIndex('struktur_bidang_active_order_index');
            $table->dropColumn(['nama_bidang', 'tingkat_jabatan']);
        });
    }
};
