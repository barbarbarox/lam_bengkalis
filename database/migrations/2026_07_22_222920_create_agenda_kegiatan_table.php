<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500);
            $table->string('slug', 250)->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi', 300)->nullable();
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable()->comment('Isi lengkap informasi kegiatan (HTML)');
            $table->string('thumbnail')->nullable();
            $table->enum('jenis', ['internal', 'publik', 'seremonial', 'pelatihan', 'rapat', 'lainnya'])
                  ->default('publik');
            $table->enum('status', ['akan_datang', 'berlangsung', 'selesai', 'dibatalkan'])
                  ->default('akan_datang');
            $table->string('penyelenggara', 200)->nullable()->default('LAMR Kabupaten Bengkalis');
            $table->unsignedInteger('kuota')->nullable()->comment('Kuota peserta, null=tidak terbatas');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->index('tanggal_mulai');
            $table->index('status');
            $table->index(['status', 'tanggal_mulai']);
            $table->index('is_aktif');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_kegiatan');
    }
};
