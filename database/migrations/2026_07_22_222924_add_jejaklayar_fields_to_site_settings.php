<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Jejak Layar: URL utama sistem museum digital
            $table->string('url_jejaklayar', 500)->nullable()
                  ->after('embed_peta')
                  ->comment('URL utama platform Jejak Layar (museum digital)');

            // URL per fitur budaya yang diarahkan ke Jejak Layar
            $table->string('url_jl_bahasa', 500)->nullable()
                  ->comment('URL Jejak Layar — Bahasa & Kamus Melayu');
            $table->string('url_jl_warisan', 500)->nullable()
                  ->comment('URL Jejak Layar — Warisan Budaya');
            $table->string('url_jl_kesenian', 500)->nullable()
                  ->comment('URL Jejak Layar — Kesenian & Tradisi');
            $table->string('url_jl_destinasi', 500)->nullable()
                  ->comment('URL Jejak Layar — Destinasi Budaya');
            $table->string('url_jl_artikel', 500)->nullable()
                  ->comment('URL Jejak Layar — Artikel Budaya');
            $table->string('url_jl_pustaka', 500)->nullable()
                  ->comment('URL Jejak Layar — Pustaka Adat');

            // Kontak WhatsApp
            $table->string('no_wa', 30)->nullable()
                  ->comment('Nomor WhatsApp untuk tombol WA');

            // Bahasa aktif (JSON array: ["id","ms","jawi"])
            $table->string('bahasa_aktif', 100)->nullable()->default('["id"]')
                  ->comment('Bahasa yang aktif sebagai JSON array');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'url_jejaklayar',
                'url_jl_bahasa', 'url_jl_warisan', 'url_jl_kesenian',
                'url_jl_destinasi', 'url_jl_artikel', 'url_jl_pustaka',
                'no_wa', 'bahasa_aktif',
            ]);
        });
    }
};
