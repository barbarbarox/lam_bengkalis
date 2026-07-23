<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelar_adat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gelar', 200)->comment('Nama gelar, misal: Datuk Sri Maharaja');
            $table->string('slug', 250)->unique();
            $table->enum('jenis', ['gelar_adat', 'penghargaan', 'kehormatan', 'pusaka'])->default('gelar_adat');
            $table->string('tingkatan', 100)->nullable()->comment('Tingkatan gelar: maharaja, dato, seri, dll.');
            $table->text('deskripsi')->nullable()->comment('Keterangan singkat gelar');
            $table->longText('makna')->nullable()->comment('Makna & filosofi gelar (HTML)');
            $table->longText('syarat_pemberian')->nullable()->comment('Syarat & tata cara pemberian gelar (HTML)');
            $table->text('penerima_terkini')->nullable()->comment('Nama penerima terakhir');
            $table->boolean('is_aktif')->default(true);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();

            $table->index(['jenis', 'is_aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gelar_adat');
    }
};
