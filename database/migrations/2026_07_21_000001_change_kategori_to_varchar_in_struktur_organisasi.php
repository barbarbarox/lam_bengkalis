<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Mengubah kolom `kategori` dari ENUM('MKA','DPH') menjadi VARCHAR(50).
 *
 * Alasan: Data resmi LAMR Bengkalis Masa Khidmat 2024-2029 memiliki 6 kategori
 * ('MKA', 'DPH', 'Bidang', 'DKA', 'Pembimbing', 'Penasehat'). ENUM lama hanya
 * mendukung 2 nilai. Migrasi ke VARCHAR memberi fleksibilitas tanpa harus migration
 * lagi setiap ada kategori baru di masa depan.
 *
 * Data lama ('MKA', 'DPH') TIDAK berubah — kompatibel penuh.
 */
return new class extends Migration
{
    public function up(): void
    {
        // MySQL tidak bisa ALTER ENUM langsung ke VARCHAR secara aman di semua versi.
        // Pendekatan: tambah kolom sementara, copy data, drop old, rename new.
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->string('kategori_new', 50)
                ->nullable()
                ->after('jabatan')
                ->comment('Kategori anggota: MKA, DPH, Bidang, DKA, Pembimbing, Penasehat');
        });

        // Copy data dari kolom lama ke kolom baru
        DB::statement('UPDATE struktur_organisasi SET kategori_new = kategori');

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            // Drop index yang menggunakan kolom kategori lama
            $table->dropIndex('struktur_kategori_active_order_index');
            // Drop kolom lama
            $table->dropColumn('kategori');
        });

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            // Rename kolom baru menjadi kategori
            $table->renameColumn('kategori_new', 'kategori');
        });

        // Set NOT NULL setelah rename
        DB::statement(
            'ALTER TABLE struktur_organisasi MODIFY COLUMN kategori VARCHAR(50) NOT NULL COMMENT \'Kategori anggota: MKA, DPH, Bidang, DKA, Pembimbing, Penasehat\''
        );

        // Buat ulang index dengan kolom yang sudah menjadi VARCHAR
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->index(
                ['kategori', 'is_active', 'urutan'],
                'struktur_kategori_active_order_index'
            );
        });
    }

    public function down(): void
    {
        // Hapus index baru
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->dropIndex('struktur_kategori_active_order_index');
        });

        // Tambah kolom ENUM sementara
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->enum('kategori_enum', ['MKA', 'DPH'])
                ->nullable()
                ->after('kategori')
                ->comment('Rollback: Majelis Kerapatan Adat atau Dewan Pengurus Harian');
        });

        // Hanya copy data yang valid untuk ENUM lama (MKA/DPH); yang lain di-set MKA sebagai default
        DB::statement("UPDATE struktur_organisasi SET kategori_enum = CASE WHEN kategori IN ('MKA','DPH') THEN kategori ELSE 'MKA' END");

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->renameColumn('kategori_enum', 'kategori');
        });

        DB::statement(
            "ALTER TABLE struktur_organisasi MODIFY COLUMN kategori ENUM('MKA','DPH') NOT NULL COMMENT 'Majelis Kerapatan Adat atau Dewan Pengurus Harian'"
        );

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->index(
                ['kategori', 'is_active', 'urutan'],
                'struktur_kategori_active_order_index'
            );
        });
    }
};
