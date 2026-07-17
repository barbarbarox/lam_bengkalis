@include('errors.layout', [
    'code'        => '429',
    'title'       => 'Terlalu Banyak Permintaan',
    'peribahasa'  => 'Biar lambat asal selamat — jangan tergesa-gesa.',
    'description' => 'Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Sistem kami membatasi ini demi keamanan bersama. Silakan tunggu beberapa saat sebelum mencoba kembali.',
    'badgeClass'  => 'badge-red',
    'badgeLabel'  => '429 — Terlalu Banyak',
])
