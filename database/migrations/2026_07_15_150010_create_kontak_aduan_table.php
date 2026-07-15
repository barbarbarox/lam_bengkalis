<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pengaduan / kontak publik.
 *
 * Keamanan:
 *   - recaptcha_score disimpan untuk audit (reCAPTCHA v3 menghasilkan 0.0–1.0)
 *   - ip_address disimpan untuk rate-limiting dan investigasi abuse
 *   - Tidak ada FK ke users — data pengadu adalah publik (tidak perlu akun)
 *
 * Status workflow: baru → diproses → selesai
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontak_aduan', function (Blueprint $table) {
            $table->id();

            // Data pengadu
            $table->string('nama_pengadu', 200);
            $table->string('email', 200);
            $table->string('no_telp', 30)->nullable();
            $table->string('subjek', 300);
            $table->longText('isi_aduan')->comment('Isi aduan/pesan dari publik');

            // Workflow admin
            $table->enum('status', ['baru', 'diproses', 'selesai'])
                  ->default('baru')
                  ->comment('Status penanganan aduan');
            $table->text('catatan_admin')->nullable()->comment('Catatan internal admin, tidak tampil ke publik');

            // Keamanan & Audit
            $table->decimal('recaptcha_score', 3, 2)
                  ->default(0.00)
                  ->comment('Skor reCAPTCHA v3 (0.0=bot, 1.0=manusia)');
            $table->string('ip_address', 45)->comment('IPv4 atau IPv6 pengirim');

            $table->timestamps();

            // Index untuk query admin: filter per status, audit per IP
            $table->index('status', 'aduan_status_index');
            $table->index('ip_address', 'aduan_ip_index');
            $table->index('created_at', 'aduan_created_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontak_aduan');
    }
};
