<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Konten sambutan dari Badan Pengurus Harian (BPH) LAM Bengkalis.
 * Biasanya hanya satu baris aktif, tapi tabel ini mendukung riwayat
 * beberapa periode kepengurusan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sambutan_bph', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ketua', 200)->comment('Nama lengkap Ketua BPH');
            $table->string('jabatan', 200)->comment('Jabatan resmi, misal: Ketua Umum LAM Bengkalis');
            $table->string('foto')->nullable()->comment('Path foto ketua ke storage');
            $table->longText('isi_sambutan')->comment('Teks sambutan dalam format HTML (disanitasi mews/purifier)');
            $table->boolean('is_active')->default(true)->comment('Sambutan yang sedang ditampilkan');
            $table->unsignedSmallInteger('periode_mulai')->nullable()->comment('Tahun mulai periode jabatan');
            $table->unsignedSmallInteger('periode_selesai')->nullable()->comment('Tahun selesai periode jabatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sambutan_bph');
    }
};
