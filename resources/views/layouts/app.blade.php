<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#121212">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- SEO --}}
  <title>@yield('title', config('app.name', 'LAM Bengkalis'))</title>
  <meta name="description" content="@yield('meta_description', $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Kabupaten Bengkalis.')">

  {{-- Canonical URL --}}
  <link rel="canonical" href="{{ url()->current() }}">

  {{-- Open Graph (Google + Sosial Media) --}}
  <meta property="og:type"        content="@yield('og_type', 'website')">
  <meta property="og:site_name"   content="{{ $setting->nama_lembaga ?? 'LAM Bengkalis' }}">
  <meta property="og:url"         content="{{ url()->current() }}">
  <meta property="og:title"       content="@yield('title', config('app.name', 'LAM Bengkalis'))">
  <meta property="og:description" content="@yield('meta_description', $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Kabupaten Bengkalis.')">
  <meta property="og:image"       content="@yield('og_image', asset('images/icon-512x512.png'))">
  <meta property="og:locale"      content="id_ID">

  {{-- Twitter Card --}}
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="@yield('title', config('app.name', 'LAM Bengkalis'))">
  <meta name="twitter:description" content="@yield('meta_description', $setting->meta_deskripsi ?? '')">
  <meta name="twitter:image"       content="@yield('og_image', asset('images/icon-512x512.png'))">

  {{-- PWA Setup --}}
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/icon-192x192.png') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  {{-- Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  {{-- Alpine.js --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  {{-- reCAPTCHA v3 (hanya di halaman kontak) --}}
  @stack('head_scripts')

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
      /* ── Warna Resmi LAM Bengkalis ── */
      /* Kuning Emas — kebesaran & kemuliaan */
      --lam-gold:    #F99522;
      --lam-gold-d:  #d97c0e;
      --lam-gold-l:  #FFC90E;
      /* Hijau Tua — kesuburan & nilai keislaman */
      --lam-green:   #008000;
      --lam-green-d: #006600;
      --lam-green-l: #009900;
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

      /* Navbar — SELALU hitam (tidak berubah di mode terang/gelap) */
      --lam-nav-bg:  rgba(12, 12, 12, 0.97);

      --font-head:   'Playfair Display', Georgia, serif;
      --font-body:   'Inter', system-ui, sans-serif;
      --radius:      .75rem;
      --radius-sm:   .375rem;
      --transition:  .25s ease;
    }

    [data-theme="dark"] {
      /* Dark Mode — background gelap, teks terang */
      --lam-bg:      #111111;
      --lam-bg-alt:  #1a1a1a;
      --lam-text:    #F5F5F5;
      --lam-text-m:  #e0e0e0;
      --lam-text-l:  #a0a0a0;
      --lam-cream:   var(--lam-bg);
      --lam-cream-d: var(--lam-bg-alt);

      --lam-border:  rgba(249,149,34,.2);
      --lam-shadow:  0 4px 24px rgba(0,0,0,.4);

      /* Navbar tetap hitam di dark mode juga */
      --lam-nav-bg:  rgba(8, 8, 8, 0.98);
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
      font-size: clamp(1.75rem, 3.5vw, 2.5rem); color: var(--lam-gold);
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
      background: var(--lam-gold); color: var(--lam-black);
      border-color: var(--lam-gold);
    }
    .btn-primary:hover { background: var(--lam-gold-d); border-color: var(--lam-gold-d); color: var(--lam-black); }
    .btn-outline {
      background: transparent; color: var(--lam-gold);
      border-color: var(--lam-gold);
    }
    .btn-outline:hover { background: var(--lam-gold); color: var(--lam-black); }
    .btn-outline-white {
      background: transparent; color: white; border-color: rgba(255,255,255,.6);
    }
    .btn-outline-white:hover { background: rgba(255,255,255,.1); }

    /* ─── Navbar ───────────────────────────────────────────────── */
    .navbar {
      position: sticky; top: 0; z-index: 100;
      background: var(--lam-nav-bg);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      box-shadow: 0 4px 20px rgba(0,0,0,.5);
      border-bottom: 1px solid var(--lam-border);
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
      box-shadow: 0 2px 10px rgba(212,160,23,.2); /* gold shadow */
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
      font-size: .7rem; color: rgba(255,255,255,.7);
      letter-spacing: .15em; text-transform: uppercase;
      margin-top: .1rem;
    }

    .navbar__nav {
      display: flex; align-items: center; gap: 1.5rem;
    }
    .navbar__link {
      position: relative;
      padding: .5rem 0; 
      color: rgba(255,255,255,.85); font-size: .9rem; font-weight: 500;
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
    /* Dropdown Layanan */
    .navbar__dropdown {
      position: relative;
    }
    .navbar__dropdown-btn {
      display: flex; align-items: center; gap: .35rem;
      padding: .5rem 0;
      color: rgba(255,255,255,.85); font-size: .9rem; font-weight: 500;
      background: none; border: none; cursor: pointer;
      transition: color var(--transition);
    }
    .navbar__dropdown-btn:hover,
    .navbar__dropdown-btn.is-active {
      color: var(--lam-gold);
    }
    .navbar__dropdown-btn svg { transition: transform .2s; }
    .navbar__dropdown.is-open .navbar__dropdown-btn svg { transform: rotate(180deg); }
    .navbar__dropdown-menu {
      display: none;
      position: absolute; top: calc(100% + .75rem); left: 50%;
      transform: translateX(-50%);
      background: rgba(12,12,12,.98);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(249,149,34,.2);
      border-radius: var(--radius);
      min-width: 200px;
      box-shadow: 0 16px 40px rgba(0,0,0,.6);
      overflow: hidden;
      z-index: 200;
      animation: dropFade .15s ease;
    }
    .navbar__dropdown.is-open .navbar__dropdown-menu { display: block; }
    @keyframes dropFade { from { opacity:0; transform: translateX(-50%) translateY(-6px); } to { opacity:1; transform: translateX(-50%) translateY(0); } }
    .navbar__dropdown-item {
      display: flex; align-items: center; gap: .65rem;
      padding: .75rem 1.1rem;
      color: rgba(255,255,255,.8); font-size: .88rem;
      transition: background var(--transition), color var(--transition);
      border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .navbar__dropdown-item:last-child { border-bottom: none; }
    .navbar__dropdown-item:hover { background: rgba(249,149,34,.12); color: var(--lam-gold); }
    .navbar__dropdown-item-icon {
      width: 28px; height: 28px;
      background: rgba(249,149,34,.15);
      border-radius: 6px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .navbar__dropdown-item-icon svg { width: 14px; height: 14px; color: var(--lam-gold); }
    .navbar__dropdown-item-text { line-height: 1.3; }
    .navbar__dropdown-item-title { font-weight: 600; font-size: .88rem; }
    .navbar__dropdown-item-desc { font-size: .73rem; color: rgba(255,255,255,.45); }

    .navbar__hamburger {
      display: none; flex-direction: column; gap: 5px;
      padding: .5rem; cursor: pointer; background: none; border: none;
    }
    .navbar__hamburger span {
      display: block; width: 24px; height: 2px;
      background: var(--lam-gold); border-radius: 2px;
      transition: transform .25s, opacity .25s;
    }

    /* Mobile nav */
    .navbar__mobile {
      display: none; flex-direction: column;
      background: var(--lam-nav-bg);
      backdrop-filter: blur(10px);
      padding: 1rem 0;
      border-top: 1px solid var(--lam-border);
    }
    .navbar__mobile-link {
      padding: .85rem 1.5rem; color: rgba(255,255,255,.85);
      font-size: .95rem; font-weight: 500;
      transition: color var(--transition), background var(--transition);
      border-left: 3px solid transparent;
      display: flex; align-items: center;
    }
    .navbar__mobile-link:hover { 
      color: var(--lam-gold); 
      background: rgba(255,255,255,.02); 
      border-left-color: var(--lam-gold);
    }
    .navbar__mobile-museum-btn {
      margin: 1rem 1.5rem .5rem;
      padding: .75rem; text-align: center;
      background: var(--lam-gold); color: var(--lam-black);
      border-radius: var(--radius-sm); font-weight: 700;
      display: flex; align-items: center; justify-content: center; gap: .5rem;
      box-shadow: 0 4px 15px rgba(212, 160, 23, 0.2);
    }

    /* Mobile Layanan Accordion */
    .navbar__mobile-accordion {
      display: flex; flex-direction: column;
    }
    .navbar__mobile-accordion-btn {
      display: flex; align-items: center; justify-content: space-between;
      padding: .85rem 1.5rem;
      color: rgba(255,255,255,.85);
      font-size: .95rem; font-weight: 500;
      background: none; border: none; cursor: pointer; width: 100%;
      text-align: left;
      transition: color var(--transition), background var(--transition);
      border-left: 3px solid transparent;
    }
    .navbar__mobile-accordion-btn:hover,
    .navbar__mobile-accordion-btn.is-open {
      color: var(--lam-gold);
      background: rgba(255,255,255,.02);
      border-left-color: var(--lam-gold);
    }
    .navbar__mobile-accordion-btn .acc-label {
      display: flex; align-items: center; gap: .5rem;
    }
    .navbar__mobile-accordion-btn .acc-chevron {
      transition: transform .25s ease;
      flex-shrink: 0;
      color: rgba(255,255,255,.45);
    }
    .navbar__mobile-accordion-btn.is-open .acc-chevron {
      transform: rotate(180deg);
      color: var(--lam-gold);
    }
    .navbar__mobile-accordion-body {
      display: none;
      flex-direction: column;
      background: rgba(0,0,0,.25);
      border-left: 3px solid rgba(249,149,34,.3);
      margin-left: 1.5rem;
      border-radius: 0 0 var(--radius-sm) var(--radius-sm);
      overflow: hidden;
    }
    .navbar__mobile-accordion-body.is-open {
      display: flex;
      animation: accFade .2s ease;
    }
    @keyframes accFade {
      from { opacity:0; transform: translateY(-6px); }
      to   { opacity:1; transform: translateY(0); }
    }
    .navbar__mobile-sublink {
      display: flex; align-items: center; gap: .75rem;
      padding: .7rem 1rem;
      color: rgba(255,255,255,.75); font-size: .88rem;
      transition: color var(--transition), background var(--transition);
      border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .navbar__mobile-sublink:last-child { border-bottom: none; }
    .navbar__mobile-sublink:hover {
      color: var(--lam-gold);
      background: rgba(249,149,34,.08);
    }
    .navbar__mobile-sublink-icon {
      width: 32px; height: 32px;
      background: rgba(249,149,34,.12);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      overflow: hidden;
    }
    .navbar__mobile-sublink-icon img {
      width: 100%; height: 100%; object-fit: cover;
    }
    .navbar__mobile-sublink-icon svg {
      width: 16px; height: 16px; color: var(--lam-gold);
    }
    .navbar__mobile-sublink-text {
      display: flex; flex-direction: column; line-height: 1.3;
    }
    .navbar__mobile-sublink-title {
      font-weight: 600; font-size: .88rem;
    }
    .navbar__mobile-sublink-desc {
      font-size: .73rem; color: rgba(255,255,255,.4); margin-top: .1rem;
    }

    @media (max-width: 768px) {
      .navbar__nav { display: none; }
      .navbar__hamburger { display: flex; }
      .navbar__mobile.is-open { display: flex; }
    }

    @media (max-width: 600px) {
      .container { padding: 0 1rem; }
    }

    /* Uiverse Social Buttons for Footer */
    .social {
      margin: 0;
      list-style: none;
      padding-left: 0;
      display: flex;
      align-items: center;
    }
    .social .social-item {
      margin-right: 25px; /* Adjust spacing to fit footer */
      width: 35px;
      height: 35px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
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
      color: var(--hover-color);
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
      top: 35px;
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
      background: var(--hover-color);
      color: #ffffff;
    }
    .social .social-item .social-link:hover::before {
      background: var(--hover-color);
    }
    .social .social-item .social-link:hover::after {
      background: var(--hover-color);
    }
    .social .social-item .social-link svg {
      width: 18px;
      height: 18px;
    }

    /* ─── Footer ───────────────────────────────────────────────── */
    .footer {
      position: relative;
      background: var(--lam-black-d); color: var(--lam-text-m);
      border-top: 4px solid var(--lam-gold);
      padding: 3.5rem 0 1.5rem;
    }
    .footer::before {
      content: "";
      position: absolute;
      top: -44px; /* Move above the 4px top border (40px height + 4px border = 44px) */
      left: 0;
      right: 0;
      height: 40px;
      background: url('/images/pucuk-rebung.svg') repeat-x center;
      background-size: auto 100%;
      z-index: 10; /* Ensure it overlays the section above */
      /* transform: rotate(180deg); */
    }
    .footer__grid {
      display: grid; grid-template-columns: 2fr 1fr 1fr;
      gap: 2.5rem; margin-bottom: 2.5rem;
      position: relative;
      z-index: 2; /* Ensure content is above the border if they overlap */
    }
    .footer__brand-name {
      font-family: var(--font-head); font-size: 1.1rem;
      color: var(--lam-gold); margin-bottom: .5rem;
    }
    .footer__brand-desc { font-size: .85rem; line-height: 1.7; color: rgba(255,255,255,.8); }
    .footer__col-title {
      font-family: var(--font-body); font-size: .75rem; font-weight: 600;
      letter-spacing: .15em; text-transform: uppercase;
      color: var(--lam-gold); margin-bottom: 1rem;
    }
    .footer__links { display: flex; flex-direction: column; gap: .5rem; }
    .footer__links a {
      font-size: .875rem; color: rgba(255,255,255,.8);
      transition: color var(--transition);
    }
    .footer__links a:hover { color: var(--lam-gold); }
    .footer__bottom {
      border-top: 1px solid rgba(255,255,255,.1);
      padding-top: 1.5rem; font-size: .8rem;
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: .5rem; color: rgba(255,255,255,.6);
    }
    @media (max-width: 768px) {
      .footer__grid { grid-template-columns: 1fr; }
      .footer__bottom { flex-direction: column; text-align: center; }
    }

    /* ─── Card Berita ──────────────────────────────────────────── */
    .card-berita {
      background: var(--lam-black-l); border-radius: var(--radius);
      border: 1px solid var(--lam-border);
      overflow: hidden; box-shadow: var(--lam-shadow);
      transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
      display: flex; flex-direction: column;
    }
    .card-berita:hover { 
      transform: translateY(-4px); 
      box-shadow: 0 12px 40px rgba(0,0,0,.8); 
      border-color: var(--lam-gold); 
    }
    .card-berita__img { width: 100%; height: 200px; object-fit: cover; }
    .card-berita__img-placeholder {
      width: 100%; height: 200px; background: var(--lam-black-d);
      display: flex; align-items: center; justify-content: center;
    }
    .card-berita__body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .card-berita__cat {
      font-size: .7rem; font-weight: 700; letter-spacing: .12em;
      text-transform: uppercase; color: var(--lam-gold); margin-bottom: .5rem;
    }
    .card-berita__title {
      font-size: 1rem; color: #F5F5F5; margin-bottom: .75rem;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
      transition: color var(--transition);
    }
    .card-berita:hover .card-berita__title { color: var(--lam-gold); }
    .card-berita__excerpt {
      font-size: .85rem; color: rgba(255,255,255,.8); flex: 1;
      display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
    }
    .card-berita__meta {
      margin-top: 1rem; padding-top: .75rem;
      border-top: 1px solid rgba(255,255,255,.1);
      font-size: .78rem; color: rgba(255,255,255,.6);
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
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__circle-container:hover {
      left: calc(100% - var(--circle-container-offset) - var(--circle-container-diameter) - 0.187em)
    }
    .theme-switch__circle-container:hover { left: calc(var(--circle-container-offset) + 0.187em); }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__moon { transform: translate(0); }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__clouds { bottom: -4.062em; }
    .theme-switch__checkbox:checked + .theme-switch__container .theme-switch__stars-container {
      top: 50%; transform: translateY(-50%);
    }
  </style>
</head>
<body x-data="{ theme: localStorage.getItem('lam_theme') || 'light' }" 
      @theme-toggled.window="theme = $event.detail; localStorage.setItem('lam_theme', theme); document.documentElement.setAttribute('data-theme', theme)">

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
      <a href="{{ route('galeri') }}"       class="navbar__link {{ Request::routeIs('galeri')       ? 'is-active' : '' }}">Galeri</a>
      <a href="{{ route('kontak') }}"       class="navbar__link {{ Request::routeIs('kontak')       ? 'is-active' : '' }}">Kontak</a>

      {{-- Dropdown Layanan --}}
      @if(isset($layanans) && $layanans->count() > 0)
      <div class="navbar__dropdown" x-data="{ dropOpen: false }" @mouseenter="dropOpen=true" @mouseleave="dropOpen=false" :class="dropOpen ? 'is-open' : ''">
        <button class="navbar__dropdown-btn" :aria-expanded="dropOpen" aria-haspopup="true">
          Layanan
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
        </button>
        <div class="navbar__dropdown-menu" role="menu">
          @foreach($layanans as $layanan)
            @php $isExt = $layanan->url && (str_starts_with($layanan->url,'http://') || str_starts_with($layanan->url,'https://')); @endphp
            <a href="{{ $layanan->url ?: '#' }}"
               class="navbar__dropdown-item"
               role="menuitem"
               @if($isExt) target="_blank" rel="noopener noreferrer" @endif>
              <span class="navbar__dropdown-item-icon">
                @if($layanan->jenis_icon === 'image' && $layanan->image)
                  <img src="{{ Storage::url($layanan->image) }}" alt="" style="width:24px;height:24px;border-radius:4px;object-fit:cover;">
                @elseif($layanan->icon)
                  <x-dynamic-component :component="$layanan->icon" style="width:24px;height:24px;" aria-hidden="true" />
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true" style="width:24px;height:24px;"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                @endif
              </span>
              <span class="navbar__dropdown-item-text">
                <span class="navbar__dropdown-item-title">{{ $layanan->nama }}</span>
                @if($layanan->deskripsi)
                  <span class="navbar__dropdown-item-desc">{{ Str::limit($layanan->deskripsi, 45) }}</span>
                @endif
              </span>
            </a>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Theme Toggle (Desktop) --}}
      <label class="theme-switch" style="display:inline-block; vertical-align:middle; margin-left: 1rem;" aria-label="Toggle Theme">
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
    <a href="{{ route('galeri') }}"       class="navbar__mobile-link" @click="open=false">Galeri</a>
    <a href="{{ route('kontak') }}"       class="navbar__mobile-link" @click="open=false">Kontak</a>

    {{-- Layanan Mobile (Accordion Submenu) --}}
    @if(isset($layanans) && $layanans->count() > 0)
      <div class="navbar__mobile-accordion" x-data="{ layananOpen: false }">
        {{-- Accordion Toggle Button --}}
        <button
          class="navbar__mobile-accordion-btn"
          :class="layananOpen ? 'is-open' : ''"
          @click="layananOpen = !layananOpen"
          :aria-expanded="layananOpen"
          aria-controls="mobile-layanan-body"
        >
          <span class="acc-label">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true"
                 style="color:var(--lam-gold);">
              <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            Layanan
          </span>
          <svg class="acc-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </button>

        {{-- Accordion Body --}}
        <div
          id="mobile-layanan-body"
          class="navbar__mobile-accordion-body"
          :class="layananOpen ? 'is-open' : ''"
          role="menu"
        >
          @foreach($layanans as $layanan)
            @php $isExt = $layanan->url && (str_starts_with($layanan->url,'http://') || str_starts_with($layanan->url,'https://')); @endphp
            <a
              href="{{ $layanan->url ?: '#' }}"
              class="navbar__mobile-sublink"
              role="menuitem"
              @click="open=false; layananOpen=false"
              @if($isExt) target="_blank" rel="noopener noreferrer" @endif
            >
              {{-- Ikon / Gambar dari database --}}
              <span class="navbar__mobile-sublink-icon">
                @if($layanan->jenis_icon === 'image' && $layanan->image)
                  <img src="{{ Storage::url($layanan->image) }}" alt="{{ $layanan->nama }}">
                @elseif($layanan->icon)
                  <x-dynamic-component :component="$layanan->icon" aria-hidden="true" />
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                       stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                @endif
              </span>

              {{-- Teks Layanan --}}
              <span class="navbar__mobile-sublink-text">
                <span class="navbar__mobile-sublink-title">
                  {{ $layanan->nama }}
                  @if($isExt)
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none"
                         stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         style="display:inline;margin-left:.2rem;opacity:.5;">
                      <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                      <polyline points="15 3 21 3 21 9"/>
                      <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                  @endif
                </span>
                @if($layanan->deskripsi)
                  <span class="navbar__mobile-sublink-desc">{{ Str::limit($layanan->deskripsi, 50) }}</span>
                @endif
              </span>
            </a>
          @endforeach
        </div>
      </div>
    @endif
    
    {{-- Theme Toggle (Mobile) --}}
    <div style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
      <span style="color: rgba(255,255,255,.85); font-size: .95rem; font-weight: 500;">Ubah Tema</span>
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
          <ul class="social" style="margin-top:1.5rem; justify-content:flex-start;">
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
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                      {!! $icons[$s['platform']] ?? '<circle cx="12" cy="12" r="10"/>' !!}
                    </svg>
                  </a>
                </li>
              @endif
            @endforeach
          </ul>
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
      <span style="font-size:.75rem;opacity:.6;">Sistem Informasi LAM Bengkalis &bull; Supported by <span style="color:var(--lam-gold);font-weight:600;">Tim JejakLayar</span></span>
    </div>
  </div>
</footer>

{{-- ── PWA Install Prompt & Script ────────────────────────────────────────────────── --}}
<div id="pwa-install-banner" style="display: none; position: fixed; bottom: 0; left: 0; width: 100%; background: var(--lam-black-d); color: #fff; padding: 1rem 1.5rem; z-index: 9999; border-top: 2px solid var(--lam-gold); box-shadow: 0 -5px 20px rgba(0,0,0,0.5); justify-content: space-between; align-items: center;">
  <div style="display: flex; align-items: center; gap: 1rem;">
    <img src="{{ asset('images/icon-192x192.png') }}" alt="Icon" style="width: 48px; height: 48px; border-radius: 12px;">
    <div>
      <div style="font-weight: bold; font-family: var(--font-head); font-size: 1.1rem; color: var(--lam-gold);">LAM Bengkalis</div>
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

    // Cek apakah user pernah menolak install sebelumnya
    const hasDeclined = localStorage.getItem('lam_pwa_declined');

    window.addEventListener('beforeinstallprompt', (e) => {
      // Prevent browser's default prompt
      e.preventDefault();
      window.deferredPrompt = e;

      // Show banner only if user hasn't declined recently and isn't already installed
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
          if (outcome === 'accepted') {
            console.log('User accepted the PWA prompt');
          }
          window.deferredPrompt = null;
        }
      });
    }

    if(closeBtn) {
      closeBtn.addEventListener('click', () => {
        if(pwaBanner) pwaBanner.style.display = 'none';
        // Simpan penolakan agar tidak mengganggu setiap saat
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
</body>
</html>
