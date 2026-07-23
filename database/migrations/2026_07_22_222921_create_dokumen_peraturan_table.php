<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_peraturan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500);
            $table->string('slug', 250)->unique();
            $table->enum('jenis', ['perda', 'perbup', 'sk', 'sop', 'ad_art', 'panduan', 'laporan', 'lainnya'])
                  ->default('lainnya');
            $table->string('nomor', 100)->nullable()->comment('Nomor peraturan/dokumen');
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('file_path')->nullable()->comment('Path file PDF/Word');
            $table->string('mime_type', 50)->nullable()->comment('Jenis file: pdf, docx, dll.');
            $table->unsignedBigInteger('ukuran_file')->nullable()->comment('Ukuran file dalam bytes');
            $table->unsignedBigInteger('jumlah_unduh')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->index('jenis');
            $table->index('tahun');
            $table->index('is_aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_peraturan');
    }
};
