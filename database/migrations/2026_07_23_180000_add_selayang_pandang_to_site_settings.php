<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Foto Balai Adat untuk hero beranda
            $table->string('foto_balai_adat')->nullable()->after('logo_path')
                  ->comment('Foto Balai Adat ditampilkan di hero beranda');

            // Selayang pandang (tentang lembaga singkat) untuk beranda
            $table->text('selayang_pandang')->nullable()->after('teks_footer')
                  ->comment('Teks selayang pandang di halaman beranda');

            // Statistik yang bisa diatur dari admin
            $table->integer('stat_kecamatan')->default(11)->after('selayang_pandang');
            $table->integer('stat_desa_kelurahan')->default(105)->after('stat_kecamatan');
            $table->integer('stat_kegiatan_budaya')->default(250)->after('stat_desa_kelurahan');
            $table->integer('stat_naskah_koleksi')->default(1250)->after('stat_kegiatan_budaya');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'foto_balai_adat',
                'selayang_pandang',
                'stat_kecamatan',
                'stat_desa_kelurahan',
                'stat_kegiatan_budaya',
                'stat_naskah_koleksi',
            ]);
        });
    }
};
