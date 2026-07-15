<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel berita utama.
 *
 * FK:
 *   - berita_kategori_id → berita_kategori.id (restrict delete agar kategori
 *     tidak bisa dihapus selama masih ada berita)
 *   - penulis_id          → users.id (set null jika user dihapus, agar berita
 *     tidak ikut hilang)
 *
 * Index:
 *   - slug          : lookup URL O(1)
 *   - status        : filter published/draft di halaman publik
 *   - tanggal_publish: ordering berita terbaru
 *   - composite (status, tanggal_publish): query "berita published terbaru"
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 500)->comment('Judul berita lengkap');
            $table->string('slug', 250)->unique()->comment('URL slug, misal: pelantikan-pengurus-lam-2025');
            $table->foreignId('berita_kategori_id')
                  ->constrained('berita_kategori')
                  ->restrictOnDelete()
                  ->comment('Kategori berita (tidak bisa dihapus jika masih ada berita)');
            $table->longText('konten')->comment('Isi berita HTML (sanitasi mews/purifier)');
            $table->string('thumbnail')->nullable()->comment('Path gambar thumbnail ke storage');
            $table->string('excerpt', 500)->nullable()->comment('Ringkasan berita untuk preview');
            $table->enum('status', ['draft', 'published'])->default('draft')->comment('Status publikasi');
            $table->timestamp('tanggal_publish')->nullable()->comment('Null = belum terjadwal/dipublish');
            $table->foreignId('penulis_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->comment('Editor yang menulis/mempublish berita');
            $table->unsignedBigInteger('jumlah_dilihat')->default(0)->comment('View counter');
            $table->timestamps();

            // Index untuk performa query publik
            $table->index('status', 'berita_status_index');
            $table->index('tanggal_publish', 'berita_tanggal_index');
            $table->index(['status', 'tanggal_publish'], 'berita_status_tanggal_index');
            $table->index('berita_kategori_id', 'berita_kategori_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
