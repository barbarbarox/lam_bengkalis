<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kategori berita — harus dibuat SEBELUM tabel berita
 * karena berita.berita_kategori_id FK ke tabel ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique()->comment('Nama kategori, misal: Adat Budaya');
            $table->string('slug', 100)->unique()->comment('URL-friendly, misal: adat-budaya');
            $table->string('deskripsi', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_kategori');
    }
};
