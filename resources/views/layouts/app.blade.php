<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#0B4F30">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- SEO --}}
  <title>@yield('title', config('app.name', 'LAM Bengkalis'))</title>
  <meta name="description" content="@yield('meta_description', $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Kabupaten Bengkalis.')">

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  {{-- Alpine.js --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- reCAPTCHA v3 (hanya di halaman kontak) --}}
  @stack('head_scripts')

  <style>
    /* ─── Design Tokens ────────────────────────────────────────── */
    :root {
      --lam-green:   #0B4F30;
      --lam-green-d: #083d24;
      --lam-green-l: #1a7a4e;
      --lam-gold:    #D4AF37;
      --lam-gold-d:  #b8941f;
      --lam-maroon:  #7A1E1E;
      --lam-cream:   #F5EEDD;
      --lam-cream-d: #e8d9bc;
      --lam-text:    #1C1C1C;
      --lam-text-m:  #4a4a4a;
      --lam-text-l:  #6b7280;
      --lam-border:  rgba(11,79,48,.15);
      --lam-shadow:  0 4px 24px rgba(11,79,48,.1);
      --font-head:   'Playfair Display', Georgia, serif;
      --font-body:   'Inter', system-ui, sans-serif;
      --radius:      .75rem;
      --radius-sm:   .375rem;
      --transition:  .25s ease;
    }

    /* ─── Reset & Base ─────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body {
      font-family: var(--font-body);
      color: var(--lam-text);
      background: var(--lam-cream);
      line-height: 1.7;
      overflow-x: hidden;
    }
    img { max-width: 100%; display: block; }
    a { color: inherit; text-decoration: none; }
    h1, h2, h3, h4, h5 { font-family: var(--font-head); line-height: 1.3; }

    /* ─── Utilities ────────────────────────────────────────────── */
    .container { max-width: 1200px; margin: 0 auto; padding: 0 1.25rem; }
    .section-pad { padding: 5rem 0; }
    .section-pad-sm { padding: 3rem 0; }
    .sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border-width:0; }

    /* Section heading */
    .section-heading { text-align: center; margin-bottom: 3rem; }
    .section-heading__eyebrow {
      display: inline-block; font-size: .75rem; font-family: var(--font-body);
      letter-spacing: .25em; text-transform: uppercase; color: var(--lam-gold);
      margin-bottom: .75rem; font-weight: 600;
    }
    .section-heading__title {
      font-size: clamp(1.75rem, 3.5vw, 2.5rem); color: var(--lam-green);
    }
    .section-heading__divider {
      display: flex; align-items: center; justify-content: center;
      gap: .5rem; margin-top: 1rem;
    }
    .section-heading__divider span {
      display: block; height: 1px; width: 48px; background: var(--lam-gold); opacity: .6;
    }
    .section-heading__divider i {
      display: block; width: 6px; height: 6px; border-radius: 50%;
      background: var(--lam-gold); font-style: normal;
    }

    /* Btn */
    .btn {
      display: inline-flex; align-items: center; gap: .5rem;
      padding: .75rem 1.75rem; border-radius: var(--radius-sm);
      font-family: var(--font-body); font-size: .9rem; font-weight: 600;
      cursor: pointer; border: 2px solid transparent;
      transition: background var(--transition), color var(--transition),
                  border-color var(--transition), transform .15s;
    }
    .btn:hover { transform: translateY(-1px); }
    .btn-primary {
      background: var(--lam-gold); color: var(--lam-green);
      border-color: var(--lam-gold);
    }
    .btn-primary:hover { background: var(--lam-gold-d); border-color: var(--lam-gold-d); }
    .btn-outline {
      background: transparent; color: var(--lam-green);
      border-color: var(--lam-green);
    }
    .btn-outline:hover { background: var(--lam-green); color: white; }
    .btn-outline-white {
      background: transparent; color: white; border-color: rgba(255,255,255,.6);
    }
    .btn-outline-white:hover { background: rgba(255,255,255,.1); }

    /* ─── Navbar ───────────────────────────────────────────────── */
    .navbar {
      position: sticky; top: 0; z-index: 100;
      background: rgba(11,79,48,.95);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 4px 20px rgba(0,0,0,.15);
      border-bottom: 1px solid rgba(255,255,255,.05);
      transition: all var(--transition);
    }
    .navbar__inner {
      display: flex; align-items: center; justify-content: space-between;
      height: 80px;
    }
    .navbar__brand {
      display: flex; align-items: center; gap: 1rem;
    }
    .navbar__brand-logo {
      width: 48px; height: 48px; border-radius: 50%;
      object-fit: contain; background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,.15);
    }
    .navbar__brand-text {
      display: flex; flex-direction: column;
    }
    .navbar__brand-name {
      font-family: var(--font-head); font-size: 1.15rem;
      font-weight: 700; color: var(--lam-gold); line-height: 1.1;
      letter-spacing: .02em;
    }
    .navbar__brand-sub {
      font-size: .7rem; color: rgba(255,255,255,.8);
      letter-spacing: .15em; text-transform: uppercase;
      margin-top: .1rem;
    }

    .navbar__nav {
      display: flex; align-items: center; gap: 1.5rem;
    }
    .navbar__link {
      position: relative;
      padding: .5rem 0; 
      color: rgba(255,255,255,.9); font-size: .9rem; font-weight: 500;
      transition: color var(--transition);
    }
    .navbar__link::after {
      content: ''; position: absolute; left: 0; bottom: 0;
      width: 0; height: 2px; background: var(--lam-gold);
      transition: width var(--transition);
      border-radius: 2px;
    }
    .navbar__link:hover,
    .navbar__link.is-active {
      color: var(--lam-gold); 
    }
    .navbar__link:hover::after,
    .navbar__link.is-active::after {
      width: 100%;
    }
    .navbar__museum-btn {
      display: inline-flex; align-items: center; gap: .5rem;
      margin-left: .5rem; padding: .6rem 1.25rem;
      background: linear-gradient(135deg, var(--lam-gold), var(--lam-gold-d));
      color: var(--lam-green-d);
      border-radius: 50px;
      font-size: .85rem; font-weight: 700;
      letter-spacing: .03em;
      box-shadow: 0 4px 15px rgba(212, 175, 55, 0.25);
      transition: all var(--transition);
    }
    .navbar__museum-btn:hover { 
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
    }

    .navbar__hamburger {
      display: none; flex-direction: column; gap: 5px;
      padding: .5rem; cursor: pointer; background: none; border: none;
    }
    .navbar__hamburger span {
      display: block; width: 24px; height: 2px;
      background: white; border-radius: 2px;
      transition: transform .25s, opacity .25s;
    }

    /* Mobile nav */
    .navbar__mobile {
      display: none; flex-direction: column;
      background: rgba(8,61,36,.98);
      backdrop-filter: blur(10px);
      padding: 1rem 0;
      border-top: 1px solid rgba(255,255,255,.05);
    }
    .navbar__mobile-link {
      padding: .85rem 1.5rem; color: rgba(255,255,255,.9);
      font-size: .95rem; font-weight: 500;
      transition: color var(--transition), background var(--transition);
      border-left: 3px solid transparent;
    }
    .navbar__mobile-link:hover { 
      color: var(--lam-gold); 
      background: rgba(255,255,255,.05); 
      border-left-color: var(--lam-gold);
    }
    .navbar__mobile-museum-btn {
      margin: 1rem 1.5rem .5rem;
      padding: .75rem; text-align: center;
      background: var(--lam-gold); color: var(--lam-green-d);
      border-radius: var(--radius-sm); font-weight: 700;
      display: flex; align-items: center; justify-content: center; gap: .5rem;
      box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
    }

    @media (max-width: 768px) {
      .navbar__nav { display: none; }
      .navbar__hamburger { display: flex; }
      .navbar__mobile.is-open { display: flex; }
    }

    /* ─── Footer ───────────────────────────────────────────────── */
    .footer {
      background: var(--lam-green-d); color: rgba(255,255,255,.75);
      padding: 3.5rem 0 1.5rem;
    }
    .footer__grid {
      display: grid; grid-template-columns: 2fr 1fr 1fr;
      gap: 2.5rem; margin-bottom: 2.5rem;
    }
    .footer__brand-name {
      font-family: var(--font-head); font-size: 1.1rem;
      color: var(--lam-gold); margin-bottom: .5rem;
    }
    .footer__brand-desc { font-size: .85rem; line-height: 1.7; }
    .footer__col-title {
      font-family: var(--font-body); font-size: .75rem; font-weight: 600;
      letter-spacing: .15em; text-transform: uppercase;
      color: var(--lam-gold); margin-bottom: 1rem;
    }
    .footer__links { display: flex; flex-direction: column; gap: .5rem; }
    .footer__links a {
      font-size: .875rem; color: rgba(255,255,255,.7);
      transition: color var(--transition);
    }
    .footer__links a:hover { color: var(--lam-gold); }
    .footer__bottom {
      border-top: 1px solid rgba(255,255,255,.1);
      padding-top: 1.5rem; font-size: .8rem;
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: .5rem;
    }
    @media (max-width: 768px) {
      .footer__grid { grid-template-columns: 1fr; }
      .footer__bottom { flex-direction: column; text-align: center; }
    }

    /* ─── Card Berita ──────────────────────────────────────────── */
    .card-berita {
      background: white; border-radius: var(--radius);
      overflow: hidden; box-shadow: var(--lam-shadow);
      transition: transform var(--transition), box-shadow var(--transition);
      display: flex; flex-direction: column;
    }
    .card-berita:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(11,79,48,.15); }
    .card-berita__img { width: 100%; height: 200px; object-fit: cover; }
    .card-berita__img-placeholder {
      width: 100%; height: 200px; background: var(--lam-cream-d);
      display: flex; align-items: center; justify-content: center;
    }
    .card-berita__body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .card-berita__cat {
      font-size: .7rem; font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--lam-gold); margin-bottom: .5rem;
    }
    .card-berita__title {
      font-size: 1rem; color: var(--lam-text); margin-bottom: .75rem;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .card-berita__excerpt {
      font-size: .85rem; color: var(--lam-text-l); flex: 1;
      display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .card-berita__meta {
      margin-top: 1rem; padding-top: .75rem;
      border-top: 1px solid var(--lam-border);
      font-size: .78rem; color: var(--lam-text-l);
      display: flex; justify-content: space-between; align-items: center;
    }
  </style>
</head>
<body>

{{-- Loading Screen (hanya render sekali di sesi pertama) --}}
<x-loading-screen />

{{-- ── Navbar ──────────────────────────────────────────────────────────────── --}}
<header x-data="{ open: false }" class="navbar" role="banner">
  <div class="container navbar__inner">

    {{-- Brand --}}
    <a href="{{ route('beranda') }}" class="navbar__brand" aria-label="LAM Bengkalis — Halaman Utama">
      @if($setting->logo_path)
        <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo LAM Bengkalis" class="navbar__brand-logo" width="40" height="40">
      @endif
      <div class="navbar__brand-text">
        <span class="navbar__brand-name">{{ $setting->singkatan ?? 'LAM Bengkalis' }}</span>
        <span class="navbar__brand-sub">Lembaga Adat Melayu</span>
      </div>
    </a>

    {{-- Desktop Nav --}}
    <nav class="navbar__nav" role="navigation" aria-label="Navigasi Utama">
      <a href="{{ route('beranda') }}"      class="navbar__link {{ Request::routeIs('beranda')      ? 'is-active' : '' }}">Beranda</a>
      <a href="{{ route('profil') }}"       class="navbar__link {{ Request::routeIs('profil')       ? 'is-active' : '' }}">Profil</a>
      <a href="{{ route('berita.index') }}" class="navbar__link {{ Request::routeIs('berita.*')     ? 'is-active' : '' }}">Berita</a>
      <a href="{{ route('kontak') }}"       class="navbar__link {{ Request::routeIs('kontak')       ? 'is-active' : '' }}">Kontak</a>
      
      @php
        $urlMuseum = '';
        if ($setting->meta_keywords) {
          $decodedMeta = json_decode($setting->meta_keywords, true);
          $urlMuseum = $decodedMeta['url_museum'] ?? '';
        }
      @endphp

      @if($urlMuseum)
        <a href="{{ $urlMuseum }}" class="navbar__museum-btn" target="_blank" rel="noopener noreferrer"
           title="Buka Jejak Layar (Museum Digital) — tab baru">
           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          Museum
        </a>
      @endif
    </nav>

    {{-- Hamburger Mobile --}}
    <button class="navbar__hamburger" @click="open = !open"
            :aria-expanded="open" aria-controls="mobile-nav"
            aria-label="Buka menu navigasi">
      <span :style="open ? 'transform:rotate(45deg) translate(5px,5px)' : ''"></span>
      <span :style="open ? 'opacity:0' : ''"></span>
      <span :style="open ? 'transform:rotate(-45deg) translate(5px,-5px)' : ''"></span>
    </button>
  </div>

    {{-- Mobile Dropdown --}}
  <nav id="mobile-nav" class="navbar__mobile" :class="open ? 'is-open' : ''"
       role="navigation" aria-label="Navigasi Mobile">
    <a href="{{ route('beranda') }}"      class="navbar__mobile-link" @click="open=false">Beranda</a>
    <a href="{{ route('profil') }}"       class="navbar__mobile-link" @click="open=false">Profil Lembaga</a>
    <a href="{{ route('berita.index') }}" class="navbar__mobile-link" @click="open=false">Berita</a>
    <a href="{{ route('kontak') }}"       class="navbar__mobile-link" @click="open=false">Kontak</a>
    
    @php
      $urlMuseum = '';
      if ($setting->meta_keywords) {
        $decodedMeta = json_decode($setting->meta_keywords, true);
        $urlMuseum = $decodedMeta['url_museum'] ?? '';
      }
    @endphp

    @if($urlMuseum)
      <a href="{{ $urlMuseum }}" class="navbar__mobile-museum-btn" target="_blank" rel="noopener noreferrer">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        Jejak Layar (Museum)
      </a>
    @endif
  </nav>
</header>

{{-- ── Konten Halaman ──────────────────────────────────────────────────────── --}}
<main id="main-content" role="main">
  @yield('content')
</main>

{{-- ── Footer ──────────────────────────────────────────────────────────────── --}}
<footer class="footer" role="contentinfo">
  <div class="container">
    <div class="footer__grid">
      <div>
        <p class="footer__brand-name">{{ $setting->nama_lembaga ?? 'Lembaga Adat Melayu Kabupaten Bengkalis' }}</p>
        <p class="footer__brand-desc">
          {{ $setting->meta_deskripsi ?? 'Menjaga dan melestarikan adat budaya Melayu di Kabupaten Bengkalis, Riau.' }}
        </p>
        {{-- Sosial Media --}}
        @php
          $sosmed = [];
          $sosmedRaw = $setting->meta_keywords;
          if ($sosmedRaw) {
            $decoded = json_decode($sosmedRaw, true);
            $sosmed = $decoded['sosmed'] ?? [];
          }
          $icons = [
            'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
            'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
            'youtube'   => '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58a2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>',
            'twitter'   => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>',
          ];
        @endphp
        @if(!empty($sosmed))
          <div style="display:flex;gap:.75rem;margin-top:1.25rem;">
            @foreach($sosmed as $s)
              @if(!empty($s['url']))
                <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer"
                   aria-label="{{ ucfirst($s['platform'] ?? '') }}"
                   style="color:rgba(255,255,255,.5);transition:color .25s;"
                   onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='rgba(255,255,255,.5)'">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    {!! $icons[$s['platform']] ?? '<circle cx="12" cy="12" r="10"/>' !!}
                  </svg>
                </a>
              @endif
            @endforeach
          </div>
        @endif
      </div>

      <div>
        <p class="footer__col-title">Navigasi</p>
        <nav class="footer__links" aria-label="Tautan Footer">
          <a href="{{ route('beranda') }}">Beranda</a>
          <a href="{{ route('profil') }}">Profil Lembaga</a>
          <a href="{{ route('berita.index') }}">Berita</a>
          <a href="{{ route('kontak') }}">Kontak</a>
          @if($setting->url_museum)
            <a href="{{ route('museum') }}" target="_blank" rel="noopener noreferrer">Jejak Layar</a>
          @endif
        </nav>
      </div>

      <div>
        <p class="footer__col-title">Kontak</p>
        <div class="footer__links" style="gap:.75rem;">
          @if($setting->alamat)
            <address style="font-style:normal;font-size:.875rem;color:rgba(255,255,255,.7);line-height:1.6;">
              {{ $setting->alamat }}
            </address>
          @endif
          @if($setting->email_kontak)
            <a href="mailto:{{ $setting->email_kontak }}">{{ $setting->email_kontak }}</a>
          @endif
          @if($setting->no_telp)
            <a href="tel:{{ $setting->no_telp }}">{{ $setting->no_telp }}</a>
          @endif
        </div>
      </div>
    </div>

    <div class="footer__bottom">
      <span>&copy; {{ $setting->tahun_berdiri ?? date('Y') }}–{{ date('Y') }} {{ $setting->nama_lembaga ?? 'LAM Bengkalis' }}. Hak Cipta Dilindungi.</span>
      <span style="font-size:.75rem;opacity:.5;">Sistem Informasi LAM Bengkalis</span>
    </div>
  </div>
</footer>

@stack('body_scripts')
</body>
</html>
