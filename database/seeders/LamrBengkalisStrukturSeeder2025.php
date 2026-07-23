<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeder data resmi Lembaga Adat Melayu Riau (LAMR) Kabupaten Bengkalis
 * Masa Khidmat 2024–2029.
 *
 * Sumber data (dua file Excel resmi):
 *   1. "struktur MKA, DPH dan DKA.xlsx"
 *      – Sheet MKA  : 61 anggota (row 1–61, row 0 = header)
 *      – Sheet DPH  : 23 anggota (row 1–23)
 *      – Sheet DKA  :  7 anggota (row 1–7)
 *   2. "BIDANG-BIDANG PENGURUS LAMR.xlsx" – Sheet1
 *      : 16 bidang beserta seluruh anggotanya
 *
 * Ketentuan teknis:
 *   - updateOrInsert berdasarkan (nama, jabatan, kategori[, nama_bidang]) — idempotent/aman diulang
 *   - Seluruh proses dibungkus DB::transaction — atomic
 *   - foto    = null (admin akan unggah via Filament)
 *   - periode = '2024-2029'
 *   - TIDAK ada truncate() — data production aman
 *
 * Catatan verifikasi:
 *   [V1] "Datuk Idham" muncul 2x pada sheet MKA baris 40 & 45.
 *        Diasumsikan 2 individu berbeda — jabatan diberi keterangan (1) dan (2).
 *        Mohon verifikasi ke sekretariat.
 *   [V2] Ejaan "Hermizon" diambil dari sheet DKA ("struktur MKA, DPH dan DKA.xlsx")
 *        vs "Hormizon" pada file BIDANG. Seeder ini mengikuti sheet DKA yang lebih spesifik.
 *   [V3] Ejaan "Zamzami" diambil dari sheet DKA vs "Zamzani" pada file BIDANG.
 *        Seeder ini mengikuti sheet DKA yang lebih spesifik.
 */
class LamrBengkalisStrukturSeeder2025 extends Seeder
{
    /**
     * Nilai default untuk semua entri.
     */
    private const DEFAULTS = [
        'foto'    => null,
        'periode' => '2024-2029',
    ];

    public function run(): void
    {
        DB::transaction(function () {
            $this->seedPembimbing();
            $this->seedPenasehat();
            $this->seedMka();
            $this->seedDph();
            $this->seedBidang();
            $this->seedDka();
        });

        $this->command->info('✅ Seeder LamrBengkalisStrukturSeeder2025 selesai.');
        $this->command->table(
            ['Kategori', 'Jumlah'],
            DB::table('struktur_organisasi')
                ->select('kategori', DB::raw('COUNT(*) as jumlah'))
                ->groupBy('kategori')
                ->orderBy('kategori')
                ->get()
                ->map(fn ($r) => [$r->kategori, $r->jumlah])
                ->toArray()
        );
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // A. Pembimbing Utama
    // ═══════════════════════════════════════════════════════════════════════════
    private function seedPembimbing(): void
    {
        $data = [
            [
                'nama'            => 'Bupati dan Wakil Bupati Bengkalis',
                'jabatan'         => 'Payung Panji Masyarakat Adat Melayu Riau Kabupaten Bengkalis',
                'tingkat_jabatan' => 'pimpinan',
                'urutan'          => 1,
                'is_active'       => true,
            ],
        ];

        foreach ($data as $item) {
            DB::table('struktur_organisasi')->updateOrInsert(
                [
                    'nama'     => $item['nama'],
                    'jabatan'  => $item['jabatan'],
                    'kategori' => 'Pembimbing',
                ],
                array_merge(self::DEFAULTS, $item, ['kategori' => 'Pembimbing', 'nama_bidang' => null])
            );
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // B. Penasehat
    // ═══════════════════════════════════════════════════════════════════════════
    private function seedPenasehat(): void
    {
        $data = [
            ['nama' => 'Datuk H. Amril Mukminin',   'jabatan' => 'Penasehat',                           'urutan' => 1],
            ['nama' => 'Riza Pahlefi',               'jabatan' => 'Penasehat, Sri Muda Sempurna Negeri', 'urutan' => 2],
            ['nama' => 'Datuk Zainuddin Yusuf',      'jabatan' => 'Penasehat',                           'urutan' => 1],
            ['nama' => 'Datuk H. Syofyan Said',      'jabatan' => 'Penasehat',                           'urutan' => 1],
        ];

        foreach ($data as $item) {
            DB::table('struktur_organisasi')->updateOrInsert(
                [
                    'nama'     => $item['nama'],
                    'jabatan'  => $item['jabatan'],
                    'kategori' => 'Penasehat',
                ],
                array_merge(self::DEFAULTS, $item, [
                    'kategori'        => 'Penasehat',
                    'nama_bidang'     => null,
                    'tingkat_jabatan' => 'pimpinan',
                    'is_active'       => true,
                ])
            );
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // C. Majelis Kerapatan Adat (MKA)
    // Sumber: "struktur MKA, DPH dan DKA.xlsx" – Sheet MKA
    // Format: [nama, jabatan, tingkat_jabatan, urutan]
    // ═══════════════════════════════════════════════════════════════════════════
    private function seedMka(): void
    {
        $data = [
            // ── Pimpinan MKA ──────────────────────────────────────────────────
            ['Datuk Seri H. Ilham Noer',           'Ketua Umum',          'pimpinan',  1],
            ['Datuk H. Amrizal',                   'Timbalan Ketua Umum', 'pimpinan',  2],
            ['Datuk H. Yuhelmi',                   'Timbalan Ketua Umum', 'pimpinan',  2],
            ['Datuk H. Anuar Syarif',              'Ketua',               'pimpinan',  3],
            ['Datuk H. Muhammad Nasir',            'Ketua',               'pimpinan',  3],
            ['Datuk Khalifah Muhammad Darwis',     'Ketua',               'pimpinan',  3],
            ['Datuk Defitri Akbar',                'Ketua',               'pimpinan',  3],
            ['Datuk Darmansyah',                   'Sekretaris Umum',     'pimpinan',  4],
            ['Datuk H. Ramlan',                    'Sekretaris',          'pimpinan',  5],
            ['Datuk Zakir bin KH. Bukhori',        'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Syafri',                    'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Syaiful Bahri',             'Sekretaris',          'pimpinan',  5],
            ['Datuk Marzuli Ridwan Al Bantani',    'Sekretaris',          'pimpinan',  5],
            ['Datuk Agus Effendi',                 'Sekretaris',          'pimpinan',  5],

            // ── Anggota MKA ───────────────────────────────────────────────────
            ['Datuk Seri Syaukani Al Karim',       'Anggota (Ex Officio) — Ketua Umum DPH LAMR Kab. Bengkalis', 'anggota',  6],
            ['Datuk H. Abu Anwar',                 'Anggota',             'anggota',  7],
            ['Puan Nurhayati Landung',             'Anggota',             'anggota',  7],
            ['Datuk Said Dillah',                  'Anggota',             'anggota',  7],
            ['Datuk H. Sisdek',                    'Anggota',             'anggota',  7],
            ['Datuk Dahen Tawakkal',               'Anggota',             'anggota',  7],
            ['Datuk Misri Hasyim',                 'Anggota',             'anggota',  7],
            ['Datuk Sumantari',                    'Anggota',             'anggota',  7],
            ['Datuk Alimuzar',                     'Anggota',             'anggota',  7],
            ['Datuk Wan Muhammad Sabri',           'Anggota',             'anggota',  7],
            ['Datuk H. Bahlizar',                  'Anggota',             'anggota',  7],
            ['Datuk Ediarsyah',                    'Anggota',             'anggota',  7],
            ['Datuk H. Jamaluddin',                'Anggota',             'anggota',  7],
            ['Datuk H. Abdul Hamid',               'Anggota',             'anggota',  7],
            ['Datuk Naamsyah',                     'Anggota',             'anggota',  7],
            ['Datuk Syamsir',                      'Anggota',             'anggota',  7],
            ['Datuk T. Fauzi',                     'Anggota',             'anggota',  7],
            ['Datuk Abdul Kadir',                  'Anggota',             'anggota',  7],
            ['Datuk H. Rusli',                     'Anggota',             'anggota',  7],
            ['Datuk Tengku Zulkifli',              'Anggota',             'anggota',  7],
            ['Datuk Tengku Zainudin',              'Anggota',             'anggota',  7],
            ['Datuk Faizal',                       'Anggota',             'anggota',  7],
            ['Datuk H. Halim',                     'Anggota',             'anggota',  7],
            ['Datuk H. Khairul',                   'Anggota',             'anggota',  7],
            ['Datuk Muhammad Sidik',               'Anggota',             'anggota',  7],
            ['Datuk Idham',                        'Anggota (1)',          'anggota',  7],
            ['Datuk Saniin',                       'Anggota',             'anggota',  7],
            ['Datuk Abdul Wahid',                  'Anggota',             'anggota',  7],
            ['Datuk H. Syamsudin',                 'Anggota',             'anggota',  7],
            ['Datuk Wira Sugiarto',                'Anggota',             'anggota',  7],
            ['Datuk Idham',                        'Anggota (2)',          'anggota',  7],
            ['Datuk H. Rusdi Ispandi',             'Anggota',             'anggota',  7],
            ['Datuk H. Firzal Fudhoil',            'Anggota',             'anggota',  7],
            ['Datuk H. Ahmad Toha',                'Anggota',             'anggota',  7],
            ['Datuk H. Syahminan Karim',           'Anggota',             'anggota',  7],
            ['Datuk H. Nirwana Anirta',            'Anggota',             'anggota',  7],
            ['Puan Hartati Idris',                 'Anggota',             'anggota',  7],
            ['Puan Nurmala',                       'Anggota',             'anggota',  7],
            ['Puan Rahmi',                         'Anggota',             'anggota',  7],
            ['Puan Evi Maria Lavina',              'Anggota',             'anggota',  7],
            ['Puan Hj. Masyitah',                  'Anggota',             'anggota',  7],
            ['Puan Soleha',                        'Anggota',             'anggota',  7],
            ['Puan Hj. Masyitah H. Usman',         'Anggota',             'anggota',  7],
            ['Puan Deliza Aziz',                   'Anggota',             'anggota',  7],
            ['Puan Elly Kusumawati',               'Anggota',             'anggota',  7],
            ['Puan Hj. Sudarmi',                   'Anggota',             'anggota',  7],
            ['Puan Siti Mastuti',                  'Anggota',             'anggota',  7],
        ];

        foreach ($data as [$nama, $jabatan, $tingkat, $urutan]) {
            DB::table('struktur_organisasi')->updateOrInsert(
                [
                    'nama'     => $nama,
                    'jabatan'  => $jabatan,
                    'kategori' => 'MKA',
                ],
                array_merge(self::DEFAULTS, [
                    'nama'            => $nama,
                    'jabatan'         => $jabatan,
                    'kategori'        => 'MKA',
                    'nama_bidang'     => null,
                    'tingkat_jabatan' => $tingkat,
                    'urutan'          => $urutan,
                    'is_active'       => true,
                ])
            );
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // D. Dewan Pimpinan Harian (DPH)
    // Sumber: "struktur MKA, DPH dan DKA.xlsx" – Sheet DPH
    // ═══════════════════════════════════════════════════════════════════════════
    private function seedDph(): void
    {
        $data = [
            ['Datuk Seri Syaukani Al Karim',      'Ketua Umum',          'pimpinan',  1],
            ['Datuk Zummar Al Azmi',              'Timbalan Ketua Umum', 'pimpinan',  2],
            ['Datuk H. Muchlizar',                'Timbalan Ketua Umum', 'pimpinan',  2],
            ['Datuk Bukhari Rasyid',              'Timbalan Ketua Umum', 'pimpinan',  2],
            ['Datuk Rinto',                       'Ketua',               'pimpinan',  3],
            ['Datuk Rahmad',                      'Ketua',               'pimpinan',  3],
            ['Datuk H. Hambali Abdullah',         'Ketua',               'pimpinan',  3],
            ['Datuk Heri Indra Putra',            'Ketua',               'pimpinan',  3],
            ['Datuk Abdul Rahman',                'Ketua',               'pimpinan',  3],
            ['Datuk Erdila Fitriyadi',            'Ketua',               'pimpinan',  3],
            ['Datuk Abdul Vattaah',               'Sekretaris Umum',     'pimpinan',  4],
            ['Datuk Desman Hendri Saputra',       'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Syafrizal',                'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Alfakhrurrazy',            'Sekretaris',          'pimpinan',  5],
            ['Datuk Khairul Saleh',               'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Heru Wahyudi',             'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Herman Nur',               'Sekretaris',          'pimpinan',  5],
            ['Datuk Riza Zulhelmi',               'Sekretaris',          'pimpinan',  5],
            ['Datuk H. Hurri Agustianri',         'Sekretaris',          'pimpinan',  5],
            ['Puan Elmiawati Safarina',           'Sekretaris',          'pimpinan',  5],
            ['Datuk Sukma Irawan Auzar',          'Bendahara Umum',      'pimpinan',  6],
            ['Puan Devi Suryanti',                'Bendahara',           'anggota',  7],
            ['Datuk Erwan Sani',                  'Bendahara',           'anggota',  7],
        ];

        foreach ($data as [$nama, $jabatan, $tingkat, $urutan]) {
            DB::table('struktur_organisasi')->updateOrInsert(
                [
                    'nama'     => $nama,
                    'jabatan'  => $jabatan,
                    'kategori' => 'DPH',
                ],
                array_merge(self::DEFAULTS, [
                    'nama'            => $nama,
                    'jabatan'         => $jabatan,
                    'kategori'        => 'DPH',
                    'nama_bidang'     => null,
                    'tingkat_jabatan' => $tingkat,
                    'urutan'          => $urutan,
                    'is_active'       => true,
                ])
            );
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // E. Bidang-Bidang DPH (16 Bidang)
    // Sumber: "BIDANG-BIDANG PENGURUS LAMR.xlsx" – Sheet1
    // Format tiap entri: [nama, jabatan, tingkat_jabatan, urutan]
    // ═══════════════════════════════════════════════════════════════════════════
    private function seedBidang(): void
    {
        $bidangData = [
            'Bidang Organisasi dan Tata Laksana' => [
                ['Datuk Kamaruzzaman',                'Penyelaras', 'pimpinan',  1],
                ['Datuk H. Nasrun',                   'Anggota',    'anggota',  2],
                ['Datuk Jasman',                      'Anggota',    'anggota',  2],
                ['Datuk Erwan',                       'Anggota',    'anggota',  2],
                ['Datuk Reza Alfian',                 'Anggota',    'anggota',  2],
            ],

            'Bidang Penelitian dan Pengembangan' => [
                ['Datuk Jarir Amrun',                 'Penyelaras', 'pimpinan',  1],
                ['Datuk Maula Arizal',                'Anggota',    'anggota',  2],
                ['Datuk Juanda',                      'Anggota',    'anggota',  2],
                ['Datuk Panca Dharma',                'Anggota',    'anggota',  2],
                ['Datuk Saddam Dewana',               'Anggota',    'anggota',  2],
                ['Datuk H. Bimo Sucahyo',             'Anggota',    'anggota',  2],
                ["Puan Mufarro'ah",                   'Anggota',    'anggota', 12],
            ],

            'Bidang Hukum, Advokasi dan Pertanahan Hak-Hak Masyarakat Adat' => [
                ['Datuk Heriyanto',                   'Penyelaras', 'pimpinan',  1],
                ['Datuk Subri',                       'Anggota',    'anggota',  2],
                ['Datuk Ahmad Zulhum',                'Anggota',    'anggota',  2],
                ['Datuk Muhammad Fauzi',              'Anggota',    'anggota',  2],
            ],

            'Bidang Pentadbiran dan Siasah' => [
                ['Datuk Jufri AN',                    'Penyelaras', 'pimpinan',  1],
                ['Datuk Muhammad Fadhil Juncery',     'Anggota',    'anggota',  2],
                ['Datuk T. Fauzi bin Tengku Amran',   'Anggota',    'anggota',  2],
                ['Datuk Basuki Rahmad',               'Anggota',    'anggota',  2],
                ['Datuk Herman Sidik',                'Anggota',    'anggota',  2],
                ['Datuk Nurkhamsah',                  'Anggota',    'anggota',  2],
            ],

            'Bidang Hubungan Kerjasama Antar Lembaga dan Pemerintah' => [
                ['Datuk Muhammad Firdaus',            'Penyelaras', 'pimpinan',  1],
                ['Datuk Muhammad Teguh Sabarullah',   'Anggota',    'anggota',  2],
                ['Datuk Muhammad Elsa Tomisa',        'Anggota',    'anggota',  2],
                ['Puan Nirma Syaumia Kumala',         'Anggota',    'anggota',  2],
                ['Puan Agustina',                     'Anggota',    'anggota',  2],
                ['Datuk Salahuddin',                  'Anggota',    'anggota',  2],
            ],

            'Bidang Pemberdayaan Perempuan dan Perlindungan Anak' => [
                ['Puan Emilda Susmiti',               'Penyelaras', 'pimpinan',  1],
                ['Puan Susy Hartati',                 'Anggota',    'anggota',  2],
                ['Puan Khamiani',                     'Anggota',    'anggota',  2],
                ['Puan Imelda',                       'Anggota',    'anggota',  2],
                ['Puan Zaharah',                      'Anggota',    'anggota',  2],
                ['Puan Hj. Mahrani',                  'Anggota',    'anggota',  2],
                ['Puan Fitri Nelly',                  'Anggota',    'anggota',  2],
                ['Puan Ermadiana',                    'Anggota',    'anggota',  2],
                ['Puan Romadiah',                     'Anggota',    'anggota',  2],
            ],

            'Bidang Dakwah dan Keagamaan' => [
                ['Datuk H. Hambali Akbar',            'Penyelaras', 'pimpinan',  1],
                ['Datuk Muhammad Isnaini',            'Anggota',    'anggota',  2],
                ['Datuk Khairul Nizam',               'Anggota',    'anggota',  2],
                ['Datuk Sungko',                      'Anggota',    'anggota',  2],
                ['Datuk Affan Zahidi',                'Anggota',    'anggota',  2],
                ['Datuk Ridho Muhadjir',              'Anggota',    'anggota',  2],
            ],

            'Bidang Pendidikan, Pengembangan Ilmu, dan Teknologi' => [
                ['Datuk Alfansuri',                   'Penyelaras', 'pimpinan',  1],
                ['Datuk Muhammad Ikhsan',             'Anggota',    'anggota',  2],
                ['Datuk Zulkifli',                    'Anggota',    'anggota',  2],
                ['Datuk Ade Indra Suhara',            'Anggota',    'anggota',  2],
                ['Puan Khodijah Ishak',               'Anggota',    'anggota',  2],
                ['Puan Mahsuri',                      'Anggota',    'anggota',  2],
                ['Puan Hj. Kurnia Dina Lestari',      'Anggota',    'anggota',  2],
                ['Puan Hj. Nurzairina',               'Anggota',    'anggota',  2],
            ],

            'Bidang Pelestarian Adat dan Nilai Budaya' => [
                ['Datuk Zakaria',                     'Penyelaras', 'pimpinan',  1],
                ['Datuk Mashuri',                     'Anggota',    'anggota',  2],
                ['Datuk Syafrizal',                   'Anggota',    'anggota',  2],
                ['Datuk Nurrahim Suprapto',           'Anggota',    'anggota',  2],
                ['Datuk Ariyansyah Putra',            'Anggota',    'anggota',  2],
                ['Puan Evi Soraya',                   'Anggota',    'anggota',  2],
                ['Puan Lina',                         'Anggota',    'anggota',  2],
                ['Puan Azizah',                       'Anggota',    'anggota',  2],
            ],

            'Bidang Kepemudaan dan Pengembangan Sumber Daya Manusia' => [
                ['Datuk Arifin Nur Ilham',            'Penyelaras', 'pimpinan',  1],
                ['Datuk Muslimin',                    'Anggota',    'anggota',  2],
                ['Datuk Handana',                     'Anggota',    'anggota',  2],
                ['Datuk Kiki Krisdinata',             'Anggota',    'anggota',  2],
                ['Datuk Cita Roza',                   'Anggota',    'anggota',  2],
                ["Datuk Syu'ib",                      'Anggota',    'anggota', 65],
                ['Datuk Rozami',                      'Anggota',    'anggota',  2],
            ],

            'Bidang Pelestarian dan Pembinaan Seni Melayu' => [
                ['Datuk Suhaimi',                     'Penyelaras', 'pimpinan',  1],
                ['Datuk Musrial Mustafi',              'Anggota',    'anggota',  2],
                ['Datuk Baharuddin',                  'Anggota',    'anggota',  2],
                ['Datuk Oesman',                      'Anggota',    'anggota',  2],
                ['Datuk Imansyah',                    'Anggota',    'anggota',  2],
                ['Puan Eliawati',                     'Anggota',    'anggota',  2],
                ['Datuk Syahrul Ramadan',             'Anggota',    'anggota',  2],
            ],

            'Bidang Humas dan Media Massa' => [
                ['Datuk Musa Ismail',                 'Penyelaras', 'pimpinan',  1],
                ['Datuk H. Taufik',                   'Anggota',    'anggota',  2],
                ['Datuk Erwin Syahputra',             'Anggota',    'anggota',  2],
                ['Datuk Nurfizal',                    'Anggota',    'anggota',  2],
                ['Datuk T. S. M. Iqbal',              'Anggota',    'anggota',  2],
                ['Datuk Mailrizon Zainal',             'Anggota',    'anggota',  2],
                ['Datuk Bob Rizal',                   'Anggota',    'anggota',  2],
            ],

            'Bidang Lingkungan Hidup dan Sumber Daya Alam' => [
                ['Datuk Junaidi',                     'Penyelaras', 'pimpinan',  1],
                ['Datuk Ikram Ilham',                 'Anggota',    'anggota',  2],
                ['Datuk Wan Junizal al Banafaat',     'Anggota',    'anggota',  2],
                ['Datuk Mukhlis',                     'Anggota',    'anggota',  2],
                ['Datuk Muhammad Surya Hadi',         'Anggota',    'anggota',  2],
                ['Datuk Faisyal Bachri',              'Anggota',    'anggota',  2],
                ['Datuk Dodi Saputra',                'Anggota',    'anggota',  2],
            ],

            'Bidang Hubungan Pembinaan Organisasi Kemasyarakatan dan Organisasi Kemelayuan' => [
                ['Datuk Muhammad Agar',               'Penyelaras', 'pimpinan',  1],
                ['Datuk Akramuddin Nur',              'Anggota',    'anggota',  2],
                ['Datuk Firdaus Ridwan',              'Anggota',    'anggota',  2],
                ['Datuk Fauzan Azima',                'Anggota',    'anggota',  2],
                ['Datuk Meifandi',                    'Anggota',    'anggota',  2],
                ['Datuk Muhammad Alvindra',           'Anggota',    'anggota',  2],
                ['Datuk M. Agung Anugrah',            'Anggota',    'anggota',  2],
            ],

            'Bidang Ekonomi, Koperasi, Ketenagakerjaan' => [
                ['Datuk Jerri Afrianto',              'Penyelaras', 'pimpinan',  1],
                ['Datuk Maizal',                      'Anggota',    'anggota',  2],
                ['Datuk Idham bin H. Yahya',          'Anggota',    'anggota',  2],
                ['Puan Hj. Uni Zuraini',              'Anggota',    'anggota',  2],
                ['Datuk Suhandi',                     'Anggota',    'anggota',  2],
                ['Datuk Abdul Mukhti Zubair',         'Anggota',    'anggota',  2],
                ['Datuk Martias',                     'Anggota',    'anggota',  2],
            ],

            'Bidang Keamanan Kelembagaan LAMR' => [
                ['Datuk Heru Tri Wahyudi',            'Penyelaras', 'pimpinan',  1],
                ['Datuk Herman',                      'Anggota',    'anggota',  2],
                ['Datuk Aziar Aziz',                  'Anggota',    'anggota',  2],
                ['Datuk Khairul Anwar',               'Anggota',    'anggota',  2],
                ['Datuk Dasrul Rahman',               'Anggota',    'anggota',  2],
                ['Datuk Zulkhairi',                   'Anggota',    'anggota',  2],
            ],
        ];

        foreach ($bidangData as $namaBidang => $anggotaList) {
            foreach ($anggotaList as [$nama, $jabatan, $tingkat, $urutan]) {
                DB::table('struktur_organisasi')->updateOrInsert(
                    [
                        'nama'        => $nama,
                        'jabatan'     => $jabatan,
                        'kategori'    => 'Bidang',
                        'nama_bidang' => $namaBidang,
                    ],
                    array_merge(self::DEFAULTS, [
                        'nama'            => $nama,
                        'jabatan'         => $jabatan,
                        'kategori'        => 'Bidang',
                        'nama_bidang'     => $namaBidang,
                        'tingkat_jabatan' => $tingkat,
                        'urutan'          => $urutan,
                        'is_active'       => true,
                    ])
                );
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // F. Dewan Kehormatan Adat (DKA)
    // Sumber: "struktur MKA, DPH dan DKA.xlsx" – Sheet DKA
    // [V2] Ejaan "Hermizon" mengikuti sheet DKA (bukan "Hormizon" di file BIDANG)
    // [V3] Ejaan "Zamzami"  mengikuti sheet DKA (bukan "Zamzani"  di file BIDANG)
    // ═══════════════════════════════════════════════════════════════════════════
    private function seedDka(): void
    {
        $data = [
            ['Datuk Ersan Saputra',         'Ketua',           'pimpinan',  1],
            ['Datuk H. Bachrum Mansur',     'Timbalan Ketua',  'pimpinan',  2],
            ['Datuk H. Hermizon',           'Timbalan Ketua',  'pimpinan',  2], // [V2]
            ['Datuk H. Zamzami',            'Timbalan Ketua',  'pimpinan',  2],
            ['Datuk Irmi Syakif Arsalan',   'Timbalan Ketua',  'pimpinan',  2],
            ['Datuk Edi Sakura',            'Timbalan Ketua',  'pimpinan',  2],
            ['Datuk Asep Setiawan',         'Timbalan Ketua',  'pimpinan',  2],
        ];

        foreach ($data as [$nama, $jabatan, $tingkat, $urutan]) {
            DB::table('struktur_organisasi')->updateOrInsert(
                [
                    'nama'     => $nama,
                    'jabatan'  => $jabatan,
                    'kategori' => 'DKA',
                ],
                array_merge(self::DEFAULTS, [
                    'nama'            => $nama,
                    'jabatan'         => $jabatan,
                    'kategori'        => 'DKA',
                    'nama_bidang'     => null,
                    'tingkat_jabatan' => $tingkat,
                    'urutan'          => $urutan,
                    'is_active'       => true,
                ])
            );
        }
    }
}
