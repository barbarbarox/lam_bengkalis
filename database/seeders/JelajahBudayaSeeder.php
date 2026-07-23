<?php

namespace Database\Seeders;

use App\Models\JelajahBudaya;
use Illuminate\Database\Seeder;

class JelajahBudayaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Upacara Adat',       'warna' => '#1B5E20', 'url' => '/berita?q=upacara+adat', 'urutan' => 1],
            ['nama' => 'Seni & Tradisi',      'warna' => '#E65100', 'url' => '/berita?q=seni+tradisi', 'urutan' => 2],
            ['nama' => 'Pakaian Adat',        'warna' => '#1565C0', 'url' => '/berita?q=pakaian+adat', 'urutan' => 3],
            ['nama' => 'Bahasa Melayu',       'warna' => '#6A1B9A', 'url' => '/berita?q=bahasa+melayu', 'urutan' => 4],
            ['nama' => 'Kuliner Tradisional', 'warna' => '#B71C1C', 'url' => '/berita?q=kuliner', 'urutan' => 5],
            ['nama' => 'Bangunan Adat',       'warna' => '#33691E', 'url' => '/berita?q=bangunan+adat', 'urutan' => 6],
            ['nama' => 'Permainan Rakyat',    'warna' => '#E65100', 'url' => '/berita?q=permainan', 'urutan' => 7],
            ['nama' => 'Tarian Adat',         'warna' => '#880E4F', 'url' => '/berita?q=tarian', 'urutan' => 8],
        ];

        foreach ($data as $item) {
            JelajahBudaya::create($item);
        }
    }
}
