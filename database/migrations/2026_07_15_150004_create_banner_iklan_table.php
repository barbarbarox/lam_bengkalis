<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Banner iklan / pengumuman berformat gambar dengan periode tayang.
 * Banner yang aktif ditampilkan hanya jika:
 *   - is_active = true
 *   - tanggal_mulai IS NULL OR tanggal_mulai <= NOW()
 *   - tanggal_selesai IS NULL OR tanggal_selesai >= NOW()
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_iklan', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->comment('Path banner ke storage');
            $table->string('link_url')->nullable()->comment('URL tujuan klik banner, boleh kosong');
            $table->string('alt_text', 300)->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('tanggal_mulai')->nullable()->comment('Null = langsung aktif');
            $table->date('tanggal_selesai')->nullable()->comment('Null = tanpa batas');
            $table->timestamps();

            // Index gabungan untuk query "banner yang sedang aktif"
            $table->index(['is_active', 'tanggal_mulai', 'tanggal_selesai'], 'banner_active_period_index');
            $table->index('urutan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_iklan');
    }
};
