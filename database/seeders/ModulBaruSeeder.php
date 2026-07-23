<?php

namespace Database\Seeders;

use App\Models\AgendaKegiatan;
use App\Models\DokumenPeraturan;
use App\Models\GelarAdat;
use App\Models\HukumAdat;
use App\Models\LamKecamatan;
use App\Models\PendidikanPelatihan;
use App\Models\TokohAdat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ModulBaruSeeder extends Seeder
{
    public function run(): void
    {
        // 1. LAM Kecamatan
        if (LamKecamatan::count() == 0) {
            LamKecamatan::create([
                'nama_kecamatan' => 'Bengkalis',
                'alamat' => 'Gedung LAMR, Jl. Pramuka, Bengkalis',
                'nama_ketua' => 'Datuk H. Ahmad',
                'no_telp' => '081234567890',
                'is_aktif' => true,
                'urutan' => 1
            ]);
            LamKecamatan::create([
                'nama_kecamatan' => 'Bantan',
                'alamat' => 'Jl. Jenderal Sudirman, Selatbaru, Bantan',
                'nama_ketua' => 'Datuk H. Ismail',
                'is_aktif' => true,
                'urutan' => 2
            ]);
        }

        // 2. Hukum Adat
        if (HukumAdat::count() == 0) {
            HukumAdat::create([
                'judul' => 'Pedoman Pelaksanaan Perkawinan Adat Melayu Bengkalis',
                'jenis' => 'keputusan',
                'nomor_dokumen' => '01/LAMR-BKS/2023',
                'tahun' => 2023,
                'ringkasan' => 'Panduan lengkap mengenai tata cara dan rukun perkawinan adat Melayu di wilayah Kabupaten Bengkalis.',
                'is_aktif' => true,
            ]);
            HukumAdat::create([
                'judul' => 'Fatwa Adat Tentang Pengelolaan Hutan Ulayat',
                'jenis' => 'fatwa_adat',
                'nomor_dokumen' => '05/FATWA/LAMR-BKS/2022',
                'tahun' => 2022,
                'ringkasan' => 'Fatwa terkait perlindungan dan pengelolaan hutan tanah ulayat menurut adat Melayu.',
                'is_aktif' => true,
            ]);
        }

        // 3. Tokoh Adat
        if (TokohAdat::count() == 0) {
            TokohAdat::create([
                'nama' => 'H. Sofyan',
                'gelar_adat' => 'Datuk Seri Setia Amanah',
                'ringkasan' => 'Tokoh budaya yang banyak berkontribusi dalam pembinaan generasi muda Melayu Bengkalis.',
                'is_aktif' => true,
            ]);
        }

        // 4. Gelar Adat
        if (GelarAdat::count() == 0) {
            GelarAdat::create([
                'nama_gelar' => 'Datuk Seri Setia Amanah',
                'jenis' => 'kehormatan',
                'tingkatan' => 'Tingkat I',
                'makna' => 'Gelar yang diberikan kepada pemimpin daerah yang setia menjaga amanah rakyat dan adat.',
                'syarat_pemberian' => 'Diberikan melalui musyawarah majelis kerapatan adat kepada tokoh pemerintahan tingkat kabupaten/provinsi.',
                'is_aktif' => true,
            ]);
            GelarAdat::create([
                'nama_gelar' => 'Datuk Setia Wangsa',
                'jenis' => 'pusaka',
                'makna' => 'Gelar pusaka warisan keluarga yang dipegang secara turun-temurun menurut garis keturunan matrilineal atau patrilineal setempat.',
                'is_aktif' => true,
            ]);
        }

        // 5. Agenda Kegiatan
        if (AgendaKegiatan::count() == 0) {
            AgendaKegiatan::create([
                'judul' => 'Majelis Balai Adat: Musyawarah Kerja Tahun 2025',
                'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(6)->format('Y-m-d'),
                'waktu_mulai' => '08:00:00',
                'lokasi' => 'Balai Kerapatan Adat Sri Mahkota, Bengkalis',
                'deskripsi' => 'Musyawarah besar tahunan pengurus LAMR se-Kabupaten Bengkalis.',
                'penyelenggara' => 'DPH LAMR Kabupaten Bengkalis',
                'is_aktif' => true,
            ]);
        }

        // 6. Dokumen Peraturan
        if (DokumenPeraturan::count() == 0) {
            DokumenPeraturan::create([
                'judul' => 'AD/ART LAMR Kabupaten Bengkalis 2025',
                'jenis' => 'ad_art',
                'nomor' => '01/AD-ART/2025',
                'tahun' => 2025,
                'deskripsi' => 'Anggaran Dasar dan Anggaran Rumah Tangga terbaru hasil Musyawarah Besar.',
                'is_aktif' => true,
            ]);
        }

        // 7. Pendidikan & Pelatihan
        if (PendidikanPelatihan::count() == 0) {
            PendidikanPelatihan::create([
                'judul' => 'Pelatihan Petatah-Petitih Adat Melayu',
                'jenis' => 'pelatihan',
                'tanggal_mulai' => now()->addDays(14)->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(16)->format('Y-m-d'),
                'deskripsi' => 'Pelatihan untuk generasi muda dalam menguasai retorika adat dan petatah-petitih acara pernikahan.',
                'kuota' => 50,
                'biaya' => 0,
                'penyelenggara' => 'LAMR Bengkalis',
                'is_aktif' => true,
            ]);
        }
    }
}
