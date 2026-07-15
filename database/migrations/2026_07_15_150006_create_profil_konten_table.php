<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Halaman konten statis yang dikelola via CMS.
 * Contoh slug: "sejarah-lam", "visi-misi", "tugas-fungsi", "dasar-hukum".
 *
 * Kolom `slug` diindex untuk lookup URL-friendly yang cepat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_konten', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique()->comment('Identifier URL, misal: sejarah-lam');
            $table->string('judul', 300)->comment('Judul halaman ditampilkan di <h1>');
            $table->longText('konten')->comment('Isi halaman HTML (sanitasi mews/purifier)');
            $table->string('meta_deskripsi', 500)->nullable()->comment('Override meta description per halaman');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index utama sudah dicakup oleh unique constraint pada slug.
            // Tambah index is_active untuk filter di admin.
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_konten');
    }
};
