{{--
  Partial: _hero-mini.blade.php
  Penggunaan:
    @include('public.partials._hero-mini', [
        'halaman'  => 'agenda',         // kunci untuk heroUrl()
        'gradient' => '#1A237E, #0d1561', // warna fallback jika tidak ada gambar
        'title'    => __('ui.agenda_title'),
        'crumbs'   => [
            ['label' => 'Beranda', 'url' => route('beranda')],
            ['label' => 'Agenda Kegiatan'],
        ]
    ])

  Variabel yang diperlukan dari controller: $setting (SiteSetting::instance())
--}}
@php
    $heroImg = $setting->heroUrl($halaman ?? 'profil');
    if ($heroImg) {
        $heroBg = "background: linear-gradient(to bottom, rgba(0,0,0,.52) 0%, rgba(0,0,0,.62) 100%), url('{$heroImg}') center/cover no-repeat;";
    } else {
        $heroBg = "background: linear-gradient(135deg, {$gradient} 100%);";
    }
@endphp
<div class="hero-mini" style="{{ $heroBg }}">
  <div class="container hero-mini__inner">
    <h1 class="hero-mini__title">{{ $title }}</h1>
    <nav class="hero-mini__crumb">
      @foreach($crumbs as $i => $crumb)
        @if(isset($crumb['url']))
          <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a><span>/</span>
        @else
          <span>{{ $crumb['label'] }}</span>
        @endif
      @endforeach
    </nav>
  </div>
</div>
