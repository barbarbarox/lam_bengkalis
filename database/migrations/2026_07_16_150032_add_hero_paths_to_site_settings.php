<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('hero_profil_path')->nullable()->after('url_museum');
            $table->string('hero_berita_path')->nullable()->after('hero_profil_path');
            $table->string('hero_kontak_path')->nullable()->after('hero_berita_path');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_profil_path', 'hero_berita_path', 'hero_kontak_path']);
        });
    }
};
