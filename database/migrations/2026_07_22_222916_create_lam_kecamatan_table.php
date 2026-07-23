<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lam_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kecamatan', 100)->comment('Nama kecamatan');
            $table->string('nama_ketua', 150)->nullable()->comment('Nama Datuk Penghulu / Ketua LAM Kecamatan');
            $table->string('jabatan_ketua', 100)->nullable()->default('Ketua LAM Kecamatan');
            $table->text('alamat')->nullable()->comment('Alamat sekretariat');
            $table->string('no_telp', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('foto_ketua_path')->nullable()->comment('Foto Ketua LAM Kecamatan');
            $table->string('foto_gedung_path')->nullable()->comment('Foto gedung/balai adat kecamatan');
            $table->text('deskripsi')->nullable()->comment('Keterangan singkat tentang LAM kecamatan');
            $table->unsignedInteger('jumlah_nagori')->nullable()->comment('Jumlah desa/kelurahan');
            $table->boolean('is_aktif')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index('is_aktif');
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lam_kecamatan');
    }
};
