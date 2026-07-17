@include('errors.layout', [
    'code'        => '500',
    'title'       => 'Kesalahan Server Internal',
    'peribahasa'  => 'Rumah besar, halaman luas — tetapi pintu sedang terkunci dari dalam.',
    'description' => 'Terjadi kesalahan tidak terduga pada server kami. Tim teknisi kami sudah diberitahu dan sedang berusaha memperbaikinya. Silakan coba kembali beberapa saat lagi.',
    'badgeClass'  => 'badge-red',
    'badgeLabel'  => '500 — Galat Server',
])
