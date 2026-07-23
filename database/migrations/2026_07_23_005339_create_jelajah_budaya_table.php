<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jelajah_budaya', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150)->comment('Label kartu, cth: Upacara Adat');
            $table->string('foto')->nullable()->comment('Path foto background kartu (storage)');
            $table->string('warna', 20)->default('#1B5E20')->comment('Warna aksen/fallback gradient');
            $table->string('url', 500)->nullable()->comment('Link tujuan saat kartu diklik');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->index(['is_aktif', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jelajah_budaya');
    }
};
