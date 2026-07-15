<?php

namespace Database\Seeders;

use App\Models\BackgroundSlide;
use App\Models\BannerIklan;
use App\Models\Berita;
use App\Models\BeritaKategori;
use App\Models\ProfilKonten;
use App\Models\SiteSetting;
use App\Models\StrukturOrganisasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to safely truncate
        Schema::disableForeignKeyConstraints();

        ProfilKonten::truncate();
        StrukturOrganisasi::truncate();
        BackgroundSlide::truncate();
        BannerIklan::truncate();
        Berita::truncate();
        BeritaKategori::truncate();

        Schema::enableForeignKeyConstraints();

        // 1. Site Setting
        SiteSetting::updateSettings([
            "nama_lembaga" => "Lembaga Adat Melayu Kabupaten Bengkalis",
            "singkatan" => "LAM Bengkalis",
            "logo_path" => "logo/01KXKBJG7990D6F9GG0VR4JSQW.png",
            "favicon_path" => "logo/01KXKBJG7BK3Y5H8EX39V1KR5G.png",
            "alamat" => "Jalan Bengkalis",
            "email_kontak" => "barbarbarox@gmail.com",
            "no_telp" => "+6281232457689",
            "facebook_url" => null,
            "instagram_url" => null,
            "youtube_url" => null,
            "twitter_url" => null,
            "meta_deskripsi" => null,
            "meta_keywords" => null,
            "teks_footer" => null,
            "embed_peta" => "<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7989.143794090537!2d102.13907266662751!3d1.4629934270362848!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d15e68216d9029%3A0x475f34d9df752e4f!2sLembaga%20Adat%20Melayu%20Riau%20Bengkalis!5e1!3m2!1sen!2sid!4v1784136895806!5m2!1sen!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"strict-origin-when-cross-origin\"></iframe>",
            "url_museum" => null,
            "tahun_berdiri" => null
        ]);

        // 2. Profil Konten
        $profilData = [
            [
                "id" => 1,
                "slug" => "sejarah-lam",
                "judul" => "Sejarah LAM Bengkalis",
                "konten" => "<p>begini</p>",
                "meta_deskripsi" => null,
                "is_active" => true
            ],
            [
                "id" => 2,
                "slug" => "visi-lam",
                "judul" => "Visi",
                "konten" => "",
                "meta_deskripsi" => null,
                "is_active" => true
            ],
            [
                "id" => 3,
                "slug" => "misi-lam",
                "judul" => "Misi",
                "konten" => "{\"dc2587f4-0f9e-4494-83f6-638eda130875\":\"begini 1\",\"27789fa4-d3a2-4ac4-84fa-418444b9bc4d\":null}",
                "meta_deskripsi" => null,
                "is_active" => true
            ],
            [
                "id" => 4,
                "slug" => "tugas-fungsi",
                "judul" => "Tugas dan Fungsi",
                "konten" => "<p>asasasas</p>",
                "meta_deskripsi" => null,
                "is_active" => true
            ],
            [
                "id" => 5,
                "slug" => "dasar-hukum",
                "judul" => "Dasar Hukum",
                "konten" => "<p>sassS</p>",
                "meta_deskripsi" => null,
                "is_active" => true
            ]
        ];
        foreach ($profilData as $data) {
            ProfilKonten::create($data);
        }

        // 3. Struktur Organisasi
        $strukturData = [
            [
                "id" => 1,
                "nama" => "barox",
                "jabatan" => "ketum",
                "kategori" => "MKA",
                "foto" => "struktur/01KXKDK07XD649XFT5K4B98NG7.gif",
                "urutan" => 0,
                "periode" => "sadlkamdlka",
                "is_active" => true
            ],
            [
                "id" => 2,
                "nama" => "abar 2",
                "jabatan" => "wakil ketum",
                "kategori" => "MKA",
                "foto" => "struktur/01KXKDKRG4PE6D807P7VME8GB5.png",
                "urutan" => 1,
                "periode" => null,
                "is_active" => true
            ],
            [
                "id" => 3,
                "nama" => "abar sasa",
                "jabatan" => "nsa nak",
                "kategori" => "DPH",
                "foto" => "struktur/01KXKDQZ3535Z58JR387DGMTZ1.png",
                "urutan" => 0,
                "periode" => null,
                "is_active" => true
            ]
        ];
        foreach ($strukturData as $data) {
            StrukturOrganisasi::create($data);
        }

        // 4. Background Slides
        $slidesData = [
            [
                "id" => 2,
                "image_path" => "slides/01KXKC2G9DQVET4GBZZS2M7X4Q.png",
                "alt_text" => "test",
                "urutan" => 0,
                "is_active" => true
            ],
            [
                "id" => 3,
                "image_path" => "slides/01KXKC38XN1SMTR5PFFDAKQQBY.svg",
                "alt_text" => "maskot test",
                "urutan" => 1,
                "is_active" => true
            ]
        ];
        foreach ($slidesData as $data) {
            BackgroundSlide::create($data);
        }

        // 5. Banners
        $bannersData = [
            [
                "id" => 4,
                "image_path" => "banners/01KXKC825KT4V8344YFWV2Q77Z.png",
                "link_url" => "https://jejaklayar.ours.web.id",
                "alt_text" => "tets",
                "urutan" => 0,
                "is_active" => true,
                "tanggal_mulai" => "2026-07-15 17:00:00",
                "tanggal_selesai" => "2026-07-17 17:00:00"
            ],
            [
                "id" => 5,
                "image_path" => "banners/01KXKDB5DY3TXCAGSQTAQDX8AM.png",
                "link_url" => "https://jejaklayar.ours.web.id",
                "alt_text" => "bola basket",
                "urutan" => 1,
                "is_active" => true,
                "tanggal_mulai" => "2026-07-15 17:00:00",
                "tanggal_selesai" => "2026-07-17 17:00:00"
            ]
        ];
        foreach ($bannersData as $data) {
            BannerIklan::create($data);
        }

        // 6. Berita Kategori
        $kategoriData = [
            [
                "id" => 1,
                "nama" => "test",
                "slug" => "test",
                "deskripsi" => null
            ],
            [
                "id" => 2,
                "nama" => "testing",
                "slug" => "testing",
                "deskripsi" => null
            ]
        ];
        foreach ($kategoriData as $data) {
            BeritaKategori::create($data);
        }

        // 7. Berita
        $beritaData = [
            [
                "id" => 1,
                "judul" => "jejaklayar",
                "slug" => "jejaklayar",
                "berita_kategori_id" => 1,
                "konten" => "<p>NADAOKMDKAMSKD</p>",
                "thumbnail" => "berita/thumbnail/01KXKB2NGP8PBZMX7WCY3KWKKK.gif",
                "excerpt" => "NADAOKMDKAMSKD",
                "status" => "published",
                "tanggal_publish" => "2026-07-15 16:51:00",
                "penulis_id" => 1,
                "jumlah_dilihat" => 2
            ],
            [
                "id" => 2,
                "judul" => "testing 2",
                "slug" => "testing-2",
                "berita_kategori_id" => 2,
                "konten" => "<p>asjalmlamldksams</p>",
                "thumbnail" => "berita/thumbnail/01KXKD5CS4Q3ZBQSPNEF8XFG03.png",
                "excerpt" => "asjalmlamldksams",
                "status" => "published",
                "tanggal_publish" => "2026-07-15 17:28:00",
                "penulis_id" => 1,
                "jumlah_dilihat" => 0
            ]
        ];
        foreach ($beritaData as $data) {
            Berita::create($data);
        }
    }
}
