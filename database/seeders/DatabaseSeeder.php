<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Urutan pemanggilan penting — jangan ubah urutan!
     * SiteSettingSeeder tidak diperlukan karena singleton sudah diisi via migration.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            JelajahBudayaSeeder::class,
            LamrBengkalisStrukturSeeder2025::class,
            ModulBaruSeeder::class,
        ]);
    }
}
