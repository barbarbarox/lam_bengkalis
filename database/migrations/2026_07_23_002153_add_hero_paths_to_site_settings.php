<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // 9 halaman modul baru — masing-masing bisa punya hero sendiri
            $table->string('hero_lam_kecamatan_path')->nullable()->after('hero_galeri_path');
            $table->string('hero_hukum_adat_path')->nullable()->after('hero_lam_kecamatan_path');
            $table->string('hero_tokoh_adat_path')->nullable()->after('hero_hukum_adat_path');
            $table->string('hero_gelar_adat_path')->nullable()->after('hero_tokoh_adat_path');
            $table->string('hero_agenda_path')->nullable()->after('hero_gelar_adat_path');
            $table->string('hero_dokumen_path')->nullable()->after('hero_agenda_path');
            $table->string('hero_permohonan_path')->nullable()->after('hero_dokumen_path');
            $table->string('hero_pendidikan_path')->nullable()->after('hero_permohonan_path');
            $table->string('hero_cari_path')->nullable()->after('hero_pendidikan_path');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_lam_kecamatan_path',
                'hero_hukum_adat_path',
                'hero_tokoh_adat_path',
                'hero_gelar_adat_path',
                'hero_agenda_path',
                'hero_dokumen_path',
                'hero_permohonan_path',
                'hero_pendidikan_path',
                'hero_cari_path',
            ]);
        });
    }
};
