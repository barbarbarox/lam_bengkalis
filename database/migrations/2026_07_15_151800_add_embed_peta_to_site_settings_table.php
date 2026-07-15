<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom embed_peta ke site_settings untuk menyimpan iframe Google Maps
 * secara dedicated (menggantikan workaround JSON di teks_footer).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('embed_peta')
                  ->nullable()
                  ->after('teks_footer')
                  ->comment('Kode iframe Google Maps embed untuk halaman Kontak');

            $table->string('url_museum')->nullable()
                  ->after('embed_peta')
                  ->comment('URL platform Jejak Layar (museum digital LAM)');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['embed_peta', 'url_museum']);
        });
    }
};
