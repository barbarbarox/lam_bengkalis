@php
  $code        = $exception->getStatusCode() ?? 500;
  $titles      = [
    400 => 'Permintaan Tidak Valid',
    405 => 'Metode Tidak Diizinkan',
    408 => 'Waktu Permintaan Habis',
    502 => 'Gateway Buruk',
    504 => 'Gateway Timeout',
  ];
  $title = $titles[$code] ?? 'Terjadi Kesalahan';
@endphp

@include('errors.layout', [
    'code'        => $code,
    'title'       => $title,
    'peribahasa'  => 'Tak ada rotan, akar pun jadi — mari coba jalan lain.',
    'description' => 'Maaf, terjadi kesalahan yang tidak terduga. Silakan kembali ke beranda atau coba halaman lain.',
    'badgeClass'  => 'badge-yellow',
    'badgeLabel'  => $code . ' — Galat',
])
