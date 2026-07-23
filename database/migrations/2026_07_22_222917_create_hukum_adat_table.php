<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hukum_adat', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500)->comment('Judul peraturan/hukum adat');
            $table->string('slug', 250)->unique();
            $table->enum('jenis', ['peraturan_adat', 'keputusan', 'pedoman', 'fatwa_adat', 'undang_undang_adat', 'lainnya'])
                  ->default('peraturan_adat');
            $table->string('nomor_dokumen', 100)->nullable()->comment('Nomor dokumen, misal: LAMR/SK/001/2024');
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->longText('konten')->nullable()->comment('Isi lengkap hukum/peraturan (HTML)');
            $table->string('file_path')->nullable()->comment('Path file PDF');
            $table->unsignedBigInteger('ukuran_file')->nullable()->comment('Ukuran file dalam bytes');
            $table->string('thumbnail')->nullable();
            $table->text('ringkasan')->nullable()->comment('Ringkasan singkat');
            $table->boolean('is_aktif')->default(true);
            $table->unsignedBigInteger('jumlah_unduh')->default(0);
            $table->timestamps();

            $table->index('jenis');
            $table->index('is_aktif');
            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hukum_adat');
    }
};
