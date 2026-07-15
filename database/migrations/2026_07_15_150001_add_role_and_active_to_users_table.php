<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambahkan kolom role dan is_active ke tabel users yang sudah ada.
 * Migration ini bersifat additive (tidak menghapus kolom existing).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'editor'])
                  ->default('editor')
                  ->after('email')
                  ->comment('Hak akses pengguna di panel admin');

            $table->boolean('is_active')
                  ->default(true)
                  ->after('role')
                  ->comment('Apakah akun aktif dan boleh login');

            // Index untuk query filter pengguna aktif berdasarkan role
            $table->index(['role', 'is_active'], 'users_role_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_active_index');
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
