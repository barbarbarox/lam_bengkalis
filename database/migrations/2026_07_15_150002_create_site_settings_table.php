<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel singleton untuk pengaturan global situs (hanya 1 baris).
 *
 * Pattern: baris tunggal dengan id=1 dilock via DB::table('site_settings')
 * ->updateOrInsert(['id' => 1], [...]).
 * Tidak boleh ada INSERT bebas — gunakan SiteSetting::updateSettings([...]).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->tinyInteger('id')->unsigned()->primary()->comment('Selalu 1 — singleton lock');

            // Identitas Lembaga
            $table->string('nama_lembaga', 200)->default('Lembaga Adat Melayu Kabupaten Bengkalis');
            $table->string('singkatan', 20)->default('LAM Bengkalis');
            $table->string('logo_path')->nullable()->comment('Path relatif ke storage');
            $table->string('favicon_path')->nullable();
            $table->string('alamat', 500)->nullable();
            $table->string('email_kontak')->nullable();
            $table->string('no_telp', 30)->nullable();

            // Media Sosial
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('twitter_url')->nullable();

            // SEO Global
            $table->string('meta_deskripsi', 500)->nullable();
            $table->string('meta_keywords', 300)->nullable();

            // Footer
            $table->text('teks_footer')->nullable();
            $table->year('tahun_berdiri')->nullable();

            $table->timestamps();
        });

        // Seed baris singleton wajib — id=1 tidak boleh didelete
        DB::table('site_settings')->insert([
            'id'         => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
