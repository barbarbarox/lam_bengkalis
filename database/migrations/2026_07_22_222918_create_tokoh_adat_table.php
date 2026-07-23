<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokoh_adat', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->string('slug', 250)->unique();
            $table->string('gelar_adat', 150)->nullable()->comment('Gelar adat yang dimiliki, misal: Datuk Seri');
            $table->string('jabatan', 200)->nullable()->comment('Jabatan dalam LAMR');
            $table->string('kecamatan', 100)->nullable();
            $table->longText('biografi')->nullable()->comment('Biografi lengkap (HTML)');
            $table->text('ringkasan')->nullable()->comment('Ringkasan singkat untuk card');
            $table->string('foto_path')->nullable();
            $table->unsignedSmallInteger('tahun_lahir')->nullable();
            $table->unsignedSmallInteger('tahun_wafat')->nullable()->comment('Null jika masih hidup');
            $table->boolean('is_aktif')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index('is_aktif');
            $table->index('kecamatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokoh_adat');
    }
};
