@include('errors.layout', [
    'code'        => '401',
    'title'       => 'Autentikasi Diperlukan',
    'peribahasa'  => 'Bagai tamu tak diundang — silakan masuk dulu sebelum melanjutkan.',
    'description' => 'Anda harus masuk (login) terlebih dahulu untuk mengakses halaman ini. Silakan klik tombol di bawah untuk menuju halaman login.',
    'badgeClass'  => 'badge-yellow',
    'badgeLabel'  => '401 — Belum Login',
])
