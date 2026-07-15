<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Slide latar belakang untuk hero/banner halaman utama.
 * Diurutkan berdasarkan kolom `urutan` secara ascending.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('background_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->comment('Path relatif ke storage/app/public');
            $table->string('alt_text', 300)->comment('Teks alternatif untuk aksesibilitas & SEO');
            $table->unsignedSmallInteger('urutan')->default(0)->comment('Urutan tampil, ascending');
            $table->boolean('is_active')->default(true)->comment('Apakah slide ditampilkan');
            $table->timestamps();

            // Index untuk query publik: ambil slide aktif terurut
            $table->index(['is_active', 'urutan'], 'slides_active_order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('background_slides');
    }
};
