<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Struktur organisasi LAM Bengkalis.
 *
 * Kategori:
 *   - MKA  : Majelis Kerapatan Adat
 *   - DPH  : Dewan Pengurus Harian
 *
 * `periode` menyimpan string seperti "2022–2027" untuk fleksibilitas.
 * Diurutkan berdasarkan `kategori` lalu `urutan` untuk tampilan tabel jabatan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200)->comment('Nama lengkap anggota');
            $table->string('jabatan', 200)->comment('Jabatan dalam struktur, misal: Ketua Umum');
            $table->enum('kategori', ['MKA', 'DPH'])->comment('Majelis Kerapatan Adat atau Dewan Pengurus Harian');
            $table->string('foto')->nullable()->comment('Path foto ke storage');
            $table->unsignedSmallInteger('urutan')->default(0)->comment('Urutan tampil dalam kategori');
            $table->string('periode', 20)->nullable()->comment('Contoh: 2022–2027');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index untuk query "tampilkan struktur aktif per kategori, terurut"
            $table->index(['kategori', 'is_active', 'urutan'], 'struktur_kategori_active_order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};
