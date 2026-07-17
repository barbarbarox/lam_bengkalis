@include('errors.layout', [
    'code'        => '419',
    'title'       => 'Sesi Telah Kadaluwarsa',
    'peribahasa'  => 'Air yang tenang jangan disangka tiada buaya — sesi Anda telah habis masa berlakunya.',
    'description' => 'Token keamanan sesi Anda sudah kadaluwarsa. Ini biasanya terjadi jika Anda terlalu lama tidak aktif atau membuka beberapa tab sekaligus. Silakan kembali dan coba lagi.',
    'badgeClass'  => 'badge-yellow',
    'badgeLabel'  => '419 — Sesi Kadaluwarsa',
])
