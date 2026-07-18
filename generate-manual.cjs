/**
 * generate-manual.js
 * Script ini menghasilkan dokumen Word (.docx) berupa:
 * "Petunjuk Penggunaan Web App LAM Bengkalis"
 *
 * Cara menjalankan:
 *   node generate-manual.cjs
 *
 * Output:
 *   ./Petunjuk_Penggunaan_LAM_Bengkalis.docx
 */
'use strict';

const fs   = require('fs');
const path = require('path');
const {
  Document, Packer, Paragraph, TextRun, HeadingLevel,
  AlignmentType, PageBreak, TableOfContents,
  Header, Footer, ImageRun, Table, TableRow, TableCell,
  WidthType, BorderStyle, ShadingType, convertInchesToTwip,
  LevelFormat, PageNumber,
} = require('docx');

// ─── Konstanta Warna Merek LAM ───────────────────────────────────────────────
const C = {
  GOLD   : 'F99522',
  GREEN  : '006600',
  BLACK  : '121212',
  WHITE  : 'FFFFFF',
  RED    : 'EB2D3A',
  GREY   : '555555',
  LGREY  : 'DDDDDD',
  BG_GOLD: 'FFF8ED',
  BG_GRN : 'E6F4EC',
  BG_RED : 'FFF0F0',
  BG_BLU : 'EEF4FF',
};

// ─── Logo ────────────────────────────────────────────────────────────────────
const LOGO_PATH = path.join(__dirname, 'public', 'images', 'logo-lam.gif');
let logoBuffer = null;
try {
  logoBuffer = fs.readFileSync(LOGO_PATH);
  console.log('✅ logo-lam.gif ditemukan.');
} catch (e) {
  console.warn('⚠ logo-lam.gif tidak ditemukan di public/images/. Logo akan diganti placeholder teks.');
}

function makeLogo(w, h) {
  if (!logoBuffer) return null;
  return new ImageRun({ data: logoBuffer, transformation: { width: w, height: h }, type: 'gif' });
}

// ─── Helpers ─────────────────────────────────────────────────────────────────
function spacer(n) {
  return Array.from({ length: n || 1 }, () => new Paragraph({ text: '', spacing: { after: 80 } }));
}

function body(text, opts) {
  opts = opts || {};
  return new Paragraph({
    children: [new TextRun({ text, font: 'Calibri', size: opts.size || 22, color: opts.color || '222222', bold: opts.bold || false, italics: opts.italic || false })],
    spacing: { before: opts.before || 40, after: opts.after || 80 },
    alignment: opts.align || AlignmentType.JUSTIFIED,
  });
}

function h1(text) { return new Paragraph({ text, heading: HeadingLevel.HEADING_1, spacing: { before: 360, after: 160 } }); }
function h2(text) { return new Paragraph({ text, heading: HeadingLevel.HEADING_2, spacing: { before: 240, after: 120 } }); }
function h3(text) { return new Paragraph({ text, heading: HeadingLevel.HEADING_3, spacing: { before: 160, after: 80 } }); }

function bullet(text, level, opts) {
  level = level || 0; opts = opts || {};
  return new Paragraph({
    children: [new TextRun({ text, font: 'Calibri', size: opts.size || 22, color: opts.color || '222222', bold: opts.bold || false })],
    bullet: { level },
    spacing: { before: 40, after: 60 },
  });
}

function numbered(text, level) {
  level = level || 0;
  return new Paragraph({
    children: [new TextRun({ text, font: 'Calibri', size: 22, color: '222222' })],
    numbering: { reference: 'main-numbering', level },
    spacing: { before: 40, after: 60 },
  });
}

function infoBox(label, text, bgColor, borderColor) {
  bgColor = bgColor || 'FFF8ED'; borderColor = borderColor || 'F99522';
  return new Table({
    width: { size: 100, type: WidthType.PERCENTAGE },
    margins: { bottom: convertInchesToTwip(0.1) },
    rows: [new TableRow({
      children: [
        new TableCell({
          width: { size: 8, type: WidthType.PERCENTAGE },
          shading: { type: ShadingType.CLEAR, fill: borderColor },
          margins: { top: convertInchesToTwip(0.08), bottom: convertInchesToTwip(0.08), left: convertInchesToTwip(0.08), right: convertInchesToTwip(0.05) },
          borders: { top: {style:BorderStyle.NONE}, bottom:{style:BorderStyle.NONE}, left:{style:BorderStyle.NONE}, right:{style:BorderStyle.NONE} },
          children: [new Paragraph({ children: [new TextRun({ text: label, bold:true, color:C.WHITE, font:'Calibri', size:16 })], alignment: AlignmentType.CENTER })],
        }),
        new TableCell({
          width: { size: 92, type: WidthType.PERCENTAGE },
          shading: { type: ShadingType.CLEAR, fill: bgColor },
          margins: { top: convertInchesToTwip(0.1), bottom: convertInchesToTwip(0.1), left: convertInchesToTwip(0.15), right: convertInchesToTwip(0.1) },
          borders: { top: {style:BorderStyle.NONE}, bottom:{style:BorderStyle.NONE}, left:{style:BorderStyle.NONE}, right:{style:BorderStyle.NONE} },
          children: [new Paragraph({ children: [new TextRun({ text, font:'Calibri', size:20, color:'333333' })] })],
        }),
      ],
    })],
  });
}

function dataTable(rows, header) {
  var headerRow = (header && header.length)
    ? [new TableRow({ tableHeader: true, children: header.map(function(h) { return new TableCell({
        shading: { type: ShadingType.CLEAR, fill: C.GREEN },
        margins: { top: convertInchesToTwip(0.07), bottom: convertInchesToTwip(0.07), left: convertInchesToTwip(0.1), right: convertInchesToTwip(0.1) },
        children: [new Paragraph({ children: [new TextRun({ text: h, bold:true, color:C.WHITE, font:'Calibri', size:20 })] })],
      }); }) })]
    : [];
  var dataRows = rows.map(function(row, i) {
    return new TableRow({ children: row.map(function(cell) { return new TableCell({
      shading: { type: ShadingType.CLEAR, fill: i%2===0 ? 'FFFFFF' : 'F8F8F8' },
      margins: { top: convertInchesToTwip(0.06), bottom: convertInchesToTwip(0.06), left: convertInchesToTwip(0.1), right: convertInchesToTwip(0.1) },
      borders: { top:{style:BorderStyle.SINGLE,size:1,color:C.LGREY}, bottom:{style:BorderStyle.SINGLE,size:1,color:C.LGREY}, left:{style:BorderStyle.SINGLE,size:1,color:C.LGREY}, right:{style:BorderStyle.SINGLE,size:1,color:C.LGREY} },
      children: [new Paragraph({ children: [new TextRun({ text: cell, font:'Calibri', size:20, color:'222222' })] })],
    }); }) });
  });
  return new Table({ width: { size: 100, type: WidthType.PERCENTAGE }, rows: headerRow.concat(dataRows) });
}

function pageBreak() { return new Paragraph({ children: [new PageBreak()] }); }

function divider() {
  return new Paragraph({
    border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: C.GOLD, space: 1 } },
    spacing: { before: 120, after: 120 },
    text: '',
  });
}

// ─────────────────────────────────────────────────────────────────────────────
// COVER
// ─────────────────────────────────────────────────────────────────────────────
function buildCover() {
  var children = [];
  if (logoBuffer) {
    children.push(new Paragraph({ children: [makeLogo(160, 160)], alignment: AlignmentType.CENTER, spacing: { before: 1440, after: 360 } }));
  } else {
    children = children.concat(spacer(7));
    children.push(new Paragraph({ children: [new TextRun({ text: '[LOGO LAM BENGKALIS]', bold: true, color: C.GOLD, size: 28, font: 'Calibri' })], alignment: AlignmentType.CENTER, spacing: { before: 0, after: 120 } }));
    children.push(new Paragraph({ children: [new TextRun({ text: '(Letakkan logo-lam.gif di public/images/ lalu jalankan ulang script)', italic: true, color: C.RED, size: 18, font: 'Calibri' })], alignment: AlignmentType.CENTER, spacing: { after: 240 } }));
  }
  children.push(new Paragraph({ border: { bottom: { style: BorderStyle.SINGLE, size: 12, color: C.GOLD } }, spacing: { before: 0, after: 160 }, text: '' }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'PETUNJUK PENGGUNAAN', bold: true, size: 56, font: 'Calibri', color: C.GREEN })], alignment: AlignmentType.CENTER, spacing: { before: 160, after: 80 } }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'WEBSITE RESMI', bold: true, size: 44, font: 'Calibri', color: C.BLACK })], alignment: AlignmentType.CENTER, spacing: { after: 80 } }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'LEMBAGA ADAT MELAYU', bold: true, size: 44, font: 'Calibri', color: C.GOLD })], alignment: AlignmentType.CENTER, spacing: { after: 80 } }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'KABUPATEN BENGKALIS', bold: true, size: 44, font: 'Calibri', color: C.GOLD })], alignment: AlignmentType.CENTER, spacing: { after: 200 } }));
  children.push(new Paragraph({ border: { top: { style: BorderStyle.SINGLE, size: 12, color: C.GOLD } }, spacing: { before: 0, after: 240 }, text: '' }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'Dokumen Resmi Panduan Penggunaan Sistem Informasi', italic: true, size: 22, font: 'Calibri', color: C.GREY })], alignment: AlignmentType.CENTER, spacing: { after: 120 } }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'Versi 1.0  —  Tahun 2026', size: 22, font: 'Calibri', color: C.GREY })], alignment: AlignmentType.CENTER, spacing: { after: 480 } }));
  children.push(new Paragraph({ border: { top: { style: BorderStyle.SINGLE, size: 6, color: C.LGREY } }, spacing: { before: 0, after: 80 }, text: '' }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'Lembaga Adat Melayu Kabupaten Bengkalis', bold: true, size: 20, font: 'Calibri', color: C.GREEN })], alignment: AlignmentType.CENTER, spacing: { after: 40 } }));
  children.push(new Paragraph({ children: [new TextRun({ text: 'Kabupaten Bengkalis, Riau, Indonesia', size: 18, font: 'Calibri', color: C.GREY })], alignment: AlignmentType.CENTER, spacing: { after: 40 } }));
  children.push(pageBreak());
  return children;
}

// ─────────────────────────────────────────────────────────────────────────────
// KATA PENGANTAR
// ─────────────────────────────────────────────────────────────────────────────
function buildPengantar() {
  return [
    h1('KATA PENGANTAR'), divider(),
    body('Puji syukur kami panjatkan ke hadirat Allah SWT atas rahmat dan hidayah-Nya sehingga dokumen Petunjuk Penggunaan Website Resmi Lembaga Adat Melayu (LAM) Kabupaten Bengkalis ini dapat diselesaikan dengan baik.'),
    body('Dokumen ini disusun sebagai panduan lengkap bagi seluruh pengguna yang berinteraksi dengan website resmi LAM Bengkalis, baik pengunjung umum maupun administrator pengelola konten. Panduan ini mencakup seluruh fitur, mekanisme keamanan, dan prosedur penanganan kesalahan yang tersedia di dalam sistem.'),
    body('Website LAM Bengkalis dibangun menggunakan teknologi modern berbasis Laravel dengan panel administrasi Filament, dirancang dengan memperhatikan keamanan, kemudahan penggunaan, serta keindahan tampilan yang mencerminkan nilai-nilai budaya Melayu Bengkalis.'),
    body('Kami berharap dokumen ini dapat menjadi referensi yang bermanfaat dan memudahkan seluruh pihak dalam memanfaatkan website ini secara optimal.'),
  ].concat(spacer(2)).concat([
    new Paragraph({ children: [new TextRun({ text: 'Bengkalis, 2026', font: 'Calibri', size: 22, color: '222222' })], alignment: AlignmentType.RIGHT, spacing: { before: 240, after: 80 } }),
    new Paragraph({ children: [new TextRun({ text: 'Tim Pengembang dan Pengelola Sistem', font: 'Calibri', size: 22, italic: true, color: '222222' })], alignment: AlignmentType.RIGHT, spacing: { after: 40 } }),
    new Paragraph({ children: [new TextRun({ text: 'Lembaga Adat Melayu Kabupaten Bengkalis', font: 'Calibri', size: 22, bold: true, color: C.GREEN })], alignment: AlignmentType.RIGHT, spacing: { after: 40 } }),
    pageBreak(),
  ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// DAFTAR ISI
// ─────────────────────────────────────────────────────────────────────────────
function buildToc() {
  return [
    h1('DAFTAR ISI'), divider(),
    new TableOfContents('Daftar Isi', { hyperlink: true, headingStyleRange: '1-3' }),
    pageBreak(),
  ];
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 1: PENDAHULUAN
// ─────────────────────────────────────────────────────────────────────────────
function buildBab1() {
  return [
    h1('BAB 1 — PENDAHULUAN'), divider(),
    h2('1.1 Latar Belakang'),
    body('Website resmi Lembaga Adat Melayu (LAM) Kabupaten Bengkalis adalah sistem informasi berbasis web yang dibangun untuk mendukung kegiatan publikasi, dokumentasi, dan komunikasi lembaga kepada masyarakat luas. Sistem ini dirancang untuk memberikan akses mudah kepada masyarakat terhadap informasi kelembagaan, berita terkini, dokumentasi kegiatan, serta sarana pengaduan langsung dengan lembaga.'),
    h2('1.2 Tujuan Dokumen'),
    body('Dokumen ini bertujuan untuk:'),
    bullet('Memberikan panduan lengkap penggunaan website bagi pengunjung umum.'),
    bullet('Memberikan panduan administrasi dan pengelolaan konten bagi administrator sistem.'),
    bullet('Menjelaskan mekanisme keamanan dan otentikasi yang diimplementasikan.'),
    bullet('Menyediakan panduan penanganan error dan troubleshooting.'),
    bullet('Menjadi referensi resmi bagi seluruh pihak yang terlibat dalam pengelolaan sistem.'),
    h2('1.3 Ruang Lingkup'),
    body('Dokumen ini mencakup: panduan penggunaan halaman publik, panduan panel administrasi, mekanisme login dan 2FA, manajemen pengguna dan hak akses (RBAC), pengelolaan seluruh konten website, penanganan halaman error, dan panduan troubleshooting.'),
    h2('1.4 Teknologi yang Digunakan'),
  ].concat(spacer(1)).concat([
    dataTable([
      ['Framework Backend',  'Laravel 12.x (PHP)'],
      ['Panel Administrasi', 'Filament v3'],
      ['Database',           'MySQL / MariaDB'],
      ['Frontend',           'Blade Template + Alpine.js'],
      ['Autentikasi 2FA',    'Laragear TwoFactor'],
      ['Keamanan Form',      'Google reCAPTCHA v3 (threshold 0.5)'],
      ['Font',               'Playfair Display + Inter (Google Fonts)'],
      ['PWA',                'Progressive Web App (manifest.json + Service Worker)'],
    ], ['Komponen', 'Detail']),
  ]).concat(spacer(1)).concat([
    h2('1.5 Struktur URL Penting'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['/',               'Halaman Beranda (publik)'],
      ['/profil',         'Profil Lembaga (publik)'],
      ['/berita',         'Daftar Berita (publik)'],
      ['/berita/{slug}',  'Detail Berita (publik)'],
      ['/galeri',         'Galeri Foto (publik)'],
      ['/kontak',         'Kontak & Aduan (publik)'],
      ['/museum',         'Redirect ke Museum Digital (publik)'],
      ['/pentadbir',      'Panel Admin (login wajib)'],
      ['/admin',          '⛔ Diblokir — Tampilkan 404 (fitur keamanan)'],
    ], ['URL', 'Deskripsi']),
  ]).concat(spacer(1)).concat([
    infoBox('ℹ INFO', 'URL panel admin sengaja menggunakan kata "/pentadbir" (bukan "/admin") sebagai langkah keamanan. URL /admin diblokir dan menampilkan halaman 404 — ini adalah fitur keamanan, bukan bug.', C.BG_GOLD, C.GOLD),
  ]).concat(spacer(1)).concat([pageBreak()]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 2: PANDUAN PENGUNJUNG UMUM
// ─────────────────────────────────────────────────────────────────────────────
function buildBab2() {
  var items = [
    h1('BAB 2 — PANDUAN PENGGUNA UMUM (PUBLIK)'), divider(),
    body('Bagian ini menjelaskan cara menggunakan setiap halaman yang tersedia untuk pengunjung umum tanpa memerlukan login.'),
    h2('2.1 Halaman Beranda (/)'),
    body('Halaman beranda adalah halaman utama yang pertama kali dilihat pengunjung. Halaman ini menampilkan:'),
    bullet('Hero Section — banner gambar utama dengan animasi dan tagline lembaga.'),
    bullet('Sambutan Ketua BPH — pesan sambutan dari pimpinan lembaga.'),
    bullet('Berita Terkini — rangkuman berita-berita terbaru yang diterbitkan.'),
    bullet('Layanan Lembaga — informasi layanan yang disediakan LAM Bengkalis.'),
    bullet('Galeri Singkat — tampilan foto-foto kegiatan terkini.'),
    bullet('Akses Cepat — tombol navigasi ke halaman-halaman penting.'),
  ].concat(spacer(1)).concat([
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN BERANDA DI SINI]\nURL: https://[domain-anda]/\nResolusi disarankan: 1280×800 piksel atau lebih.', C.BG_BLU, '3B82F6'),
  ]).concat(spacer(1)).concat([
    h3('2.1.1 Mode Tampilan Terang/Gelap'),
    body('Website mendukung dua mode tampilan: Mode Terang (Light Mode) dan Mode Gelap (Dark Mode). Klik ikon 🌙/☀️ di pojok kanan atas navbar untuk beralih mode. Pengaturan tersimpan di browser secara permanen.'),
    h3('2.1.2 Fitur PWA (Progressive Web App)'),
    body('Website dapat diinstal sebagai aplikasi:'),
    numbered('Di browser Chrome/Edge: klik ikon "Install" di address bar.'),
    numbered('Di Android: pilih "Add to Home Screen" dari menu browser.'),
    numbered('Di iPhone/iPad: buka di Safari → Share → "Add to Home Screen".'),
    h2('2.2 Halaman Profil (/profil)'),
    body('Menampilkan informasi resmi tentang LAM Bengkalis: sejarah pendirian, visi dan misi, struktur organisasi, serta tugas pokok dan fungsi lembaga.'),
  ]).concat(spacer(1)).concat([
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN PROFIL DI SINI]\nURL: https://[domain-anda]/profil', C.BG_BLU, '3B82F6'),
  ]).concat(spacer(1)).concat([
    h2('2.3 Halaman Berita (/berita)'),
    h3('2.3.1 Daftar Berita'),
    body('Menampilkan daftar berita dengan kartu (thumbnail, judul, tanggal, ringkasan), filter berdasarkan kategori, pencarian kata kunci, dan navigasi halaman (pagination).'),
    h3('2.3.2 Detail Berita'),
    body('Klik judul atau "Baca Selengkapnya" untuk membuka detail berita. Menampilkan: judul, tanggal terbit, nama penulis, gambar utama, konten lengkap, dan tombol navigasi berita sebelumnya/berikutnya.'),
    h2('2.4 Halaman Galeri Foto (/galeri)'),
    body('Menampilkan koleksi foto dokumentasi kegiatan dalam format grid responsif. Klik foto untuk melihat tampilan penuh (lightbox viewer). Foto diurutkan berdasarkan tanggal upload terbaru.'),
  ]).concat(spacer(1)).concat([
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN GALERI DI SINI]\nURL: https://[domain-anda]/galeri', C.BG_BLU, '3B82F6'),
  ]).concat(spacer(1)).concat([
    h2('2.5 Halaman Kontak & Aduan (/kontak)'),
    h3('2.5.1 Cara Mengirim Pesan'),
    numbered('Buka halaman Kontak dari menu navigasi.'),
    numbered('Isi formulir: Nama Lengkap (wajib, maks. 200 karakter), Email (wajib, format valid), Nomor Telepon (opsional), Subjek (wajib, maks. 300 karakter), Isi Pesan (wajib, maks. 5.000 karakter).'),
    numbered('Centang verifikasi reCAPTCHA (jika diminta).'),
    numbered('Klik tombol "Kirim Pesan" dan tunggu konfirmasi.'),
  ]).concat(spacer(1)).concat([
    infoBox('⚠ PERHATIAN', 'Formulir dibatasi maksimal 5 pengiriman per menit dari satu alamat IP untuk mencegah spam. Jika melebihi batas, tunggu 1 menit sebelum mencoba kembali.', C.BG_GOLD, C.GOLD),
  ]).concat(spacer(1)).concat([
    h2('2.6 Museum Digital (/museum)'),
    body('Halaman ini melakukan pengalihan otomatis ke platform Museum Digital LAM Bengkalis. Jika URL belum dikonfigurasi administrator, pengunjung akan diarahkan ke halaman beranda.'),
    pageBreak(),
  ]);
  return items;
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 3: LOGIN & KEAMANAN ADMIN
// ─────────────────────────────────────────────────────────────────────────────
function buildBab3() {
  return [
    h1('BAB 3 — MEKANISME LOGIN & KEAMANAN ADMIN'), divider(),
    h2('3.1 Gambaran Umum Sistem Keamanan'),
    body('Panel administrasi website LAM Bengkalis dilindungi oleh sistem keamanan berlapis:'),
    bullet('URL tersembunyi — panel admin menggunakan URL /pentadbir (bukan /admin).'),
    bullet('Autentikasi berbasis email dan kata sandi (bcrypt hashed).'),
    bullet('Two-Factor Authentication (2FA) wajib untuk Super Admin.'),
    bullet('Status akun aktif/nonaktif — hanya akun aktif yang dapat masuk.'),
    bullet('Pemblokiran URL legacy — /admin mengembalikan error 404 secara silent.'),
    h2('3.2 Mengakses Panel Admin'),
    body('Panel admin hanya dapat diakses melalui URL:'),
    new Paragraph({ children: [new TextRun({ text: '    https://[domain-anda]/pentadbir', font: 'Courier New', size: 22, bold: true, color: C.GREEN })], spacing: { before: 80, after: 80 } }),
    infoBox('🔒 KEAMANAN', 'Jangan pernah mencoba mengakses /admin. URL tersebut diblokir dan dikembalikan sebagai 404. Selalu gunakan /pentadbir untuk masuk ke panel admin.', C.BG_RED, C.RED),
  ].concat(spacer(1)).concat([
    h2('3.3 Proses Login — Langkah demi Langkah'),
    h3('3.3.1 Login Pertama Kali (Semua Role)'),
    numbered('Buka browser dan navigasi ke: https://[domain-anda]/pentadbir'),
    numbered('Halaman login Filament akan ditampilkan.'),
    numbered('Masukkan Alamat Email yang terdaftar di sistem.'),
    numbered('Masukkan Kata Sandi akun Anda.'),
    numbered('Klik tombol "Sign In" / "Masuk".'),
    numbered('Sistem akan memverifikasi kredensial Anda.'),
  ]).concat(spacer(1)).concat([
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN LOGIN /pentadbir DI SINI]\nURL: https://[domain-anda]/pentadbir/login', C.BG_BLU, '3B82F6'),
  ]).concat(spacer(1)).concat([
    h3('3.3.2 Mekanisme Verifikasi Login'),
    body('Saat login, sistem melakukan pengecekan berurutan:'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Langkah 1', 'Verifikasi email — apakah email terdaftar di database.'],
      ['Langkah 2', 'Verifikasi kata sandi — pencocokan hash bcrypt.'],
      ['Langkah 3', 'Verifikasi status aktif — kolom is_active harus bernilai true.'],
      ['Langkah 4', 'Cek role pengguna — Super Admin atau Editor.'],
      ['Langkah 5', '(Super Admin) Cek status 2FA — wajib diaktifkan sebelum akses panel.'],
      ['Langkah 6', 'Redirect ke dashboard setelah semua verifikasi lulus.'],
    ], ['Tahap', 'Deskripsi']),
  ]).concat(spacer(1)).concat([
    h2('3.4 Two-Factor Authentication (2FA)'),
    body('Two-Factor Authentication (2FA) adalah lapisan keamanan tambahan yang mengharuskan pengguna memverifikasi identitas menggunakan kode OTP (One-Time Password) 6 digit dari aplikasi authenticator di ponsel.'),
    h3('3.4.1 Siapa yang Wajib Menggunakan 2FA?'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Super Admin', '✅ WAJIB — Tidak bisa mengakses panel sama sekali sebelum 2FA diaktifkan.'],
      ['Editor',      '⚙️ Opsional — Sangat direkomendasikan tetapi tidak dipaksakan sistem.'],
    ], ['Role', 'Kewajiban 2FA']),
  ]).concat(spacer(1)).concat([
    h3('3.4.2 Cara Mengaktifkan 2FA (untuk Super Admin)'),
    numbered('Login dengan email dan kata sandi Super Admin.'),
    numbered('Sistem otomatis mengarahkan ke: /pentadbir/two-factor-setup'),
    numbered('Instal aplikasi authenticator di ponsel: Google Authenticator, Microsoft Authenticator, atau Authy.'),
    numbered('Pindai kode QR yang ditampilkan menggunakan aplikasi authenticator.'),
    numbered('Masukkan kode 6 digit dari aplikasi ke kolom verifikasi.'),
    numbered('Klik "Aktifkan 2FA" untuk menyelesaikan setup.'),
    numbered('SIMPAN kode pemulihan (recovery codes) di tempat yang aman!'),
  ]).concat(spacer(1)).concat([
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN SETUP 2FA DI SINI]\nURL: https://[domain-anda]/pentadbir/two-factor-setup', C.BG_BLU, '3B82F6'),
    infoBox('⚠ PENTING', 'Simpan kode pemulihan (recovery codes) yang ditampilkan saat setup 2FA! Kode ini digunakan jika Anda kehilangan akses ke aplikasi authenticator. Tanpa kode pemulihan, akun Super Admin tidak dapat dipulihkan tanpa akses langsung ke database server.', C.BG_RED, C.RED),
  ]).concat(spacer(1)).concat([
    h3('3.4.3 Login dengan 2FA Aktif'),
    numbered('Masukkan email dan kata sandi seperti biasa.'),
    numbered('Sistem akan meminta kode OTP 6 digit.'),
    numbered('Buka aplikasi authenticator di ponsel.'),
    numbered('Masukkan kode 6 digit yang ditampilkan (berlaku 30 detik).'),
    numbered('Klik "Verifikasi" — jika benar, Anda masuk ke dashboard admin.'),
    infoBox('ℹ INFO', 'Kode OTP hanya berlaku selama 30 detik. Jika kode sudah expired, tunggu kode baru muncul di aplikasi authenticator sebelum memasukkannya.', C.BG_GOLD, C.GOLD),
  ]).concat(spacer(1)).concat([
    h2('3.5 Peran dan Hak Akses (Role-Based Access Control)'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Super Admin', 'Akses penuh: manajemen pengguna, pengaturan situs, semua konten, semua laporan.'],
      ['Editor',      'Akses terbatas: berita, galeri, profil, layanan, struktur organisasi, sambutan BPH.'],
    ], ['Role', 'Hak Akses']),
  ]).concat(spacer(1)).concat([
    h3('3.5.1 Fitur Khusus Super Admin'),
    bullet('Manajemen Pengguna — membuat, mengedit, menonaktifkan akun admin/editor.'),
    bullet('Pengaturan Situs Global — nama lembaga, logo, kontak, media sosial.'),
    bullet('Pengaturan Hero/Banner — gambar banner di setiap halaman publik.'),
    bullet('Manajemen Banner Iklan — banner pengumuman di halaman publik.'),
    bullet('Manajemen Background Slide — slide gambar beranda.'),
    bullet('Melihat Semua Aduan Kontak — membaca dan memproses pesan pengunjung.'),
    h3('3.5.2 Fitur yang Dapat Diakses Editor'),
    bullet('Manajemen Berita — membuat, mengedit, mempublikasikan, menghapus.'),
    bullet('Manajemen Galeri — upload dan mengelola foto galeri.'),
    bullet('Manajemen Profil Konten — mengedit konten halaman profil lembaga.'),
    bullet('Manajemen Layanan — mengelola informasi layanan lembaga.'),
    bullet('Manajemen Struktur Organisasi — memperbarui data kepengurusan.'),
    bullet('Sambutan BPH — mengedit teks sambutan pimpinan.'),
    h2('3.6 Logout (Keluar dari Sistem)'),
    numbered('Klik avatar/nama akun di pojok kanan atas panel admin.'),
    numbered('Pilih menu "Sign Out" / "Keluar".'),
    numbered('Sesi login dihentikan dan Anda diarahkan ke halaman login.'),
    infoBox('🔒 KEAMANAN', 'Selalu lakukan logout setelah selesai menggunakan panel admin, terutama jika menggunakan komputer bersama. Jangan biarkan sesi admin terbuka tanpa pengawasan.', C.BG_RED, C.RED),
    pageBreak(),
  ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 4: PANEL ADMIN — PENGELOLAAN KONTEN
// ─────────────────────────────────────────────────────────────────────────────
function buildBab4() {
  return [
    h1('BAB 4 — PANDUAN PANEL ADMINISTRASI'), divider(),
    h2('4.1 Dashboard Admin'),
    body('Setelah login berhasil, Anda akan diarahkan ke dashboard yang menampilkan ringkasan statistik dan akses cepat ke semua fitur pengelolaan konten.'),
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN DASHBOARD ADMIN DI SINI]\nURL: https://[domain-anda]/pentadbir', C.BG_BLU, '3B82F6'),
  ].concat(spacer(1)).concat([
    h2('4.2 Manajemen Berita'),
    h3('4.2.1 Membuat Berita Baru'),
    numbered('Klik menu "Berita" di sidebar panel admin.'),
    numbered('Klik tombol "+ Tambah Berita" di kanan atas.'),
    numbered('Isi formulir: Judul, Kategori, Konten (rich text editor), Gambar Utama, Status (Draft/Terbit), Tanggal Terbit.'),
    numbered('Klik "Simpan" untuk menyimpan berita.'),
    infoBox('ℹ INFO', 'Slug URL berita dibuat otomatis dari judul. Pastikan judul berita unik agar tidak terjadi konflik URL.', C.BG_GOLD, C.GOLD),
  ]).concat(spacer(1)).concat([
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR FORMULIR TAMBAH BERITA DI SINI]', C.BG_BLU, '3B82F6'),
  ]).concat(spacer(1)).concat([
    h3('4.2.2 Mengedit Berita'),
    numbered('Di daftar berita, klik tombol ✏️ (Edit) pada berita yang ingin diubah.'),
    numbered('Lakukan perubahan yang diperlukan.'),
    numbered('Klik "Simpan Perubahan".'),
    h3('4.2.3 Menghapus Berita'),
    numbered('Di daftar berita, klik tombol 🗑️ (Hapus).'),
    numbered('Konfirmasi penghapusan pada dialog yang muncul.'),
    infoBox('⚠ PERHATIAN', 'Penghapusan berita bersifat PERMANEN dan tidak dapat dibatalkan. Pastikan berita yang akan dihapus memang tidak diperlukan lagi.', C.BG_RED, C.RED),
  ]).concat(spacer(1)).concat([
    h3('4.2.4 Manajemen Kategori Berita'),
    body('Kategori berita dikelola melalui menu "Kategori Berita" di sidebar. Anda dapat menambah, mengedit, atau menghapus kategori sesuai kebutuhan.'),
    h2('4.3 Manajemen Galeri Foto'),
    h3('4.3.1 Upload Foto Baru'),
    numbered('Klik menu "Galeri" di sidebar admin.'),
    numbered('Klik tombol "+ Tambah Foto".'),
    numbered('Isi: Judul Foto, upload File Foto (JPG/PNG/WebP), Keterangan (opsional).'),
    numbered('Klik "Simpan".'),
    infoBox('ℹ INFO', 'Ukuran file foto disarankan tidak lebih dari 5 MB dengan resolusi maksimal 2000×2000 piksel untuk performa optimal.', C.BG_GOLD, C.GOLD),
  ]).concat(spacer(1)).concat([
    h2('4.4 Manajemen Profil Konten'),
    body('Konten halaman profil (sejarah, visi, misi, dll.) dikelola melalui menu "Profil Konten". Pilih bagian yang ingin diedit, perbarui konten, lalu simpan.'),
    h2('4.5 Manajemen Struktur Organisasi'),
    numbered('Klik "Struktur Organisasi" di sidebar.'),
    numbered('Tambah atau edit data pengurus: nama, jabatan, foto.'),
    numbered('Atur urutan tampilan sesuai hierarki jabatan.'),
    numbered('Simpan perubahan.'),
    infoBox('📸 FOTO', '📌 [TEMPATKAN TANGKAPAN LAYAR HALAMAN MANAJEMEN STRUKTUR ORGANISASI DI SINI]', C.BG_BLU, '3B82F6'),
  ]).concat(spacer(1)).concat([
    h2('4.6 Sambutan BPH'),
    body('Edit teks sambutan Ketua BPH yang tampil di beranda: klik "Sambutan BPH" di sidebar, edit teks dan foto penulis, simpan perubahan.'),
    h2('4.7 Banner Iklan / Pengumuman'),
    body('Kelola banner/pengumuman yang tampil di halaman publik melalui menu "Banner Iklan". Isi judul, gambar, link tujuan, dan status aktif/nonaktif.'),
    h2('4.8 Background Slide Beranda'),
    body('Kelola gambar-gambar slide di hero beranda melalui menu "Background Slide". Tambah slide baru, atur urutan, aktifkan/nonaktifkan slide individual.'),
    h2('4.9 Manajemen Aduan/Kontak Masuk'),
    body('Semua pesan yang dikirim melalui formulir kontak tersimpan di menu "Aduan Kontak" dengan status penanganan:'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Baru',      'Pesan baru yang belum dibaca oleh admin.'],
      ['Dibaca',    'Pesan sudah dibaca, belum ditangani.'],
      ['Diproses',  'Pesan sedang dalam proses penanganan.'],
      ['Selesai',   'Penanganan pesan telah selesai.'],
    ], ['Status', 'Keterangan']),
  ]).concat(spacer(1)).concat([pageBreak()]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 5: PENGATURAN SITUS
// ─────────────────────────────────────────────────────────────────────────────
function buildBab5() {
  return [
    h1('BAB 5 — PENGATURAN SITUS (SUPER ADMIN)'), divider(),
    body('Menu Pengaturan Situs hanya dapat diakses oleh Super Admin dan mengelola konfigurasi global yang memengaruhi semua halaman publik.'),
    h2('5.1 Informasi Umum Lembaga'),
  ].concat(spacer(1)).concat([
    dataTable([
      ['Nama Lembaga', 'Nama resmi lembaga yang tampil di header dan footer.'],
      ['Singkatan',    'Singkatan nama lembaga (misal: LAM).'],
      ['Alamat',       'Alamat lengkap kantor lembaga.'],
      ['Email Kontak', 'Email resmi lembaga untuk kontak publik.'],
      ['No. Telepon',  'Nomor telepon resmi lembaga.'],
      ['Tahun Berdiri','Tahun pendirian lembaga.'],
      ['Teks Footer',  'Teks yang tampil di bagian bawah setiap halaman.'],
    ], ['Field', 'Keterangan']),
  ]).concat(spacer(1)).concat([
    h2('5.2 Logo dan Favicon'),
    numbered('Di Pengaturan Situs, scroll ke bagian "Logo & Favicon".'),
    numbered('Upload Logo — disarankan PNG dengan latar transparan.'),
    numbered('Upload Favicon — disarankan ICO atau PNG berukuran 32×32 piksel.'),
    numbered('Klik "Simpan Pengaturan".'),
    h2('5.3 Media Sosial'),
    body('Tautan media sosial yang tampil di footer dan halaman kontak: Facebook URL, Instagram URL, YouTube URL, Twitter/X URL.'),
    h2('5.4 Gambar Hero (Banner Halaman)'),
    body('Setiap halaman publik memiliki banner gambar di atas (hero). Untuk menggantinya:'),
    numbered('Buka Pengaturan Situs → bagian "Hero Images".'),
    numbered('Pilih halaman: Hero Profil, Hero Berita, Hero Kontak, atau Hero Galeri.'),
    numbered('Upload gambar baru (resolusi disarankan: 1920×500 piksel, format JPG/PNG/WebP).'),
    numbered('Klik "Simpan".'),
    h2('5.5 SEO (Meta Deskripsi & Kata Kunci)'),
    bullet('Meta Deskripsi — deskripsi singkat website untuk mesin pencari (maks. 160 karakter).'),
    bullet('Meta Keywords — kata kunci relevan untuk website lembaga.'),
    h2('5.6 Peta Lokasi & Museum Digital'),
    bullet('Embed Peta — kode embed Google Maps untuk halaman kontak.'),
    bullet('URL Museum Digital — URL platform museum digital LAM Bengkalis.'),
    h2('5.7 Manajemen Pengguna Admin'),
    h3('5.7.1 Membuat Akun Baru'),
    numbered('Klik "Pengguna" di sidebar.'),
    numbered('Klik "+ Tambah Pengguna".'),
    numbered('Isi: Nama Lengkap, Email, Kata Sandi (min. 8 karakter), Role (Super Admin/Editor), Status (Aktif/Nonaktif).'),
    numbered('Klik "Simpan".'),
    h3('5.7.2 Menonaktifkan Akun'),
    numbered('Edit akun yang ingin dinonaktifkan.'),
    numbered('Ubah status menjadi "Nonaktif".'),
    numbered('Simpan perubahan.'),
    body('Akun nonaktif tidak dapat login meskipun kata sandi benar.'),
    h3('5.7.3 Mengubah Kata Sandi'),
    numbered('Klik nama akun di pojok kanan atas panel.'),
    numbered('Pilih "Profil" atau "Edit Profil".'),
    numbered('Masukkan kata sandi baru dan konfirmasinya, lalu simpan.'),
    pageBreak(),
  ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 6: PENANGANAN ERROR
// ─────────────────────────────────────────────────────────────────────────────
function buildBab6() {
  return [
    h1('BAB 6 — PENANGANAN ERROR & HALAMAN GALAT'), divider(),
    body('Website LAM Bengkalis dilengkapi halaman error yang dirancang estetik khas Melayu Bengkalis, menampilkan kode error, peribahasa Melayu yang relevan, dan panduan tindakan untuk pengguna.'),
    h2('6.1 Daftar Kode Error'),
    h3('6.1.1 Error 401 — Tidak Terautentikasi'),
  ].concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '401 Unauthorized'],
      ['Penyebab',      'Mengakses halaman yang memerlukan login tetapi belum login.'],
      ['Peribahasa',    '"Tak Kenal Maka Tak Sayang" — Identitas Anda Tidak Dikenal'],
      ['Tindakan User', 'Klik "Masuk ke Panel" untuk melakukan login terlebih dahulu.'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h3('6.1.2 Error 403 — Akses Ditolak'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '403 Forbidden'],
      ['Penyebab',      'Sudah login tetapi tidak memiliki izin mengakses halaman tersebut.'],
      ['Peribahasa',    '"Air Beriak Tanda Tak Dalam" — Akses Ditolak'],
      ['Tindakan User', 'Kembali ke beranda atau hubungi Super Admin untuk hak akses.'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h3('6.1.3 Error 404 — Halaman Tidak Ditemukan'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '404 Not Found'],
      ['Penyebab',      'URL tidak ada di sistem, atau konten sudah dihapus.'],
      ['Peribahasa',    '"Yang Dikejar Tak Dapat, Yang Dikandung Berciciran"'],
      ['Tindakan User', 'Periksa kembali URL, atau kembali ke beranda.'],
      ['Catatan',       '/admin sengaja menampilkan 404 sebagai fitur keamanan (silent 404).'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h3('6.1.4 Error 419 — Token Kedaluwarsa'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '419 Page Expired'],
      ['Penyebab',      'Token CSRF kedaluwarsa karena sesi terlalu lama tidak aktif.'],
      ['Peribahasa',    '"Terlambat Kapal Sudah Berlabuh" — Sesi Formulir Kedaluwarsa'],
      ['Tindakan User', 'Muat ulang halaman (F5) dan isi formulir dari awal.'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h3('6.1.5 Error 429 — Terlalu Banyak Permintaan'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '429 Too Many Requests'],
      ['Penyebab',      'Rate limiting: formulir kontak maks. 5 pengiriman per menit per IP.'],
      ['Peribahasa',    '"Sedikit-sedikit Lama-lama Menjadi Bukit"'],
      ['Tindakan User', 'Tunggu 1 menit penuh sebelum mencoba kembali.'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h3('6.1.6 Error 500 — Kesalahan Server Internal'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '500 Internal Server Error'],
      ['Penyebab',      'Kesalahan tidak terduga di dalam sistem/server.'],
      ['Peribahasa',    '"Ada Udang di Balik Batu" — Terjadi Kesalahan Sistem'],
      ['Tindakan User', 'Muat ulang halaman. Jika masih gagal, hubungi administrator.'],
      ['Admin Harus',   'Periksa log Laravel di storage/logs/laravel.log untuk detail error.'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h3('6.1.7 Error 503 — Layanan Tidak Tersedia'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Kode Error',    '503 Service Unavailable'],
      ['Penyebab',      'Website dalam mode maintenance atau server tidak dapat merespons.'],
      ['Peribahasa',    '"Tunggu Sampai Padi Masak" — Sistem Dalam Pemeliharaan'],
      ['Tindakan User', 'Tunggu beberapa saat dan coba kembali.'],
    ], ['Parameter', 'Detail']),
  ]).concat(spacer(1)).concat([
    h2('6.2 Langkah Pencegahan Error'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Jangan biarkan formulir terbuka terlalu lama',     'Mencegah error 419 token kedaluwarsa.'],
      ['Jangan klik Submit berkali-kali',                  'Mencegah error 429 terlalu banyak permintaan.'],
      ['Periksa URL sebelum mengakses',                    'Mencegah error 404.'],
      ['Gunakan browser versi terbaru',                    'Memastikan JS dan cookies berfungsi.'],
      ['Aktifkan JavaScript di browser',                   'reCAPTCHA memerlukan JavaScript.'],
      ['Bersihkan cache browser jika tampilan bermasalah', 'Ctrl+Shift+Delete untuk clear cache.'],
    ], ['Tindakan Pencegahan', 'Alasan']),
  ]).concat(spacer(1)).concat([pageBreak()]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 7: TROUBLESHOOTING
// ─────────────────────────────────────────────────────────────────────────────
function buildBab7() {
  return [
    h1('BAB 7 — TROUBLESHOOTING & PEMECAHAN MASALAH'), divider(),
    h2('7.1 Masalah Login Admin'),
    h3('7.1.1 Tidak Bisa Login — "Kredensial Tidak Valid"'),
    bullet('Periksa ejaan email — pastikan tidak ada kesalahan huruf atau spasi ekstra.'),
    bullet('Periksa Caps Lock — kata sandi bersifat case-sensitive.'),
    bullet('Pastikan akun belum dinonaktifkan oleh Super Admin.'),
    bullet('Coba reset kata sandi melalui fitur "Lupa Kata Sandi" (jika tersedia).'),
    h3('7.1.2 Diarahkan ke Halaman Setup 2FA Terus-menerus'),
    body('Ini normal bagi Super Admin yang belum mengaktifkan 2FA. Solusinya: selesaikan setup 2FA sesuai panduan Bab 3.4.2 dengan menginstal aplikasi authenticator dan memindai kode QR.'),
    h3('7.1.3 Kode OTP 2FA Tidak Valid'),
    bullet('Sinkronkan jam/waktu ponsel (pilih "Set time automatically" di pengaturan).'),
    bullet('Kode OTP berlaku 30 detik — tunggu kode baru jika sudah expired.'),
    bullet('Gunakan salah satu recovery code yang tersimpan saat setup 2FA.'),
    bullet('Jika kehilangan akses authenticator DAN recovery code — hubungi administrator server untuk reset 2FA langsung di database.'),
    h3('7.1.4 Lupa Kata Sandi'),
    numbered('Klik "Lupa Kata Sandi?" di halaman login.'),
    numbered('Masukkan alamat email akun.'),
    numbered('Cek email untuk tautan reset kata sandi.'),
    numbered('Klik tautan dan buat kata sandi baru.'),
    h2('7.2 Masalah Formulir Kontak'),
    h3('7.2.1 "Verifikasi Keamanan Tidak Ditemukan"'),
    body('Penyebab: JavaScript dinonaktifkan sehingga reCAPTCHA tidak dimuat. Solusi: aktifkan JavaScript, nonaktifkan ad-blocker yang memblokir Google reCAPTCHA, atau coba browser lain.'),
    h3('7.2.2 "Terlalu Banyak Permintaan"'),
    body('Rate limiting aktif (maks. 5 pengiriman per menit per IP). Tunggu 1 menit penuh sebelum mencoba kembali.'),
    h3('7.2.3 Formulir Tidak Merespons Setelah Klik Submit'),
    bullet('Periksa koneksi internet.'),
    bullet('Scroll ke atas untuk melihat pesan error validasi.'),
    bullet('Pastikan semua field wajib (*) sudah terisi dengan format yang benar.'),
    h2('7.3 Masalah Tampilan Website'),
    h3('7.3.1 Halaman Tidak Tampil dengan Benar'),
    bullet('Bersihkan cache: Ctrl+Shift+Delete (Windows) / Cmd+Shift+Delete (Mac).'),
    bullet('Hard refresh: Ctrl+F5 (Windows) / Cmd+Shift+R (Mac).'),
    bullet('Coba buka di browser berbeda (Chrome, Firefox, Edge).'),
    bullet('Pastikan browser versi terbaru.'),
    h3('7.3.2 Font Tidak Muncul dengan Benar'),
    body('Website menggunakan Google Fonts dari internet. Pastikan perangkat terhubung ke internet dan tidak ada firewall yang memblokir fonts.googleapis.com.'),
    h2('7.4 Masalah Upload Konten (Admin)'),
    h3('7.4.1 Gagal Upload Gambar'),
    bullet('Periksa ukuran file — pastikan tidak melebihi batas upload server.'),
    bullet('Pastikan format file yang didukung: JPG, PNG, GIF, WebP.'),
    bullet('Hubungi administrator server untuk memeriksa konfigurasi PHP upload_max_filesize.'),
    h3('7.4.2 Gambar Sudah Diupload tapi Tidak Tampil'),
    numbered('Pastikan storage link sudah dibuat: jalankan php artisan storage:link di server.'),
    numbered('Periksa izin folder storage/app/public — harus bisa dibaca web server.'),
    numbered('Bersihkan cache halaman jika menggunakan sistem cache.'),
    h2('7.5 Prosedur Eskalasi Masalah'),
  ].concat(spacer(1)).concat([
    dataTable([
      ['Level 1', 'Pengguna mencoba solusi mandiri sesuai panduan troubleshooting ini.'],
      ['Level 2', 'Pengguna/Editor menghubungi Super Admin dengan deskripsi dan screenshot masalah.'],
      ['Level 3', 'Super Admin memeriksa log sistem di storage/logs/laravel.log.'],
      ['Level 4', 'Super Admin menghubungi tim developer dengan data log error.'],
      ['Level 5', 'Tim developer melakukan analisis dan perbaikan kode/konfigurasi server.'],
    ], ['Level', 'Tindakan']),
  ]).concat(spacer(1)).concat([pageBreak()]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 8: KEAMANAN & PRAKTIK TERBAIK
// ─────────────────────────────────────────────────────────────────────────────
function buildBab8() {
  return [
    h1('BAB 8 — KEAMANAN & PRAKTIK TERBAIK'), divider(),
    h2('8.1 Praktik Terbaik untuk Semua Pengguna'),
    bullet('Jangan bagikan kata sandi akun kepada siapapun.'),
    bullet('Gunakan kata sandi kuat: min. 12 karakter, kombinasi huruf besar/kecil, angka, simbol.'),
    bullet('Ganti kata sandi secara berkala (minimal setiap 3 bulan).'),
    bullet('Jangan gunakan kata sandi yang sama untuk akun lain.'),
    bullet('Selalu logout setelah selesai menggunakan panel admin.'),
    bullet('Jangan mengakses panel admin melalui Wi-Fi publik yang tidak aman.'),
    bullet('Aktifkan layar kunci pada perangkat yang digunakan untuk akses admin.'),
    h2('8.2 Praktik Terbaik untuk Super Admin'),
    bullet('Selalu aktifkan 2FA dan jaga kerahasiaan kode OTP.'),
    bullet('Simpan recovery codes 2FA secara offline di tempat yang aman.'),
    bullet('Audit akun pengguna secara berkala — nonaktifkan akun yang tidak digunakan.'),
    bullet('Pantau log aktivitas sistem secara rutin.'),
    bullet('Jangan membuat akun Super Admin lebih dari yang benar-benar diperlukan.'),
    bullet('Lakukan backup database secara berkala.'),
    h2('8.3 Lapisan Keamanan yang Diimplementasikan'),
  ].concat(spacer(1)).concat([
    dataTable([
      ['URL Tersembunyi',            '/pentadbir menggantikan /admin — /admin diblokir 404 secara silent.'],
      ['CSRF Protection',            'Setiap formulir dilindungi token CSRF dari Laravel.'],
      ['Password Hashing',           'Kata sandi disimpan dalam hash bcrypt yang tidak bisa dibaca balik.'],
      ['2FA Wajib Super Admin',      'Super Admin tidak bisa akses panel tanpa 2FA aktif.'],
      ['Rate Limiting Kontak',       'Formulir kontak dibatasi 5 pengiriman/menit per IP.'],
      ['reCAPTCHA v3',               'Verifikasi anti-bot dengan threshold 0.5 untuk formulir kontak.'],
      ['Status Akun Aktif/Nonaktif', 'Akun nonaktif tidak bisa login meskipun kata sandi benar.'],
      ['RBAC',                       'Role-Based Access Control — Editor tidak bisa akses menu Super Admin.'],
      ['XSS Prevention',             'Input formulir kontak dibersihkan dengan strip_tags().'],
      ['Input Validation Server',    'Semua input divalidasi di sisi server sebelum diproses.'],
    ], ['Mekanisme Keamanan', 'Deskripsi']),
  ]).concat(spacer(1)).concat([
    h2('8.4 Prosedur Jika Akun Diretas'),
    numbered('Segera hubungi Super Admin atau administrator server.'),
    numbered('Super Admin segera nonaktifkan akun yang diretas dari panel admin.'),
    numbered('Ganti kata sandi dari perangkat yang aman.'),
    numbered('Reset 2FA jika diperlukan.'),
    numbered('Periksa log aktivitas untuk mengetahui tindakan tidak sah yang dilakukan.'),
    numbered('Laporkan kejadian kepada pimpinan lembaga.'),
    numbered('Pertimbangkan mengganti kata sandi semua akun admin sebagai pencegahan.'),
    pageBreak(),
  ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// BAB 9: REFERENSI CEPAT
// ─────────────────────────────────────────────────────────────────────────────
function buildBab9() {
  return [
    h1('BAB 9 — REFERENSI CEPAT'), divider(),
    h2('9.1 Ringkasan Kode Error'),
  ].concat(spacer(1)).concat([
    dataTable([
      ['401', 'Tidak Terautentikasi',      'Login terlebih dahulu.'],
      ['403', 'Akses Ditolak',             'Hubungi Super Admin untuk hak akses.'],
      ['404', 'Halaman Tidak Ditemukan',   'Periksa URL atau kembali ke beranda.'],
      ['419', 'Token Kedaluwarsa',         'Muat ulang halaman (F5).'],
      ['429', 'Terlalu Banyak Permintaan', 'Tunggu 1 menit dan coba kembali.'],
      ['500', 'Kesalahan Server Internal', 'Hubungi administrator sistem.'],
      ['503', 'Layanan Tidak Tersedia',    'Tunggu dan coba kembali nanti.'],
    ], ['Kode', 'Makna', 'Tindakan']),
  ]).concat(spacer(1)).concat([
    h2('9.2 URL Penting'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['Beranda',        'https://[domain-anda]/'],
      ['Profil',         'https://[domain-anda]/profil'],
      ['Berita',         'https://[domain-anda]/berita'],
      ['Galeri',         'https://[domain-anda]/galeri'],
      ['Kontak',         'https://[domain-anda]/kontak'],
      ['Museum Digital', 'https://[domain-anda]/museum'],
      ['Panel Admin',    'https://[domain-anda]/pentadbir'],
      ['Login Admin',    'https://[domain-anda]/pentadbir/login'],
      ['Setup 2FA',      'https://[domain-anda]/pentadbir/two-factor-setup'],
    ], ['Halaman', 'URL']),
  ]).concat(spacer(1)).concat([
    h2('9.3 Pintasan Keyboard'),
  ]).concat(spacer(1)).concat([
    dataTable([
      ['F5 / Ctrl+R',           'Muat ulang halaman.'],
      ['Ctrl+F5 / Cmd+Shift+R', 'Hard refresh — muat ulang + hapus cache.'],
      ['Ctrl+Shift+Delete',     'Buka panel bersihkan cache browser.'],
      ['F12',                   'Buka Developer Tools browser.'],
    ], ['Pintasan', 'Fungsi']),
  ]).concat(spacer(1)).concat([
    h2('9.4 Informasi Kontak Bantuan Teknis'),
    infoBox('📌 INFO', '📌 [ISI INFORMASI KONTAK TIM TEKNIS/ADMINISTRATOR DI SINI]\nContoh: Email: admin@lam-bengkalis.go.id | Telepon: 0778-XXXXXX | WhatsApp: 08XX-XXXX-XXXX', C.BG_GRN, C.GREEN),
  ]).concat(spacer(1)).concat([
    h2('9.5 Checklist Rutin Administrator'),
    h3('Harian'),
    bullet('Periksa aduan/pesan baru di menu Kontak.'),
    bullet('Pastikan website dapat diakses dengan normal.'),
    h3('Mingguan'),
    bullet('Perbarui berita dan konten terkini.'),
    bullet('Tambahkan foto kegiatan terbaru ke galeri.'),
    bullet('Review status aduan yang belum ditangani.'),
    h3('Bulanan'),
    bullet('Audit akun pengguna — nonaktifkan akun yang tidak aktif.'),
    bullet('Periksa log error di storage/logs/laravel.log.'),
    bullet('Backup database.'),
    bullet('Perbarui gambar banner/hero halaman jika diperlukan.'),
    h3('Triwulan'),
    bullet('Ganti kata sandi akun admin.'),
    bullet('Review pengaturan keamanan sistem.'),
    bullet('Update software server jika ada patch keamanan.'),
    pageBreak(),
  ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// PENUTUP
// ─────────────────────────────────────────────────────────────────────────────
function buildPenutup() {
  return [
    h1('PENUTUP'), divider(),
    body('Demikian Petunjuk Penggunaan Website Resmi Lembaga Adat Melayu (LAM) Kabupaten Bengkalis ini disusun. Dokumen ini diharapkan dapat menjadi panduan yang komprehensif dan mudah dipahami bagi seluruh pengguna sistem.'),
    body('Website ini terus dikembangkan untuk memberikan layanan informasi yang terbaik kepada masyarakat Kabupaten Bengkalis dan khalayak luas. Setiap masukan, saran, dan laporan kendala sangat kami apresiasi untuk perbaikan sistem ke depannya.'),
    body('Semoga website resmi LAM Bengkalis ini dapat menjadi sarana yang efektif dalam melestarikan, mempromosikan, dan mengembangkan nilai-nilai budaya Melayu Bengkalis di era digital ini.'),
  ].concat(spacer(2)).concat([
    new Paragraph({ children: [new TextRun({ text: '"Adat bersendi Syarak, Syarak bersendi Kitabullah"', italic: true, size: 28, font: 'Calibri', color: C.GOLD, bold: true })], alignment: AlignmentType.CENTER, spacing: { before: 240, after: 240 } }),
    new Paragraph({ border: { top: { style: BorderStyle.SINGLE, size: 6, color: C.GOLD }, bottom: { style: BorderStyle.SINGLE, size: 6, color: C.GOLD } }, spacing: { before: 0, after: 240 }, text: '' }),
  ]).concat(spacer(3)).concat([
    new Paragraph({ children: [new TextRun({ text: 'Bengkalis, 2026', font: 'Calibri', size: 22 })], alignment: AlignmentType.RIGHT, spacing: { after: 80 } }),
    new Paragraph({ children: [new TextRun({ text: 'Tim Pengembang Sistem Informasi', font: 'Calibri', size: 22, italic: true })], alignment: AlignmentType.RIGHT, spacing: { after: 40 } }),
    new Paragraph({ children: [new TextRun({ text: 'Lembaga Adat Melayu Kabupaten Bengkalis', font: 'Calibri', size: 22, bold: true, color: C.GREEN })], alignment: AlignmentType.RIGHT, spacing: { after: 40 } }),
  ]);
}

// ─────────────────────────────────────────────────────────────────────────────
// HEADER & FOOTER
// ─────────────────────────────────────────────────────────────────────────────
function buildHeader() {
  var children = [];
  if (logoBuffer) {
    children.push(new Paragraph({
      children: [
        makeLogo(40, 40),
        new TextRun({ text: '   ', size: 20 }),
        new TextRun({ text: 'Petunjuk Penggunaan | Website Resmi LAM Bengkalis', font: 'Calibri', size: 18, color: C.GREY, italics: true }),
      ],
      border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: C.GOLD, space: 1 } },
      spacing: { after: 80 },
    }));
  } else {
    children.push(new Paragraph({
      children: [
        new TextRun({ text: 'LAM Bengkalis', font: 'Calibri', size: 18, bold: true, color: C.GREEN }),
        new TextRun({ text: '  |  Petunjuk Penggunaan Website', font: 'Calibri', size: 18, color: C.GREY, italics: true }),
      ],
      border: { bottom: { style: BorderStyle.SINGLE, size: 6, color: C.GOLD, space: 1 } },
      spacing: { after: 80 },
    }));
  }
  return new Header({ children: children });
}

function buildFooter() {
  return new Footer({
    children: [new Paragraph({
      children: [
        new TextRun({ text: '© 2026 Lembaga Adat Melayu Kabupaten Bengkalis  —  Halaman ', font: 'Calibri', size: 16, color: C.GREY }),
        new TextRun({ children: [PageNumber.CURRENT], font: 'Calibri', size: 16, color: C.GREY }),
        new TextRun({ text: ' dari ', font: 'Calibri', size: 16, color: C.GREY }),
        new TextRun({ children: [PageNumber.TOTAL_PAGES], font: 'Calibri', size: 16, color: C.GREY }),
      ],
      alignment: AlignmentType.CENTER,
      border: { top: { style: BorderStyle.SINGLE, size: 6, color: C.GOLD, space: 1 } },
      spacing: { before: 80 },
    })],
  });
}

// ─────────────────────────────────────────────────────────────────────────────
// MAIN
// ─────────────────────────────────────────────────────────────────────────────
async function main() {
  console.log('📄 Memulai pembuatan dokumen Word...');

  var allChildren = []
    .concat(buildCover())
    .concat(buildPengantar())
    .concat(buildToc())
    .concat(buildBab1())
    .concat(buildBab2())
    .concat(buildBab3())
    .concat(buildBab4())
    .concat(buildBab5())
    .concat(buildBab6())
    .concat(buildBab7())
    .concat(buildBab8())
    .concat(buildBab9())
    .concat(buildPenutup());

  var doc = new Document({
    creator    : 'LAM Bengkalis — Sistem Informasi',
    title      : 'Petunjuk Penggunaan Website Resmi LAM Bengkalis',
    description: 'Dokumen petunjuk penggunaan lengkap website resmi LAM Bengkalis.',
    subject    : 'Petunjuk Penggunaan Sistem Informasi',
    keywords   : 'LAM, Bengkalis, Melayu, Adat, Website, Manual, Panduan',
    styles: {
      default: {
        document: {
          run: { font: 'Calibri', size: 22, color: '222222' },
          paragraph: { spacing: { line: 360 } },
        },
      },
      paragraphStyles: [
        {
          id: 'Heading1', name: 'Heading 1', basedOn: 'Normal', next: 'Normal',
          run: { bold: true, size: 36, color: C.GREEN, font: 'Calibri' },
          paragraph: { spacing: { before: 480, after: 240 }, shading: { type: ShadingType.CLEAR, fill: 'F0FAF3' } },
        },
        {
          id: 'Heading2', name: 'Heading 2', basedOn: 'Normal', next: 'Normal',
          run: { bold: true, size: 28, color: C.GOLD, font: 'Calibri' },
          paragraph: { spacing: { before: 320, after: 160 } },
        },
        {
          id: 'Heading3', name: 'Heading 3', basedOn: 'Normal', next: 'Normal',
          run: { bold: true, size: 24, color: '333333', font: 'Calibri' },
          paragraph: { spacing: { before: 200, after: 80 } },
        },
      ],
    },
    numbering: {
      config: [{
        reference: 'main-numbering',
        levels: [
          {
            level: 0, format: LevelFormat.DECIMAL, text: '%1.',
            alignment: AlignmentType.LEFT,
            style: { run: { font: 'Calibri', size: 22 }, paragraph: { indent: { left: convertInchesToTwip(0.5), hanging: convertInchesToTwip(0.25) } } },
          },
          {
            level: 1, format: LevelFormat.LOWER_LETTER, text: '%2.',
            alignment: AlignmentType.LEFT,
            style: { run: { font: 'Calibri', size: 22 }, paragraph: { indent: { left: convertInchesToTwip(1.0), hanging: convertInchesToTwip(0.25) } } },
          },
        ],
      }],
    },
    sections: [{
      properties: {
        page: {
          margin: {
            top   : convertInchesToTwip(1.0),
            right : convertInchesToTwip(1.0),
            bottom: convertInchesToTwip(1.0),
            left  : convertInchesToTwip(1.25),
          },
        },
      },
      headers: { default: buildHeader() },
      footers: { default: buildFooter() },
      children: allChildren,
    }],
  });

  var outPath = path.join(__dirname, 'Petunjuk_Penggunaan_LAM_Bengkalis.docx');
  var buffer  = await Packer.toBuffer(doc);
  fs.writeFileSync(outPath, buffer);

  console.log('');
  console.log('✅ ===================================================');
  console.log('✅  DOKUMEN BERHASIL DIBUAT!');
  console.log('✅ ===================================================');
  console.log('📂 Lokasi file: ' + outPath);
  console.log('');
  console.log('📌 DAFTAR FOTO YANG PERLU DITAMBAHKAN MANUAL:');
  console.log('   Buka file .docx di Word, cari teks [TEMPATKAN ...] dan ganti dengan screenshot:');
  console.log('');
  console.log('   1. Halaman Beranda (/)              → Bab 2.1');
  console.log('   2. Halaman Profil (/profil)         → Bab 2.2');
  console.log('   3. Halaman Galeri (/galeri)         → Bab 2.4');
  console.log('   4. Halaman Login (/pentadbir/login) → Bab 3.3.1');
  console.log('   5. Halaman Setup 2FA               → Bab 3.4.2');
  console.log('   6. Dashboard Admin (/pentadbir)    → Bab 4.1');
  console.log('   7. Formulir Tambah Berita           → Bab 4.2.1');
  console.log('   8. Manajemen Struktur Organisasi   → Bab 4.5');
  console.log('   9. Kontak Tim Teknis               → Bab 9.4');
  console.log('');
  console.log('📝 Setelah membuka di Word:');
  console.log('   Klik menu References → Update Table → Update entire table');
  console.log('   (untuk memperbarui nomor halaman di Daftar Isi)');
}

main().catch(function(err) {
  console.error('❌ Gagal membuat dokumen:', err.message);
  console.error(err.stack);
  process.exit(1);
});
