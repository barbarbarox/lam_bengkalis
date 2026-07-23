<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#FFFFFF">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- SEO --}}
  <title>@yield('title', config('app.name', 'LAMR Bengkalis'))</title>
  <meta name="description" content="@yield('meta_description', $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Riau Kabupaten Bengkalis.')">

  {{-- Canonical URL --}}
  <link rel="canonical" href="{{ url()->current() }}">

  {{-- Open Graph --}}
  <meta property="og:type"        content="@yield('og_type', 'website')">
  <meta property="og:site_name"   content="{{ $setting->nama_lembaga ?? 'LAMR Bengkalis' }}">
  <meta property="og:url"         content="{{ url()->current() }}">
  <meta property="og:title"       content="@yield('title', config('app.name', 'LAMR Bengkalis'))">
  <meta property="og:description" content="@yield('meta_description', $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Riau Kabupaten Bengkalis.')">
  <meta property="og:image"       content="@yield('og_image', asset('images/icon-512x512.png'))">
  <meta property="og:locale"      content="id_ID">

  {{-- Twitter Card --}}
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="@yield('title', config('app.name', 'LAMR Bengkalis'))">
  <meta name="twitter:description" content="@yield('meta_description', $setting->meta_deskripsi ?? '')">
  <meta name="twitter:image"       content="@yield('og_image', asset('images/icon-512x512.png'))">

  {{-- PWA Setup & Favicon --}}
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icon-192x192.png') }}">
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/icon-192x192.png') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Alpine.js --}}
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- reCAPTCHA v3 (hanya di halaman kontak) --}}
  @stack('head_scripts')

  {{-- Page-specific styles (e.g. @push('styles') dari tiap view) --}}
  @stack('styles')

  {{-- FOUC Prevention --}}
  <script>
    (function() {
      const theme = localStorage.getItem('lam_theme') || 'light';
      document.documentElement.setAttribute('data-theme', theme);
    })();
  </script>

  <style>
    /* ─── Design Tokens ────────────────────────────────────────── */
    :root {
      /* ── Warna Resmi LAMR Bengkalis ── */
      --lam-gold:    #F99522;
      --lam-gold-d:  #d97c0e;
      --lam-gold-l:  #FFC90E;
      --lam-gold-deep: #D4AF37;

      /* Hijau Tua — kesuburan & nilai keislaman */
      --lam-green:   #1B5E20;
      --lam-green-d: #0d3d10;
      --lam-green-l: #2e7d32;
      --lam-green-nav: #1B5E20;

      /* Merah — teks / aksen */
      --lam-red:     #EB2D3A;
      --lam-red-d:   #c71f2b;

      /* Hitam — dominan layout */
      --lam-black:   #121212;
      --lam-black-d: #080808;
      --lam-black-l: #1e1e1e;

      /* Alias cokelat → hitam (kompatibilitas sub-views) */
      --lam-brown:   #121212;
      --lam-brown-d: #080808;
      --lam-brown-l: #1e1e1e;
      --lam-maroon:  #1e1e1e;

      /* Light Mode */
      --lam-bg:      #f8f8f6;
      --lam-bg-alt:  #ffffff;
      --lam-text:    #111111;
      --lam-text-m:  #333333;
      --lam-text-l:  #555555;
      --lam-cream:   var(--lam-bg);
      --lam-cream-d: var(--lam-bg-alt);

      --lam-border:  rgba(249,149,34,.25);
      --lam-shadow:  0 4px 24px rgba(0,0,0,.10);

      /* Navbar — PUTIH (sesuai mockup) */
      --lam-nav-bg:  #ffffff;
      --lam-nav-text: #1a1a1a;
      --lam-nav-border: #e8e8e8;

      --font-head:   'Playfair Display', Georgia, serif;
      --font-body:   'Inter', system-ui, sans-serif;
      --radius:      .75rem;
      --radius-sm:   .375rem;
      --transition:  .25s ease;
    }

    [data-theme="dark"] {
      --lam-bg:      #111111;
      --lam-bg-alt:  #1a1a1a;
      --lam-text:    #F5F5F5;
      --lam-text-m:  #e0e0e0;
      --lam-text-l:  #a0a0a0;
      --lam-cream:   var(--lam-bg);
      --lam-cream-d: var(--lam-bg-alt);
      --lam-border:  rgba(249,149,34,.2);
      --lam-shadow:  0 4px 24px rgba(0,0,0,.4);
      --lam-nav-bg:  #1a1a1a;
      --lam-nav-text: #f0f0f0;
      --lam-nav-border: rgba(255,255,255,.1);
    }

    /* ─── Reset & Base ─────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body {
      font-family: var(--font-body);
      color: var(--lam-text);
      background: var(--lam-bg);
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

    /* Section Ornament Title */
    .section-ornament-title {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .75rem;
      font-family: var(--font-body);
      font-size: .8rem;
      font-weight: 700;
      letter-spacing: .2em;
      text-transform: uppercase;
      color: var(--lam-green);
      margin-bottom: 2.5rem;
    }
    .section-ornament-title::before,
    .section-ornament-title::after {
      content: '✦';
      color: var(--lam-gold);
      font-size: .7rem;
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
      background: var(--lam-gold); color: var(--lam-black);
      border-color: var(--lam-gold);
    }
    .btn-primary:hover { background: var(--lam-gold-d); border-color: var(--lam-gold-d); color: var(--lam-black); }
    .btn-outline {
      background: transparent; color: var(--lam-green);
      border-color: var(--lam-green);
    }
    .btn-outline:hover { background: var(--lam-green); color: white; }
    .btn-outline-white {
      background: transparent; color: white; border-color: rgba(255,255,255,.6);
    }
    .btn-outline-white:hover { background: rgba(255,255,255,.1); }

    /* ─── NAVBAR (Putih — Sesuai Mockup) ───────────────────────── */
    .navbar {
      position: sticky; top: 0; z-index: 200;
      background: var(--lam-nav-bg);
      box-shadow: 0 2px 16px rgba(0,0,0,.10);
      border-bottom: 1px solid var(--lam-nav-border);
      transition: all var(--transition);
    }

    /* Baris atas: topbar utilitas (language, search, aspirasi) */
    .navbar__topbar {
      border-bottom: 1px solid var(--lam-nav-border);
      background: var(--lam-nav-bg);
    }
    .navbar__topbar-inner {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 1rem;
      height: 40px;
    }

    /* Language switcher */
    .navbar__lang {
      display: flex;
      align-items: center;
      gap: .35rem;
      font-size: .75rem;
      font-weight: 600;
      color: var(--lam-text-l);
    }
    .navbar__lang a {
      color: var(--lam-text-l);
      transition: color .2s;
      padding: .15rem .2rem;
    }
    .navbar__lang a:hover,
    .navbar__lang a.is-active { color: var(--lam-gold); }
    .navbar__lang span { color: #ccc; font-size: .6rem; }

    /* Search bar */
    .navbar__search {
      display: flex;
      align-items: center;
      border: 1px solid #ddd;
      border-radius: 20px;
      overflow: hidden;
      height: 28px;
      background: #f5f5f5;
    }
    [data-theme="dark"] .navbar__search {
      background: rgba(255,255,255,.08);
      border-color: rgba(255,255,255,.15);
    }
    .navbar__search input {
      border: none;
      outline: none;
      background: transparent;
      padding: 0 .75rem;
      font-size: .78rem;
      font-family: var(--font-body);
      color: var(--lam-text);
      width: 180px;
    }
    .navbar__search input::placeholder { color: #aaa; }
    .navbar__search button {
      border: none;
      background: none;
      padding: 0 .6rem;
      cursor: pointer;
      color: var(--lam-text-l);
      display: flex;
      align-items: center;
    }
    .navbar__search button:hover { color: var(--lam-gold); }

    /* Tombol aspirasi di topbar */
    .navbar__aspirasi-top {
      display: inline-flex; align-items: center; gap: .4rem;
      padding: .35rem 1rem;
      background: var(--lam-gold);
      color: #1a1a1a;
      font-size: .75rem;
      font-weight: 700;
      border-radius: 4px;
      transition: background .2s, transform .15s;
      white-space: nowrap;
    }
    .navbar__aspirasi-top:hover {
      background: var(--lam-gold-d);
      transform: translateY(-1px);
    }

    /* Baris bawah: brand + navigasi utama */
    .navbar__main {
      background: var(--lam-nav-bg);
    }
    .navbar__main-inner {
      display: flex;
      align-items: center;
      height: 72px;
      gap: 2rem;
    }

    /* Brand */
    .navbar__brand {
      display: flex;
      align-items: center;
      gap: .875rem;
      flex-shrink: 0;
      text-decoration: none;
    }
    .navbar__brand-logo {
      width: 52px;
      height: 52px;
      border-radius: 4px;
      object-fit: contain;
      flex-shrink: 0;
    }
    .navbar__brand-text {
      display: flex;
      flex-direction: column;
      line-height: 1.15;
    }
    .navbar__brand-name {
      font-family: var(--font-body);
      font-size: .9rem;
      font-weight: 800;
      color: var(--lam-nav-text);
      letter-spacing: .01em;
      text-transform: uppercase;
    }
    .navbar__brand-sub {
      font-size: .72rem;
      font-weight: 600;
      color: var(--lam-nav-text);
      letter-spacing: .01em;
      text-transform: uppercase;
      opacity: .75;
    }

    /* Nav desktop */
    .navbar__nav {
      display: flex;
      align-items: center;
      gap: .25rem;
      flex: 1;
      justify-content: flex-end;
    }

    .navbar__link {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: .28rem;
      padding: .5rem .65rem;
      color: var(--lam-nav-text);
      font-size: .85rem;
      font-weight: 500;
      border-radius: 4px;
      transition: color var(--transition), background var(--transition);
      white-space: nowrap;
    }
    .navbar__link::after {
      content: '';
      position: absolute;
      left: .5rem;
      right: .5rem;
      bottom: 0;
      height: 2.5px;
      background: var(--lam-green-nav);
      border-radius: 2px;
      transform: scaleX(0);
      transition: transform var(--transition);
    }
    .navbar__link:hover { color: var(--lam-green-nav); }
    .navbar__link:hover::after,
    .navbar__link.is-active::after { transform: scaleX(1); }
    .navbar__link.is-active { color: var(--lam-green-nav); font-weight: 600; }

    /* Dropdown */
    .navbar__dropdown {
      position: relative;
    }
    .navbar__dropdown-btn {
      display: inline-flex;
      align-items: center;
      gap: .28rem;
      padding: .5rem .65rem;
      color: var(--lam-nav-text);
      font-size: .85rem;
      font-weight: 500;
      background: none;
      border: none;
      cursor: pointer;
      border-radius: 4px;
      transition: color var(--transition), background var(--transition);
      white-space: nowrap;
      position: relative;
    }
    .navbar__dropdown-btn::after {
      content: '';
      position: absolute;
      left: .5rem;
      right: .5rem;
      bottom: 0;
      height: 2.5px;
      background: var(--lam-green-nav);
      border-radius: 2px;
      transform: scaleX(0);
      transition: transform var(--transition);
    }
    .navbar__dropdown-btn:hover,
    .navbar__dropdown-btn.is-active { color: var(--lam-green-nav); }
    .navbar__dropdown-btn:hover::after,
    .navbar__dropdown-btn.is-active::after { transform: scaleX(1); }
    .navbar__dropdown-btn svg { transition: transform .2s; }
    .navbar__dropdown.is-open .navbar__dropdown-btn svg { transform: rotate(180deg); }

    .navbar__dropdown-menu {
      display: none;
      position: absolute;
      top: calc(100% + .5rem);
      left: 50%;
      transform: translateX(-50%);
      background: white;
      border: 1px solid #e8e8e8;
      border-radius: var(--radius);
      min-width: 220px;
      box-shadow: 0 16px 40px rgba(0,0,0,.15);
      overflow: hidden;
      z-index: 300;
      animation: dropFade .15s ease;
    }
    [data-theme="dark"] .navbar__dropdown-menu {
      background: #1e1e1e;
      border-color: rgba(255,255,255,.1);
    }
    .navbar__dropdown.is-open .navbar__dropdown-menu { display: block; }
    .navbar__dropdown::after {
      content: '';
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      height: 1.5rem;
      display: none;
    }
    .navbar__dropdown.is-open::after { display: block; }
    @keyframes dropFade { from { opacity:0; transform: translateX(-50%) translateY(-8px); } to { opacity:1; transform: translateX(-50%) translateY(0); } }

    .navbar__dropdown-item {
      display: flex;
      align-items: center;
      gap: .65rem;
      padding: .75rem 1rem;
      color: #333;
      font-size: .85rem;
      transition: background var(--transition), color var(--transition);
      border-bottom: 1px solid #f0f0f0;
    }
    [data-theme="dark"] .navbar__dropdown-item { color: rgba(255,255,255,.85); border-bottom-color: rgba(255,255,255,.06); }
    .navbar__dropdown-item:last-child { border-bottom: none; }
    .navbar__dropdown-item:hover { background: rgba(27,94,32,.06); color: var(--lam-green-nav); }
    [data-theme="dark"] .navbar__dropdown-item:hover { background: rgba(249,149,34,.1); color: var(--lam-gold); }
    .navbar__dropdown-item-icon {
      width: 28px; height: 28px;
      background: rgba(27,94,32,.08);
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .navbar__dropdown-item-icon svg { width: 14px; height: 14px; color: var(--lam-green-nav); }
    [data-theme="dark"] .navbar__dropdown-item-icon { background: rgba(249,149,34,.12); }
    [data-theme="dark"] .navbar__dropdown-item-icon svg { color: var(--lam-gold); }
    .navbar__dropdown-item-text { line-height: 1.3; }
    .navbar__dropdown-item-title { font-weight: 600; font-size: .85rem; }
    .navbar__dropdown-item-desc { font-size: .72rem; color: #888; }

    /* Divider & label untuk submenu dengan grup */
    .navbar__dropdown-divider {
      height: 1px;
      background: linear-gradient(to right, transparent, #e0e0e0, transparent);
      margin: .25rem 0;
    }
    [data-theme="dark"] .navbar__dropdown-divider { background: linear-gradient(to right, transparent, rgba(255,255,255,.12), transparent); }
    .navbar__dropdown-label {
      padding: .45rem 1rem .25rem;
      font-size: .65rem;
      font-weight: 800;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--lam-gold);
    }

    /* Item yang mengarah ke situs eksternal (Jejak Layar) */
    .navbar__dropdown-item--ext { opacity: .88; }
    .navbar__dropdown-item--ext::after {
      content: '↗';
      font-size: .65rem;
      margin-left: auto;
      color: var(--lam-gold);
      opacity: .7;
    }
    .navbar__dropdown-item--ext:hover { background: rgba(249,149,34,.08); color: var(--lam-gold-d); }

    /* Perlebar dropdown untuk Adat & Budaya (banyak item) */
    .navbar__dropdown:has(.navbar__dropdown-item--ext) .navbar__dropdown-menu { min-width: 270px; }


    .navbar__hamburger {
      display: none;
      flex-direction: column;
      gap: 5px;
      padding: .5rem;
      cursor: pointer;
      background: none;
      border: none;
    }
    .navbar__hamburger span {
      display: block;
      width: 24px;
      height: 2px;
      background: var(--lam-nav-text);
      border-radius: 2px;
      transition: transform .25s, opacity .25s;
    }

    /* ─── Mobile Menu Panel ──────────────────────────────────── */
    /* Overlay */
    .mobile-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.5);
      z-index: 250;
    }
    .mobile-overlay.is-open { display: block; }

    /* Panel */
    .mobile-panel {
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      width: 90%;
      max-width: 360px;
      background: white;
      z-index: 260;
      transform: translateX(-100%);
      transition: transform .35s cubic-bezier(.4,0,.2,1);
      display: flex;
      flex-direction: column;
      overflow-y: auto;
    }
    [data-theme="dark"] .mobile-panel { background: #1a1a1a; }
    .mobile-panel.is-open { transform: translateX(0); }

    /* Panel header */
    .mobile-panel__header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #f0f0f0;
      background: white;
      flex-shrink: 0;
    }
    [data-theme="dark"] .mobile-panel__header { background: #1a1a1a; border-bottom-color: rgba(255,255,255,.08); }
    .mobile-panel__brand {
      display: flex;
      align-items: center;
      gap: .65rem;
    }
    .mobile-panel__logo {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }
    .mobile-panel__brand-text { line-height: 1.15; }
    .mobile-panel__brand-name {
      font-weight: 800;
      font-size: .8rem;
      color: var(--lam-nav-text);
      text-transform: uppercase;
    }
    .mobile-panel__brand-sub {
      font-size: .65rem;
      color: #777;
      text-transform: uppercase;
    }
    .mobile-panel__close {
      background: none;
      border: none;
      cursor: pointer;
      color: #555;
      padding: .25rem;
    }
    .mobile-panel__close:hover { color: var(--lam-red); }

    /* Panel nav items */
    .mobile-panel__nav {
      flex: 1;
      padding: .5rem 0;
    }
    .mobile-panel__link {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .9rem 1.25rem;
      color: #222;
      font-size: .92rem;
      font-weight: 500;
      border-bottom: 1px solid #f5f5f5;
      transition: background .2s, color .2s;
    }
    [data-theme="dark"] .mobile-panel__link { color: rgba(255,255,255,.88); border-bottom-color: rgba(255,255,255,.06); }
    .mobile-panel__link:hover { background: rgba(27,94,32,.06); color: var(--lam-green-nav); }
    [data-theme="dark"] .mobile-panel__link:hover { background: rgba(249,149,34,.08); color: var(--lam-gold); }
    .mobile-panel__link svg { flex-shrink: 0; opacity: .7; }

    /* Panel accordion */
    .mobile-panel__accordion-btn {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      padding: .9rem 1.25rem;
      background: none;
      border: none;
      border-bottom: 1px solid #f5f5f5;
      cursor: pointer;
      color: #222;
      font-size: .92rem;
      font-weight: 500;
      text-align: left;
      transition: background .2s, color .2s;
    }
    [data-theme="dark"] .mobile-panel__accordion-btn {
      color: rgba(255,255,255,.88);
      border-bottom-color: rgba(255,255,255,.06);
    }
    .mobile-panel__accordion-btn:hover,
    .mobile-panel__accordion-btn.is-open {
      color: var(--lam-green-nav);
      background: rgba(27,94,32,.04);
    }
    [data-theme="dark"] .mobile-panel__accordion-btn:hover,
    [data-theme="dark"] .mobile-panel__accordion-btn.is-open {
      color: var(--lam-gold);
      background: rgba(249,149,34,.06);
    }
    .mobile-panel__accordion-left {
      display: flex;
      align-items: center;
      gap: .75rem;
    }
    .mobile-panel__accordion-chevron {
      transition: transform .25s ease;
      flex-shrink: 0;
      opacity: .5;
    }
    .mobile-panel__accordion-btn.is-open .mobile-panel__accordion-chevron {
      transform: rotate(180deg);
      opacity: 1;
    }
    .mobile-panel__accordion-body {
      display: none;
      flex-direction: column;
      background: #fafafa;
      border-left: 3px solid rgba(27,94,32,.25);
      margin-left: 1.25rem;
    }
    [data-theme="dark"] .mobile-panel__accordion-body { background: rgba(0,0,0,.2); border-left-color: rgba(249,149,34,.3); }
    .mobile-panel__accordion-body.is-open { display: flex; }
    .mobile-panel__sublink {
      display: flex;
      align-items: center;
      gap: .6rem;
      padding: .7rem 1rem;
      color: #444;
      font-size: .85rem;
      border-bottom: 1px solid #f0f0f0;
      transition: color .2s, background .2s;
    }
    [data-theme="dark"] .mobile-panel__sublink { color: rgba(255,255,255,.72); border-bottom-color: rgba(255,255,255,.05); }
    .mobile-panel__sublink:last-child { border-bottom: none; }
    .mobile-panel__sublink:hover { color: var(--lam-green-nav); background: rgba(27,94,32,.05); }
    [data-theme="dark"] .mobile-panel__sublink:hover { color: var(--lam-gold); background: rgba(249,149,34,.06); }

    /* Panel footer section */
    .mobile-panel__footer {
      flex-shrink: 0;
    }
    .mobile-panel__aspirasi {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      margin: 1rem 1.25rem;
      padding: .875rem;
      background: var(--lam-gold);
      color: #1a1a1a;
      font-weight: 700;
      font-size: .9rem;
      border-radius: 8px;
      transition: background .2s;
    }
    .mobile-panel__aspirasi:hover { background: var(--lam-gold-d); }

    .mobile-panel__quote {
      background: #1B5E20;
      padding: 1.5rem 1.25rem;
      text-align: center;
    }
    .mobile-panel__quote-text {
      color: white;
      font-family: var(--font-head);
      font-size: .95rem;
      font-style: italic;
      line-height: 1.6;
      margin-bottom: .75rem;
    }
    .mobile-panel__quote-text::before { content: '\201C'; font-size: 1.5rem; color: var(--lam-gold); }
    .mobile-panel__quote-text::after  { content: '\201D'; font-size: 1.5rem; color: var(--lam-gold); }
    .mobile-panel__socials {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .75rem;
      margin-top: 1rem;
    }
    .mobile-panel__social-link {
      width: 38px;
      height: 38px;
      border: 1.5px solid rgba(255,255,255,.35);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: rgba(255,255,255,.8);
      transition: border-color .2s, color .2s, background .2s;
    }
    .mobile-panel__social-link:hover {
      border-color: var(--lam-gold);
      color: var(--lam-gold);
      background: rgba(255,255,255,.08);
    }

    .mobile-panel__info {
      background: #0d3d10;
      padding: 1.25rem;
    }
    .mobile-panel__info-logo {
      display: flex;
      align-items: center;
      gap: .5rem;
      margin-bottom: .75rem;
    }
    .mobile-panel__info-logo img {
      width: 36px;
      height: 36px;
      object-fit: contain;
    }
    .mobile-panel__info-logo-text { color: var(--lam-gold); font-weight: 800; font-size: .9rem; }
    .mobile-panel__info-logo-sub  { color: rgba(255,255,255,.7); font-size: .65rem; text-transform: uppercase; }
    .mobile-panel__info-desc {
      color: rgba(255,255,255,.72);
      font-size: .8rem;
      line-height: 1.6;
      margin-bottom: .75rem;
    }
    .mobile-panel__info-contact {
      display: flex;
      flex-direction: column;
      gap: .4rem;
    }
    .mobile-panel__info-contact-item {
      display: flex;
      align-items: flex-start;
      gap: .5rem;
      color: rgba(255,255,255,.75);
      font-size: .78rem;
    }
    .mobile-panel__info-contact-item svg {
      flex-shrink: 0;
      margin-top: .1rem;
      color: var(--lam-gold);
    }
    .mobile-panel__copyright {
      background: #080808;
      padding: .75rem 1.25rem;
      text-align: center;
      font-size: .72rem;
      color: rgba(255,255,255,.4);
    }

    /* ─── Responsive: hide desktop nav on mobile ── */
    @media (max-width: 992px) {
      .navbar__topbar { display: none; }
      .navbar__nav { display: none; }
      .navbar__hamburger { display: flex; }
      .navbar__main-inner { justify-content: space-between; }
      .navbar__brand-name { font-size: .82rem; }
    }
    @media (max-width: 600px) {
      .container { padding: 0 1rem; }
    }

    /* ─── Social Media Icons: Uiverse 3D Animation ───────────── */
    .social {
      margin: 0;
      list-style: none;
      padding-left: 0;
      display: flex;
      align-items: center;
    }
    .social .social-item {
      margin-right: 22px;
      width: 38px;
      height: 38px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .social .social-item:last-child { margin-right: 0; }
    .social .social-item .social-link {
      position: relative;
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #fff;
      text-align: center;
      transform: perspective(1000px) rotate(-30deg) skew(25deg) translate(0, 0);
      transition: all 0.4s ease;
      color: var(--hover-color, #b1b1b1);
    }
    .social .social-item .social-link::before {
      content: "";
      position: absolute;
      top: 5px;
      left: -10px;
      height: 100%;
      width: 10px;
      background: #b1b1b1;
      transition: all 0.4s ease;
      transform: rotate(0deg) skewY(-45deg);
    }
    .social .social-item .social-link::after {
      content: "";
      position: absolute;
      top: 38px;
      left: -5px;
      height: 10px;
      width: 100%;
      background: #b1b1b1;
      transition: all 0.4s ease;
      transform: rotate(0deg) skewX(-45deg);
    }
    .social .social-item .social-link:hover {
      transform: perspective(1000px) rotate(-30deg) skew(25deg) translate(5px, -5px);
      box-shadow: -20px 20px 10px rgba(0, 0, 0, 0.5);
      background: var(--hover-color, #b1b1b1);
      color: #ffffff;
    }
    .social .social-item .social-link:hover::before { background: var(--hover-color, #b1b1b1); }
    .social .social-item .social-link:hover::after  { background: var(--hover-color, #b1b1b1); }
    .social .social-item .social-link svg { width: 20px; height: 20px; }
   
    /* ─── Footer ───────────────────────────────────────────────── */
    .footer {
      position: relative;
      background: #111811;
      color: rgba(255,255,255,.8);
      border-top: 4px solid var(--lam-gold);
      padding: 3.5rem 0 0;
    }
    .footer::before {
      content: "";
      position: absolute;
      top: -44px; /* 40px height + 4px border */
      left: 0; right: 0;
      height: 40px;
      background: url('/images/pucuk-rebung.svg') repeat-x center bottom;
      background-size: auto 100%;
      z-index: 20;
    }
    /* Decorative motif corner footer */
    .footer__motif-left {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 220px;
      height: auto;
      opacity: .12;
      pointer-events: none;
    }
    .footer__motif-right {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 220px;
      height: auto;
      opacity: .12;
      transform: scaleX(-1);
      pointer-events: none;
    }

    .footer__grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1.4fr 1.6fr;
      gap: 2.5rem;
      margin-bottom: 2.5rem;
      position: relative;
      z-index: 2;
    }

    /* Col 1 — Brand */
    .footer__brand-logo {
      width: 56px;
      height: 56px;
      object-fit: contain;
      margin-bottom: .75rem;
    }
    .footer__brand-name {
      font-family: var(--font-body);
      font-size: .9rem;
      font-weight: 800;
      color: var(--lam-gold);
      text-transform: uppercase;
      letter-spacing: .02em;
      line-height: 1.2;
      margin-bottom: .25rem;
    }
    .footer__brand-sub {
      font-size: .72rem;
      color: rgba(255,255,255,.6);
      text-transform: uppercase;
      margin-bottom: .75rem;
    }
    .footer__brand-desc {
      font-size: .83rem;
      line-height: 1.7;
      color: rgba(255,255,255,.65);
      margin-bottom: 1rem;
    }

    /* Col headers */
    .footer__col-title {
      font-family: var(--font-body);
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
      color: var(--lam-gold);
      margin-bottom: 1rem;
    }
    .footer__links { display: flex; flex-direction: column; gap: .55rem; }
    .footer__links a {
      font-size: .85rem;
      color: rgba(255,255,255,.7);
      transition: color var(--transition), padding-left var(--transition);
    }
    .footer__links a:hover { color: var(--lam-gold); padding-left: .35rem; }

    /* Contact items */
    .footer__contact-item {
      display: flex;
      align-items: flex-start;
      gap: .6rem;
      font-size: .83rem;
      color: rgba(255,255,255,.72);
      line-height: 1.5;
      margin-bottom: .65rem;
    }
    .footer__contact-item svg {
      flex-shrink: 0;
      margin-top: .1rem;
      color: var(--lam-gold);
    }
    .footer__contact-item a {
      color: rgba(255,255,255,.72);
      transition: color .2s;
    }
    .footer__contact-item a:hover { color: var(--lam-gold); }

    /* Newsletter */
    .footer__newsletter-desc {
      font-size: .83rem;
      color: rgba(255,255,255,.65);
      line-height: 1.65;
      margin-bottom: 1rem;
    }
    .footer__newsletter-form {
      display: flex;
      flex-direction: column;
      gap: .6rem;
    }
    .footer__newsletter-input {
      width: 100%;
      padding: .65rem 1rem;
      border: 1px solid rgba(255,255,255,.2);
      border-radius: 6px;
      background: rgba(255,255,255,.08);
      color: white;
      font-family: var(--font-body);
      font-size: .83rem;
      outline: none;
      transition: border-color .2s;
    }
    .footer__newsletter-input::placeholder { color: rgba(255,255,255,.35); }
    .footer__newsletter-input:focus { border-color: var(--lam-gold); }
    .footer__newsletter-btn {
      width: 100%;
      padding: .65rem 1rem;
      background: var(--lam-gold);
      color: #1a1a1a;
      border: none;
      border-radius: 6px;
      font-weight: 700;
      font-size: .85rem;
      cursor: pointer;
      transition: background .2s, transform .15s;
    }
    .footer__newsletter-btn:hover { background: var(--lam-gold-d); transform: translateY(-1px); }

    /* Footer bottom */
    .footer__divider {
      border: none;
      border-top: 1px solid rgba(255,255,255,.1);
      margin: 0;
      position: relative;
      z-index: 2;
    }
    .footer__bottom {
      padding: 1.25rem 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: .5rem;
      font-size: .78rem;
      color: rgba(255,255,255,.45);
      position: relative;
      z-index: 2;
    }
    .footer__bottom-links {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .footer__bottom-links a {
      color: rgba(255,255,255,.45);
      transition: color .2s;
    }
    .footer__bottom-links a:hover { color: var(--lam-gold); }
    .footer__bottom-links span { color: rgba(255,255,255,.2); }

    @media (max-width: 1100px) {
      .footer__grid { grid-template-columns: 1.5fr 1fr 1fr 1.2fr; gap: 2rem; }
      .footer__grid > *:last-child { grid-column: 1 / -1; }
    }
    @media (max-width: 768px) {
      .footer__grid { grid-template-columns: 1fr 1fr; gap: 1.75rem; }
      .footer__grid > *:first-child { grid-column: 1 / -1; }
    }
    @media (max-width: 480px) {
      .footer__grid { grid-template-columns: 1fr; }
      .footer__grid > * { grid-column: auto; }
      .footer__bottom { flex-direction: column; text-align: center; }
      .footer__bottom-links { flex-wrap: wrap; justify-content: center; }
    }

    /* ─── Card Berita ──────────────────────────────────────────── */
    .card-berita {
      background: var(--lam-bg-alt);
      border-radius: var(--radius);
      border: 1px solid var(--lam-border);
      overflow: hidden;
      box-shadow: var(--lam-shadow);
      transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
      display: flex;
      flex-direction: column;
    }
    .card-berita:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0,0,0,.12);
      border-color: var(--lam-gold);
    }
    .card-berita__img { width: 100%; height: 200px; object-fit: cover; }
    .card-berita__img-placeholder {
      width: 100%; height: 200px;
      background: var(--lam-bg);
      display: flex; align-items: center; justify-content: center;
    }
    .card-berita__body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .card-berita__cat {
      font-size: .7rem; font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--lam-green); margin-bottom: .5rem;
    }
    .card-berita__title {
      font-size: 1rem; color: var(--lam-text); margin-bottom: .75rem;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
      transition: color var(--transition);
    }
    .card-berita:hover .card-berita__title { color: var(--lam-green); }
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



    /* ─── Theme Switch ────────────────────────────────────────────── */
    .theme-switch {
      --toggle-size: 14px;
      --container-width: 5.625em;
      --container-height: 2.5em;
      --container-radius: 6.25em;
      --container-light-bg: #3D7EAE;
      --container-night-bg: #1D1F2C;
      --circle-container-diameter: 3.375em;
      --sun-moon-diameter: 2.125em;
      --sun-bg: #ECCA2F;
      --moon-bg: #C4C9D1;
      --spot-color: #959DB1;
      --circle-container-offset: calc((var(--circle-container-diameter) - var(--container-height)) / 2 * -1);
      --stars-color: #fff;
      --clouds-color: #F3FDFF;
      --back-clouds-color: #AACADF;
      --transition: .5s cubic-bezier(0, -0.02, 0.4, 1.25);
      --circle-transition: .3s cubic-bezier(0, -0.02, 0.35, 1.17);
    }
    .theme-switch, .theme-switch *, .theme-switch *::before, .theme-switch *::after {
      -webkit-box-sizing: border-box; box-sizing: border-box; margin: 0; padding: 0; font-size: var(--toggle-size);
    }
    .theme-switch__container {
      width: var(--container-width); height: var(--container-height);
      background-color: var(--container-light-bg); border-radius: var(--container-radius);
      overflow: hidden; cursor: pointer; position: relative;
      -webkit-box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
      box-shadow: 0em -0.062em 0.062em rgba(0, 0, 0, 0.25), 0em 0.062em 0.125em rgba(255, 255, 255, 0.94);
      transition: var(--transition);
    }
    .theme-switch__container::before {
      content: ""; position: absolute; z-index: 1; inset: 0;
      box-shadow: 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset, 0em 0.05em 0.187em rgba(0, 0, 0, 0.25) inset;
      border-radius: var(--container-radius)
    }
    .theme-switch__checkbox { display: none; }
    .theme-switch__circle-container {
      width: var(--circle-container-diameter); height: var(--circle-container-diameter);
      background-color: rgba(255, 255, 255, 0.1); position: absolute;
      left: var(--circle-container-offset); top: var(--circle-container-offset);
      border-radius: var(--container-radius);
      box-shadow: inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), inset 0 0 0 3.375em rgba(255, 255, 255, 0.1), 0 0 0 0.625em rgba(255, 255, 255, 0.1), 0 0 0 1.25em rgba(255, 255, 255, 0.1);
      display: flex; transition: var(--circle-transition); pointer-events: none;
    }
    .theme-switch__sun-moon-container {
      pointer-events: auto; position: relative; z-index: 2;
      width: var(--sun-moon-diameter); height: var(--sun-moon-diameter);
      margin: auto; border-radius: var(--container-radius); background-color: var(--sun-bg);
      box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #a1872a inset;
      filter: drop-shadow(0.062em 0.125em 0.125em rgba(0, 0, 0, 0.25)) drop-shadow(0em 0.062em 0.125em rgba(0, 0, 0, 0.25));
      overflow: hidden; transition: var(--transition);
    }
    .theme-switch__moon {
      transform: translateX(100%); width: 100%; height: 100%;
      background-color: var(--moon-bg); border-radius: inherit;
      box-shadow: 0.062em 0.062em 0.062em 0em rgba(254, 255, 239, 0.61) inset, 0em -0.062em 0.062em 0em #969696 inset;
      transition: var(--transition); position: relative;
    }
    .theme-switch__spot {
      position: absolute; top: 0.75em; left: 0.312em; width: 0.75em; height: 0.75em;
      border-radius: var(--container-radius); background-color: var(--spot-color);
      box-shadow: 0em 0.0312em 0.062em rgba(0, 0, 0, 0.25) inset;
    }
    .theme-switch__spot:nth-of-type(2) { width: 0.375em; height: 0.375em; top: 0.937em; left: 1.375em; }
    .theme-switch__spot:nth-last-of-type(3) { width: 0.25em; height: 0.25em; top: 0.312em; left: 0.812em; }
    .theme-switch__clouds {
      width: 1.25em; height: 1.25em; background-color: var(--clouds-color);
      border-radius: var(--container-radius); position: absolute; bottom: -0.625em; left: 0.312em;
      box-shadow: 0.937em 0.312em var(--clouds-color), -0.312em -0.312em var(--back-clouds-color), 1.437em 0.375em var(--clouds-color), 0.5em -0.125em var(--back-clouds-color), 2.187em 0 var(--clouds-color), 1.25em -0.062em var(--back-clouds-color), 2.937em 0.312em var(--clouds-color), 2em -0.312em var(--back-clouds-color), 3.625em -0.062em var(--clouds-color), 2.625em 0em var(--back-clouds-color), 4.5em -0.312em var(--clouds-color), 3.375em -0.437em var(--back-clouds-color), 4.625em -1.75em 0 0.437em var(--clouds-color), 4em -0.625em var(--back-clouds-color), 4.125em -2.125em 0 0.437em var(--back-clouds-color);
      transition: 0.5s cubic-bezier(0, -0.02, 0.4, 1.25);
    }
    .theme-switch__stars-container {
      position: absolute; color: var(--stars-color); top: -100%; left: 0.312em;
      width: 2.75em; height: auto; transition: var(--transition);
    }
    .theme-switch__checkbox:checked + .theme-switch__container { background-color: var(--container-night-bg); }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container {
      left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter));
    }
}
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container:hover {
      left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter) - 0.187em)
    }
    .theme-switch__circle-container:hover { left: calc(var(--circle-container-offset) + 0.187em); }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__moon { transform: translate(0); }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__clouds { bottom: -4.062em; }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__stars-container {
      top: 50%; transform: translateY(-50%);
    }    /* ─── Mobile Bottom Nav ────────────────────────────────────── */
    .mobile-bottom-nav {
      display: none;
      position: fixed;
      bottom: 0; left: 0; right: 0;
      background: white;
      box-shadow: 0 -4px 20px rgba(0,0,0,0.06);
      z-index: 1000;
      border-top: 1px solid var(--lam-border);
      padding-bottom: env(safe-area-inset-bottom);
    }
    .mobile-bottom-nav__list {
      display: flex; justify-content: space-around; align-items: center;
      list-style: none; padding: 0.25rem 0; margin: 0;
    }
    .mobile-bottom-nav__item { flex: 1; text-align: center; }
    .mobile-bottom-nav__link {
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      padding: 0.4rem 0; gap: 0.2rem;
      color: var(--lam-text-l); text-decoration: none;
      font-size: 0.65rem; font-weight: 600; font-family: var(--font-body);
      transition: all 0.2s ease;
    }
    .mobile-bottom-nav__link svg { width: 22px; height: 22px; stroke-width: 1.8; transition: all 0.2s ease; }
    .mobile-bottom-nav__link:active { transform: scale(0.95); }
    .mobile-bottom-nav__link.is-active { color: var(--lam-green); font-weight: 700; }
    .mobile-bottom-nav__link.is-active svg { color: var(--lam-green); stroke-width: 2.2; }
    
    @media (max-width: 992px) {
      .mobile-bottom-nav { display: block; }
      body { padding-bottom: calc(64px + env(safe-area-inset-bottom)); }
    }
  </style>
</head>
<body x-data="{ theme: localStorage.getItem('lam_theme') || 'light', mobileOpen: false }"
      @theme-toggled.window="theme = $event.detail; localStorage.setItem('lam_theme', theme); document.documentElement.setAttribute('data-theme', theme)">

{{-- Loading Screen --}}
<x-loading-screen />

{{-- ── Navbar ─────────────────────────────────────────────────────────────── --}}
<header class="navbar" role="banner">

  {{-- Topbar: Language + Search + Aspirasi (Desktop only) --}}
  <div class="navbar__topbar">
    <div class="container navbar__topbar-inner">

      {{-- Language switcher --}}
      <div class="navbar__lang">
        @php $currentLang = session('app_locale', config('app.locale', 'id')); @endphp
        <a href="{{ route('locale.switch', 'id') }}"
           class="{{ $currentLang === 'id' ? 'is-active' : '' }}"
           hreflang="id" aria-label="Bahasa Indonesia">ID</a>
        <span>|</span>
        <a href="{{ route('locale.switch', 'ms') }}"
           class="{{ $currentLang === 'ms' ? 'is-active' : '' }}"
           hreflang="ms" aria-label="Bahasa Melayu">MS</a>
        <span>|</span>
        <a href="{{ route('locale.switch', 'jawi') }}"
           class="{{ $currentLang === 'jawi' ? 'is-active' : '' }}"
           hreflang="ar" aria-label="Tulisan Jawi" style="font-family:serif;font-size:.85rem;">جاوي</a>
      </div>

      {{-- Search bar — mengarah ke halaman pencarian global --}}
      <form action="{{ route('cari') }}" method="GET" class="navbar__search" role="search" id="navbar-search-form">
        <input type="text" name="q" placeholder="{{ __('ui.cari_placeholder') }}" aria-label="Cari konten" value="{{ request('q') }}" id="navbar-search-input">
        <button type="submit" aria-label="{{ __('ui.tombol_cari') }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
          </svg>
        </button>
      </form>

      {{-- Theme Toggle (Desktop) --}}
      <label class="theme-switch" aria-label="Toggle Tema">
        <input type="checkbox" class="theme-switch__checkbox"
               :checked="theme === 'dark'"
               @change="$dispatch('theme-toggled', $event.target.checked ? 'dark' : 'light')">
        <div class="theme-switch__container">
          <div class="theme-switch__clouds"></div>
          <div class="theme-switch__stars-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path></svg>
          </div>
          <div class="theme-switch__circle-container">
            <div class="theme-switch__sun-moon-container">
              <div class="theme-switch__moon">
                <div class="theme-switch__spot"></div>
                <div class="theme-switch__spot"></div>
                <div class="theme-switch__spot"></div>
              </div>
            </div>
          </div>
        </div>
      </label>

      {{-- Tombol Sampaikan Aspirasi --}}
      <a href="{{ route('kontak') }}" class="navbar__aspirasi-top" aria-label="Sampaikan Aspirasi">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Sampaikan Aspirasi
      </a>
    </div>
  </div>

  {{-- Main navbar row: Brand + Nav --}}
  <div class="navbar__main">
    <div class="container navbar__main-inner">

      {{-- Brand --}}
      <a href="{{ route('beranda') }}" class="navbar__brand" aria-label="LAMR Bengkalis — Halaman Utama">
        @if($setting->logo_path)
          <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo LAMR Bengkalis" class="navbar__brand-logo" width="52" height="52">
        @else
          <img src="{{ asset('images/logo-lam.gif') }}" alt="Logo LAMR Bengkalis" class="navbar__brand-logo" width="52" height="52">
        @endif
        <div class="navbar__brand-text">
          <span class="navbar__brand-name">Lembaga Adat Melayu Riau</span>
          <span class="navbar__brand-sub">Kabupaten Bengkalis</span>
        </div>
      </a>

      {{-- Desktop Nav --}}
      <nav class="navbar__nav" role="navigation" aria-label="Navigasi Utama">

        {{-- Mengenal LAMR Dropdown --}}
        <div class="navbar__dropdown" x-data="{ dropOpen: false }" @mouseenter="dropOpen=true" @mouseleave="dropOpen=false" :class="dropOpen ? 'is-open' : ''">
          <button class="navbar__dropdown-btn {{ Request::routeIs('profil') ? 'is-active' : '' }}" :aria-expanded="dropOpen" aria-haspopup="true">
            Mengenal LAMR
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="navbar__dropdown-menu" role="menu">
            <a href="{{ route('profil') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">Sekapur Sirih</span>
              </span>
            </a>
            <a href="{{ route('profil') }}#sejarah" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">Sejarah LAMR</span>
              </span>
            </a>
            <a href="{{ route('profil') }}#visi-misi" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">Visi & Misi</span>
              </span>
            </a>
            <a href="{{ route('profil') }}#struktur" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">Struktur & Kepengurusan</span>
              </span>
            </a>
            <a href="{{ route('profil') }}#tugas" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">Tugas & Fungsi</span>
              </span>
            </a>
            <a href="{{ route('profil') }}#landasan" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">Landasan Hukum</span>
              </span>
            </a>
          </div>
        </div>

        {{-- LAM Kecamatan --}}
        <a href="{{ route('lam-kecamatan.index') }}"
           class="navbar__link {{ Request::routeIs('lam-kecamatan.*') ? 'is-active' : '' }}"
           title="Direktori LAM Kecamatan">LAM Kecamatan</a>

        {{-- Adat & Budaya Dropdown --}}
        <div class="navbar__dropdown" x-data="{ dropOpen: false }" @mouseenter="dropOpen=true" @mouseleave="dropOpen=false" :class="dropOpen ? 'is-open' : ''">
          <button class="navbar__dropdown-btn {{ Request::routeIs('hukum-adat.*','tokoh-adat.*','gelar-adat.*','jejaklayar') ? 'is-active' : '' }}" :aria-expanded="dropOpen" aria-haspopup="true">
            {{ __('ui.nav_adat_budaya') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="navbar__dropdown-menu" role="menu">
            <a href="{{ route('hukum-adat.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg></span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">{{ __('ui.sub_hukum_adat') }}</span>
              </span>
            </a>
            <a href="{{ route('tokoh-adat.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">{{ __('ui.sub_tokoh_adat') }}</span>
              </span>
            </a>
            <a href="{{ route('gelar-adat.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">{{ __('ui.sub_gelar_adat') }}</span>
              </span>
            </a>
            <div class="navbar__dropdown-divider"></div>
            @php 
                $jlBase = \App\Models\SiteSetting::instance(); 
                $jlMeta = json_decode($jlBase->meta_keywords ?? '{}', true);
                $urlMuseum = $jlMeta['url_museum'] ?? '#';
            @endphp
            <div class="navbar__dropdown-label">Situs Digital Eksternal</div>
            <a href="{{ $urlMuseum }}" class="navbar__dropdown-item navbar__dropdown-item--ext" role="menuitem" target="_blank" rel="noopener">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">Jelajahi Jejak Layar</span></span>
            </a>
          </div>
        </div>

        {{-- Informasi & Publikasi --}}
        <div class="navbar__dropdown" x-data="{ dropOpen: false }" @mouseenter="dropOpen=true" @mouseleave="dropOpen=false" :class="dropOpen ? 'is-open' : ''">
          <button class="navbar__dropdown-btn {{ Request::routeIs('berita.*','agenda.*','dokumen.*') ? 'is-active' : '' }}" :aria-expanded="dropOpen" aria-haspopup="true">
            {{ __('ui.nav_informasi') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="navbar__dropdown-menu" role="menu">
            <a href="{{ route('berita.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">{{ __('ui.sub_berita') }}</span></span>
            </a>
            <a href="{{ route('agenda.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">{{ __('ui.sub_agenda') }}</span></span>
            </a>
            <a href="{{ route('dokumen.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">{{ __('ui.sub_dokumen') }}</span></span>
            </a>
            <a href="{{ route('galeri') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">{{ __('ui.sub_galeri') }}</span></span>
            </a>
          </div>
        </div>

        <div class="navbar__dropdown" x-data="{ dropOpen: false }" @mouseenter="dropOpen=true" @mouseleave="dropOpen=false" :class="dropOpen ? 'is-open' : ''">
          <button class="navbar__dropdown-btn {{ Request::routeIs('permohonan-informasi.*','pendidikan.*') ? 'is-active' : '' }}" :aria-expanded="dropOpen" aria-haspopup="true">
            {{ __('ui.nav_layanan') }}
            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="navbar__dropdown-menu" role="menu">
            @if(isset($layanans) && $layanans->count() > 0)
              @foreach($layanans as $layanan)
                @php $isExt = $layanan->url && (str_starts_with($layanan->url,'http://') || str_starts_with($layanan->url,'https://')); @endphp
                <a href="{{ $layanan->url ?: '#' }}" class="navbar__dropdown-item" role="menuitem"
                   @if($isExt) target="_blank" rel="noopener noreferrer" @endif>
                  <span class="navbar__dropdown-item-icon">
                    @if($layanan->jenis_icon === 'image' && $layanan->image)
                      <img src="{{ Storage::url($layanan->image) }}" alt="" style="width:14px;height:14px;object-fit:cover;">
                    @elseif($layanan->icon)
                      <x-dynamic-component :component="$layanan->icon" style="width:14px;height:14px;" aria-hidden="true" />
                    @else
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @endif
                  </span>
                  <span class="navbar__dropdown-item-text">
                    <span class="navbar__dropdown-item-title">{{ $layanan->nama }}</span>
                    @if($layanan->deskripsi)<span class="navbar__dropdown-item-desc">{{ Str::limit($layanan->deskripsi, 40) }}</span>@endif
                  </span>
                </a>
              @endforeach
              <div class="navbar__dropdown-divider"></div>
            @endif
            <a href="{{ route('pendidikan.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">{{ __('ui.sub_pendidikan') }}</span></span>
            </a>
            <a href="{{ route('permohonan-informasi.index') }}" class="navbar__dropdown-item" role="menuitem">
              <span class="navbar__dropdown-item-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg></span>
              <span class="navbar__dropdown-item-text"><span class="navbar__dropdown-item-title">{{ __('ui.sub_permohonan') }}</span></span>
            </a>
          </div>
        </div>

        {{-- Kontak --}}
        <a href="{{ route('kontak') }}" class="navbar__link {{ Request::routeIs('kontak') ? 'is-active' : '' }}">Kontak</a>

      </nav>

      {{-- Hamburger (Mobile) --}}
      <button class="navbar__hamburger" @click="mobileOpen = true"
              :aria-expanded="mobileOpen" aria-controls="mobile-panel"
              aria-label="Buka menu navigasi">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>
</header>

{{-- ── Mobile Overlay & Panel ──────────────────────────────────────────────── --}}
<div class="mobile-overlay" :class="mobileOpen ? 'is-open' : ''" @click="mobileOpen = false" aria-hidden="true"></div>

<nav id="mobile-panel" class="mobile-panel" :class="mobileOpen ? 'is-open' : ''"
     role="navigation" aria-label="Navigasi Mobile">

  {{-- Panel Header --}}
  <div class="mobile-panel__header">
    <div class="mobile-panel__brand">
      @if($setting->logo_path)
        <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo LAMR" class="mobile-panel__logo">
      @else
        <img src="{{ asset('images/logo-lam.gif') }}" alt="Logo LAMR" class="mobile-panel__logo">
      @endif
      <div class="mobile-panel__brand-text">
        <div class="mobile-panel__brand-name">LAMR</div>
        <div class="mobile-panel__brand-sub">Kabupaten Bengkalis</div>
      </div>
    </div>
    <button class="mobile-panel__close" @click="mobileOpen = false" aria-label="Tutup menu">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>

  {{-- Panel Nav --}}
  <div class="mobile-panel__nav">

    {{-- Beranda --}}
    <a href="{{ route('beranda') }}" class="mobile-panel__link" @click="mobileOpen=false">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
      Beranda
    </a>

    {{-- Mengenal LAMR Accordion --}}
    <div x-data="{ open: false }">
      <button class="mobile-panel__accordion-btn" :class="open ? 'is-open' : ''" @click="open = !open" :aria-expanded="open">
        <span class="mobile-panel__accordion-left">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          Mengenal LAMR
        </span>
        <svg class="mobile-panel__accordion-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="mobile-panel__accordion-body" :class="open ? 'is-open' : ''">
        <a href="{{ route('profil') }}" class="mobile-panel__sublink" @click="mobileOpen=false">Sekapur Sirih</a>
        <a href="{{ route('profil') }}#sejarah" class="mobile-panel__sublink" @click="mobileOpen=false">Sejarah LAMR</a>
        <a href="{{ route('profil') }}#falsafah" class="mobile-panel__sublink" @click="mobileOpen=false">Falsafah, Asas & Tujuan</a>
        <a href="{{ route('profil') }}#visi-misi" class="mobile-panel__sublink" @click="mobileOpen=false">Visi & Misi</a>
        <a href="{{ route('profil') }}#struktur" class="mobile-panel__sublink" @click="mobileOpen=false">Struktur & Kepengurusan</a>
        <a href="{{ route('profil') }}#tugas" class="mobile-panel__sublink" @click="mobileOpen=false">Tugas & Fungsi</a>
        <a href="{{ route('profil') }}#landasan" class="mobile-panel__sublink" @click="mobileOpen=false">Landasan Hukum</a>
      </div>
    </div>

    {{-- LAM Kecamatan --}}
    <a href="{{ route('lam-kecamatan.index') }}" class="mobile-panel__link" @click="mobileOpen=false">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
      LAM Kecamatan
    </a>

    {{-- Adat & Budaya Accordion --}}
    <div x-data="{ open: false }">
      <button class="mobile-panel__accordion-btn" :class="open ? 'is-open' : ''" @click="open = !open" :aria-expanded="open">
        <span class="mobile-panel__accordion-left">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
          {{ __('ui.nav_adat_budaya') }}
        </span>
        <svg class="mobile-panel__accordion-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="mobile-panel__accordion-body" :class="open ? 'is-open' : ''">
        <a href="{{ route('hukum-adat.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_hukum_adat') }}</a>
        <a href="{{ route('tokoh-adat.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_tokoh_adat') }}</a>
        <a href="{{ route('gelar-adat.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_gelar_adat') }}</a>
        
        <div style="padding: .5rem 1rem .25rem; font-size: .65rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--lam-gold);">Situs Digital Eksternal</div>
        @php 
            $jlBase = \App\Models\SiteSetting::instance(); 
            $jlMeta = json_decode($jlBase->meta_keywords ?? '{}', true);
            $urlMuseum = $jlMeta['url_museum'] ?? '#';
        @endphp
        <a href="{{ $urlMuseum }}" class="mobile-panel__sublink" @click="mobileOpen=false" target="_blank">Jelajahi Jejak Layar ↗</a>
      </div>
    </div>

    {{-- Informasi & Publikasi Accordion --}}
    <div x-data="{ open: false }">
      <button class="mobile-panel__accordion-btn" :class="open ? 'is-open' : ''" @click="open = !open" :aria-expanded="open">
        <span class="mobile-panel__accordion-left">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
          {{ __('ui.nav_informasi') }}
        </span>
        <svg class="mobile-panel__accordion-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="mobile-panel__accordion-body" :class="open ? 'is-open' : ''">
        <a href="{{ route('berita.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_berita') }}</a>
        <a href="{{ route('agenda.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_agenda') }}</a>
        <a href="{{ route('dokumen.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_dokumen') }}</a>
        <a href="{{ route('galeri') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_galeri') }}</a>
      </div>
    </div>

    {{-- Layanan Publik --}}
    <div x-data="{ open: false }">
      <button class="mobile-panel__accordion-btn" :class="open ? 'is-open' : ''" @click="open = !open" :aria-expanded="open">
        <span class="mobile-panel__accordion-left">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
          {{ __('ui.nav_layanan') }}
        </span>
        <svg class="mobile-panel__accordion-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="mobile-panel__accordion-body" :class="open ? 'is-open' : ''">
        @if(isset($layanans) && $layanans->count() > 0)
          @foreach($layanans as $layanan)
            @php $isExt = $layanan->url && (str_starts_with($layanan->url,'http://') || str_starts_with($layanan->url,'https://')); @endphp
            <a href="{{ $layanan->url ?: '#' }}" class="mobile-panel__sublink"
               @click="mobileOpen=false"
               @if($isExt) target="_blank" rel="noopener noreferrer" @endif>
              {{ $layanan->nama }} @if($isExt) ↗ @endif
            </a>
          @endforeach
          <div style="height: 1px; background: rgba(0,0,0,.05); margin: .25rem 1.5rem;"></div>
        @endif
        <a href="{{ route('pendidikan.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_pendidikan') }}</a>
        <a href="{{ route('permohonan-informasi.index') }}" class="mobile-panel__sublink" @click="mobileOpen=false">{{ __('ui.sub_permohonan') }}</a>
      </div>
    </div>

    {{-- Kontak --}}
    <a href="{{ route('kontak') }}" class="mobile-panel__link" @click="mobileOpen=false">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92v2z"/></svg>
      Kontak
    </a>

    {{-- Theme Toggle --}}
    <div style="display:flex; align-items:center; justify-content:space-between; padding:.9rem 1.25rem; border-bottom:1px solid #f5f5f5;">
      <span style="font-size:.88rem; font-weight:500; color:var(--lam-nav-text);">Ubah Tema</span>
      <label class="theme-switch" aria-label="Toggle Theme">
        <input type="checkbox" class="theme-switch__checkbox"
               :checked="theme === 'dark'"
               @change="$dispatch('theme-toggled', $event.target.checked ? 'dark' : 'light')">
        <div class="theme-switch__container">
          <div class="theme-switch__clouds"></div>
          <div class="theme-switch__stars-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path></svg>
          </div>
          <div class="theme-switch__circle-container">
            <div class="theme-switch__sun-moon-container">
              <div class="theme-switch__moon">
                <div class="theme-switch__spot"></div>
                <div class="theme-switch__spot"></div>
                <div class="theme-switch__spot"></div>
              </div>
            </div>
          </div>
        </div>
      </label>
    </div>

  </div>{{-- /panel__nav --}}

  {{-- Panel Footer --}}
  <div class="mobile-panel__footer">
    <a href="{{ route('kontak') }}" class="mobile-panel__aspirasi" @click="mobileOpen=false">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Sampaikan Aspirasi
    </a>

    <div class="mobile-panel__quote">
      <p class="mobile-panel__quote-text">&nbsp;Adat Bersendikan Syarak, Syarak Bersendikan Kitabullah.&nbsp;</p>
      <div class="mobile-panel__socials">
        @php
          $sosmedMobile = [];
          if ($setting->meta_keywords) {
            $dec = json_decode($setting->meta_keywords, true);
            $sosmedMobile = $dec['sosmed'] ?? [];
          }
          $mobileIcons = [
            'facebook'  => '<path d="M9.19795 21.5H13.198V13.4901H16.8021L17.198 9.50977H13.198V7.5C13.198 6.94772 13.6457 6.5 14.198 6.5H17.198V2.5H14.198C11.4365 2.5 9.19795 4.73858 9.19795 7.5V9.50977H7.19795L6.80206 13.4901H9.19795V21.5Z" fill="currentColor"/>',
            'instagram' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M12 7C9.23858 7 7 9.23858 7 12C7 14.7614 9.23858 17 12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7ZM9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12Z" fill="currentColor"/><path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5 1C2.79086 1 1 2.79086 1 5V19C1 21.2091 2.79086 23 5 23H19C21.2091 23 23 21.2091 23 19V5C23 2.79086 21.2091 1 19 1H5ZM19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" fill="currentColor"/>',
            'youtube'   => '<path d="M6 12C6 15.3137 8.68629 18 12 18C14.6124 18 16.8349 16.3304 17.6586 14H12V10H21.8047V14H21.8C20.8734 18.5645 16.8379 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C15.445 2 18.4831 3.742 20.2815 6.39318L17.0039 8.68815C15.9296 7.06812 14.0895 6 12 6C8.68629 6 6 8.68629 6 12Z" fill="currentColor"/>',
          ];
        @endphp
        @foreach($sosmedMobile as $s)
          @if(!empty($s['url']))
            <a class="mobile-panel__social-link" href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($s['platform'] ?? '') }}">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                {!! $mobileIcons[$s['platform']] ?? '<circle cx="12" cy="12" r="10" fill="currentColor"/>' !!}
              </svg>
            </a>
          @endif
        @endforeach
        {{-- Selalu tampilkan ikon email --}}
        <a class="mobile-panel__social-link" href="mailto:{{ $setting->email_kontak ?? '' }}" aria-label="Email">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
          </svg>
        </a>
      </div>
    </div>

    <div class="mobile-panel__info">
      <div class="mobile-panel__info-logo">
        @if($setting->logo_path)
          <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo LAMR">
        @else
          <img src="{{ asset('images/logo-lam.gif') }}" alt="Logo LAMR">
        @endif
        <div>
          <div class="mobile-panel__info-logo-text">LAMR</div>
          <div class="mobile-panel__info-logo-sub">Kabupaten Bengkalis</div>
        </div>
      </div>
      <p class="mobile-panel__info-desc">Menjaga marwah adat, melestarikan budaya Melayu, membangun peradaban berlandaskan nilai-nilai luhur.</p>
      <div class="mobile-panel__info-contact">
        @if($setting->alamat)
          <div class="mobile-panel__info-contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>{{ $setting->alamat }}</span>
          </div>
        @endif
        @if($setting->no_telp)
          <div class="mobile-panel__info-contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92v2z"/></svg>
            <a href="tel:{{ $setting->no_telp }}" style="color:inherit;">{{ $setting->no_telp }}</a>
          </div>
        @endif
        @if($setting->email_kontak)
          <div class="mobile-panel__info-contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <a href="mailto:{{ $setting->email_kontak }}" style="color:inherit;">{{ $setting->email_kontak }}</a>
          </div>
        @endif
        <div class="mobile-panel__info-contact-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>Senin – Jumat 08.00 – 16.00 WIB</span>
        </div>
      </div>
    </div>

    <div class="mobile-panel__copyright">
      &copy; {{ date('Y') }} LAMR Kabupaten Bengkalis &nbsp;|&nbsp;
      <a href="#" style="color:inherit;">Kebijakan Privasi</a> &nbsp;|&nbsp;
      <a href="#" style="color:inherit;">Syarat & Ketentuan</a>
    </div>
  </div>
</nav>

{{-- ── Konten Halaman ──────────────────────────────────────────────────────── --}}
<main id="main-content" role="main">
  @yield('content')
</main>

{{-- ── Footer ──────────────────────────────────────────────────────────────── --}}
<footer class="footer" role="contentinfo">
  {{-- Motif dekoratif sudut --}}
  <img src="{{ asset('images/motif-kiri.svg') }}" class="footer__motif-left" alt="" aria-hidden="true">
  <img src="{{ asset('images/motif-kiri.svg') }}" class="footer__motif-right" alt="" aria-hidden="true">

  <div class="container">
    <div class="footer__grid">

      {{-- Kolom 1: Brand + Deskripsi + Sosmed --}}
      <div>
        @if($setting->logo_path)
          <img src="{{ Storage::url($setting->logo_path) }}" alt="Logo LAMR Bengkalis" class="footer__brand-logo">
        @else
          <img src="{{ asset('images/logo-lam.gif') }}" alt="Logo LAMR Bengkalis" class="footer__brand-logo">
        @endif
        <p class="footer__brand-name">Lembaga Adat Melayu Riau</p>
        <p class="footer__brand-sub">Kabupaten Bengkalis</p>
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
            'facebook'  => '<path d="M9.19795 21.5H13.198V13.4901H16.8021L17.198 9.50977H13.198V7.5C13.198 6.94772 13.6457 6.5 14.198 6.5H17.198V2.5H14.198C11.4365 2.5 9.19795 4.73858 9.19795 7.5V9.50977H7.19795L6.80206 13.4901H9.19795V21.5Z" fill="currentColor"/>',
            'twitter'   => '<path fill-rule="evenodd" clip-rule="evenodd" d="M8 3C9.10457 3 10 3.89543 10 5V8H16C17.1046 8 18 8.89543 18 10C18 11.1046 17.1046 12 16 12H10V14C10 15.6569 11.3431 17 13 17H16C17.1046 17 18 17.8954 18 19C18 20.1046 17.1046 21 16 21H13C9.13401 21 6 17.866 6 14V5C6 3.89543 6.89543 3 8 3Z" fill="currentColor"/>',
            'youtube'   => '<path d="M6 12C6 15.3137 8.68629 18 12 18C14.6124 18 16.8349 16.3304 17.6586 14H12V10H21.8047V14H21.8C20.8734 18.5645 16.8379 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C15.445 2 18.4831 3.742 20.2815 6.39318L17.0039 8.68815C15.9296 7.06812 14.0895 6 12 6C8.68629 6 6 8.68629 6 12Z" fill="currentColor"/>',
            'instagram' => '<path fill-rule="evenodd" clip-rule="evenodd" d="M12 7C9.23858 7 7 9.23858 7 12C7 14.7614 9.23858 17 12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7ZM9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12Z" fill="currentColor"/><path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5 1C2.79086 1 1 2.79086 1 5V19C1 21.2091 2.79086 23 5 23H19C21.2091 23 23 21.2091 23 19V5C23 2.79086 21.2091 1 19 1H5ZM19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3Z" fill="currentColor"/>',
          ];
        @endphp
        @if(!empty($sosmed))
          <ul class="social" style="margin-top:1.25rem; justify-content:flex-start;">
            @php
              $socialColors = [
                'facebook'  => '#3b5999',
                'twitter'   => '#55acee',
                'youtube'   => '#dd4b39',
                'instagram' => '#e4405f',
              ];
            @endphp
            @foreach($sosmed as $s)
              @if(!empty($s['url']))
                @php $color = $socialColors[$s['platform'] ?? ''] ?? '#b1b1b1'; @endphp
                <li class="social-item" style="--hover-color: {{ $color }};">
                  <a class="social-link" href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($s['platform'] ?? '') }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      {!! $icons[$s['platform']] ?? '<circle cx="12" cy="12" r="10" fill="currentColor"/>' !!}
                    </svg>
                  </a>
                </li>
              @endif
            @endforeach
          </ul>
        @endif
      </div>

      {{-- Kolom 2: Tautan Cepat --}}
      <div>
        <p class="footer__col-title">Tautan Cepat</p>
        <nav class="footer__links" aria-label="Tautan Cepat">
          <a href="{{ route('beranda') }}">Beranda</a>
          <a href="{{ route('profil') }}">Profil Lembaga</a>
          <a href="{{ route('berita.index') }}">Berita & Pengumuman</a>
          <a href="{{ route('galeri') }}">Galeri Foto</a>
          <a href="{{ route('kontak') }}">Kontak</a>
          @if($setting->url_museum)
            <a href="{{ route('museum') }}" target="_blank" rel="noopener noreferrer">Jejak Layar</a>
          @endif
        </nav>
      </div>

      {{-- Kolom 3: Layanan Populer --}}
      <div>
        <p class="footer__col-title">Layanan Populer</p>
        <nav class="footer__links" aria-label="Layanan Populer">
          @if(isset($layanans) && $layanans->count() > 0)
            @foreach($layanans->take(5) as $lFooter)
              @php $isExtF = $lFooter->url && (str_starts_with($lFooter->url,'http://') || str_starts_with($lFooter->url,'https://')); @endphp
              <a href="{{ $lFooter->url ?: '#' }}" @if($isExtF) target="_blank" rel="noopener noreferrer" @endif>
                {{ $lFooter->nama }}
              </a>
            @endforeach
          @else
            <a href="{{ route('kontak') }}">Aspirasi Masyarakat</a>
            <a href="{{ route('kontak') }}">Konsultasi Adat</a>
            <a href="{{ route('kontak') }}">Permohonan Kegiatan</a>
          @endif
        </nav>
      </div>

      {{-- Kolom 4: Kontak Kami --}}
      <div>
        <p class="footer__col-title">Kontak Kami</p>
        @if($setting->alamat)
          <div class="footer__contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <address style="font-style:normal;">{{ $setting->alamat }}</address>
          </div>
        @endif
        @if($setting->no_telp)
          <div class="footer__contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 14.92v2z"/></svg>
            <a href="tel:{{ $setting->no_telp }}">{{ $setting->no_telp }}</a>
          </div>
        @endif
        @if($setting->email_kontak)
          <div class="footer__contact-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <a href="mailto:{{ $setting->email_kontak }}">{{ $setting->email_kontak }}</a>
          </div>
        @endif
        <div class="footer__contact-item">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>Senin – Jumat 08.00 – 16.00 WIB</span>
        </div>
      </div>

      {{-- Kolom 5: Newsletter --}}
      <div id="newsletter">
        <p class="footer__col-title">Berlangganan Berita</p>
        <p class="footer__newsletter-desc">
          Dapatkan informasi terbaru dari LAMR Bengkalis langsung ke email Anda.
        </p>
        
        @if(session('subscriber_success'))
          <div style="background: rgba(0, 128, 0, 0.1); color: #4ade80; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600;">
            {{ session('subscriber_success') }}
          </div>
        @endif
        
        @if(session('subscriber_error'))
          <div style="background: rgba(255, 0, 0, 0.1); color: #f87171; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600;">
            {{ session('subscriber_error') }}
          </div>
        @endif

        <form action="{{ route('subscribe') }}" method="POST" class="footer__newsletter-form" aria-label="Form berlangganan">
          @csrf
          <input type="email" name="email" class="footer__newsletter-input" placeholder="Email Anda" required aria-label="Alamat email">
          <button type="submit" class="footer__newsletter-btn">Berlangganan</button>
        </form>
      </div>

    </div>{{-- /footer__grid --}}

    <hr class="footer__divider">

    <div class="footer__bottom">
      <span>&copy; {{ $setting->tahun_berdiri ?? date('Y') }}–{{ date('Y') }} {{ $setting->nama_lembaga ?? 'LAMR Bengkalis' }}. Hak Cipta Dilindungi.</span>
      <div class="footer__bottom-links">
        <a href="#">Kebijakan Privasi</a>
        <span>|</span>
        <a href="#">Syarat & Ketentuan</a>
        <span>|</span>
        <a href="{{ route('sitemap') }}">Peta Situs</a>
      </div>
    </div>
  </div>
</footer>



{{-- ── PWA Install Prompt & Script ────────────────────────────────────────────────── --}}
<div id="pwa-install-banner" style="display: none; position: fixed; bottom: 0; left: 0; width: 100%; background: #111811; color: #fff; padding: 1rem 1.5rem; z-index: 9999; border-top: 2px solid var(--lam-gold); box-shadow: 0 -5px 20px rgba(0,0,0,0.5); justify-content: space-between; align-items: center;">
  <div style="display: flex; align-items: center; gap: 1rem;">
    <img src="{{ asset('images/icon-192x192.png') }}" alt="Icon" style="width: 48px; height: 48px; border-radius: 12px;">
    <div>
      <div style="font-weight: bold; font-family: var(--font-head); font-size: 1.1rem; color: var(--lam-gold);">LAMR Bengkalis</div>
      <div style="font-size: 0.8rem; color: #ccc;">Install aplikasi untuk akses lebih cepat dan mudah</div>
    </div>
  </div>
  <div style="display: flex; gap: 0.75rem; align-items: center;">
    <button id="pwa-close-btn" style="background: none; border: none; color: #aaa; font-size: 0.9rem; cursor: pointer; padding: 0.5rem;">Nanti</button>
    <button id="pwa-install-btn" style="background: var(--lam-gold); color: var(--lam-black); border: none; padding: 0.5rem 1rem; border-radius: 6px; font-weight: bold; font-size: 0.9rem; cursor: pointer;">Install</button>
  </div>
</div>

<script>
  // 1. Register Service Worker
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('/sw.js').catch(err => {
        console.log('ServiceWorker registration failed: ', err);
      });
    });
  }

  // 2. Custom Install Prompt Logic
  (function() {
    if (window.lamPwaInitialized) return;
    window.lamPwaInitialized = true;

    window.deferredPrompt = window.deferredPrompt || null;
    const pwaBanner = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const closeBtn = document.getElementById('pwa-close-btn');

    const hasDeclined = localStorage.getItem('lam_pwa_declined');

    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      window.deferredPrompt = e;
      if (!hasDeclined && !window.matchMedia('(display-mode: standalone)').matches) {
        if(pwaBanner) pwaBanner.style.display = 'flex';
      }
    });

    if(installBtn) {
      installBtn.addEventListener('click', async () => {
        if(pwaBanner) pwaBanner.style.display = 'none';
        if (window.deferredPrompt) {
          window.deferredPrompt.prompt();
          const { outcome } = await window.deferredPrompt.userChoice;
          window.deferredPrompt = null;
        }
      });
    }

    if(closeBtn) {
      closeBtn.addEventListener('click', () => {
        if(pwaBanner) pwaBanner.style.display = 'none';
        localStorage.setItem('lam_pwa_declined', 'true');
      });
    }

    window.addEventListener('appinstalled', () => {
      if(pwaBanner) pwaBanner.style.display = 'none';
      window.deferredPrompt = null;
    });
  })();
</script>

@stack('body_scripts')

{{-- Development Popup --}}
<x-development-popup />

{{-- Mobile Bottom Navigation --}}
<nav class="mobile-bottom-nav" aria-label="Navigasi Bawah">
  <ul class="mobile-bottom-nav__list">
    <li class="mobile-bottom-nav__item">
      <a href="{{ route('beranda') }}" class="mobile-bottom-nav__link {{ request()->routeIs('beranda') ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        <span>Beranda</span>
      </a>
    </li>
    <li class="mobile-bottom-nav__item">
      <a href="{{ route('berita.index') }}" class="mobile-bottom-nav__link {{ request()->routeIs('berita.*') ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v1m2 13a2 2 0 0 1-2-2V7m2 13a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
        <span>Berita</span>
      </a>
    </li>
    <li class="mobile-bottom-nav__item">
      <a href="{{ route('agenda.index') }}" class="mobile-bottom-nav__link {{ request()->routeIs('agenda.*') ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
        <span>Agenda</span>
      </a>
    </li>
    <li class="mobile-bottom-nav__item">
      <a href="{{ route('galeri') }}" class="mobile-bottom-nav__link {{ request()->routeIs('galeri') ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        <span>Galeri</span>
      </a>
    </li>
    <li class="mobile-bottom-nav__item">
      <a href="{{ route('cari') }}" class="mobile-bottom-nav__link {{ request()->routeIs('cari') ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <span>Cari</span>
      </a>
    </li>
  </ul>
</nav>

</body>
</html>
