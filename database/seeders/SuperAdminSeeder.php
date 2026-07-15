<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * SuperAdminSeeder
 *
 * Membuat akun super_admin awal untuk panel /pentadbir LAM Bengkalis.
 *
 * ⚠️  PENTING — Langkah WAJIB setelah seeder ini berjalan:
 *   1. Ganti password default segera setelah login pertama.
 *   2. Aktifkan 2FA di halaman profil (wajib untuk super_admin).
 *   3. Jangan jalankan seeder ini di production tanpa mengubah kredensial.
 *
 * Cara menjalankan:
 *   php artisan db:seed --class=SuperAdminSeeder
 * atau lewat DatabaseSeeder:
 *   php artisan db:seed
 */
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cegah duplikasi jika seeder dijalankan lebih dari sekali
        $existingAdmin = User::where('email', 'admin@lam-bengkalis.go.id')->first();

        if ($existingAdmin) {
            $this->command->warn('⚠️  Super admin sudah ada (email: admin@lam-bengkalis.go.id). Seeder dilewati.');
            return;
        }

        $user = User::create([
            'name'      => 'Administrator LAM Bengkalis',
            'email'     => 'admin@lam-bengkalis.go.id',
            'password'  => Hash::make('LAM@Bengkalis2025!'), // ← GANTI SEGERA SETELAH LOGIN PERTAMA
            'role'      => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Assign Spatie permission role juga (opsional, sinkronisasi dengan Filament)
        // $user->assignRole('super_admin');

        $this->command->info('');
        $this->command->info('✅ Super Admin berhasil dibuat:');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Email',    $user->email],
                ['Password', 'LAM@Bengkalis2025! ← GANTI SEGERA'],
                ['Role',     $user->role],
                ['Panel URL', url('/pentadbir/login')],
            ]
        );
        $this->command->warn('');
        $this->command->warn('🔐 LANGKAH WAJIB SETELAH LOGIN:');
        $this->command->warn('   1. Login ke ' . url('/pentadbir/login'));
        $this->command->warn('   2. Ganti password default');
        $this->command->warn('   3. Aktifkan Two-Factor Authentication (2FA) di halaman profil');
        $this->command->warn('');
    }
}
