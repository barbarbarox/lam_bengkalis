<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_informasi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket', 20)->unique()->comment('Nomor tiket: PI-YYYYMMDD-XXXX');
            $table->string('nama_pemohon', 200);
            $table->string('email', 200);
            $table->string('no_hp', 30)->nullable();
            $table->string('instansi', 200)->nullable();
            $table->string('jenis_informasi', 200)->comment('Kategori informasi yang diminta');
            $table->text('uraian_permohonan')->comment('Uraian lengkap informasi yang dimohon');
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('catatan_admin')->nullable()->comment('Catatan/balasan dari admin');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_informasi');
    }
};
