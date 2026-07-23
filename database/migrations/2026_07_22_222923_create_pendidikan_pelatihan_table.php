<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendidikan_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500);
            $table->string('slug', 250)->unique();
            $table->enum('jenis', ['pelatihan', 'workshop', 'seminar', 'kursus', 'beasiswa', 'pendidikan_formal', 'lainnya'])
                  ->default('pelatihan');
            $table->string('penyelenggara', 200)->nullable()->default('LAMR Kabupaten Bengkalis');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi', 300)->nullable();
            $table->unsignedInteger('kuota')->nullable();
            $table->decimal('biaya', 15, 2)->nullable()->comment('Null atau 0 = gratis');
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable()->comment('Informasi lengkap (HTML)');
            $table->string('thumbnail')->nullable();
            $table->string('link_pendaftaran', 500)->nullable()->comment('URL eksternal form pendaftaran');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->index('jenis');
            $table->index('tanggal_mulai');
            $table->index('is_aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendidikan_pelatihan');
    }
};
