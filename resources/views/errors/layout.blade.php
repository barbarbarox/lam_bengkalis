<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $code }} — {{ $title }} | LAM Bengkalis</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --lam-black:   #0D0D0D;
      --lam-black-d: #070707;
      --lam-gold:    #F99522;
      --lam-gold-d:  #D4A017;
      --lam-green:   #0B4F30;
      --lam-green-l: #1A7A4A;
      --lam-maroon:  #8B1A1A;
      --lam-cream:   #FDF6E3;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--lam-black-d);
      color: #e8e0cc;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    /* ── Top ornament bar ─────────────────────────────── */
    .ornament-bar {
      width: 100%;
      height: 36px;
      background: url('/images/itik_pulang_petang.svg') repeat-x center;
      background-size: auto 100%;
      opacity: 0.7;
      flex-shrink: 0;
    }

    /* ── Gold border lines ───────────────────────────── */
    .gold-line { height: 3px; background: var(--lam-gold); width: 100%; }

    /* ── Main error area ─────────────────────────────── */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 1.5rem;
      position: relative;
      overflow: hidden;
    }

    /* Corak vertikal sisi kiri & kanan */
    main::before,
    main::after {
      content: '';
      position: absolute;
      top: 0; bottom: 0;
      width: 52px;
      background: url('/images/corak.svg') repeat-y center top;
      background-size: 52px auto;
      opacity: 0.18;
      pointer-events: none;
    }
    main::before { left: 0; }
    main::after  { right: 0; transform: scaleX(-1); }

    /* ── Error code (large number) ───────────────────── */
    .error-code-wrap {
      position: relative;
      display: inline-block;
      margin-bottom: 1.5rem;
    }
    .error-code {
      font-family: 'Playfair Display', serif;
      font-size: clamp(6rem, 20vw, 14rem);
      font-weight: 900;
      line-height: 1;
      /* Gold → green gradient */
      background: linear-gradient(135deg, var(--lam-gold) 0%, var(--lam-gold-d) 40%, var(--lam-green-l) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.03em;
      filter: drop-shadow(0 4px 24px rgba(249,149,34,0.25));
    }

    /* Pucuk rebung di bawah angka error */
    .error-pucuk {
      width: 100%;
      height: 20px;
      background: url('/images/pucuk-rebung.svg') repeat-x center;
      background-size: auto 100%;
      opacity: 0.55;
      margin: 0.5rem 0 1.5rem;
    }

    /* ── Kuntum Bujang dekorasi ──────────────────────── */
    .kuntum-deco {
      width: 72px; height: 72px;
      background: url('/images/kuntum-bujang.svg') no-repeat center;
      background-size: contain;
      opacity: 0.25;
      margin: 0 auto 1.5rem;
    }

    /* ── Text content ────────────────────────────────── */
    .error-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.4rem, 4vw, 2.2rem);
      font-weight: 700;
      color: #f5e6c0;
      margin-bottom: 0.75rem;
      text-align: center;
    }

    .error-peribahasa {
      font-size: 0.85rem;
      color: var(--lam-gold);
      font-style: italic;
      text-align: center;
      margin-bottom: 1.25rem;
      opacity: 0.8;
      letter-spacing: 0.02em;
    }

    .error-desc {
      font-size: clamp(0.9rem, 2.5vw, 1.05rem);
      color: rgba(232, 224, 204, 0.7);
      text-align: center;
      max-width: 520px;
      line-height: 1.75;
      margin-bottom: 2.5rem;
    }

    /* ── Action buttons ──────────────────────────────── */
    .btn-group {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.75rem;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 600;
      text-decoration: none;
      transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
      cursor: pointer;
      border: none;
    }
    .btn:hover { transform: translateY(-2px); opacity: 0.92; }
    .btn-primary {
      background: linear-gradient(135deg, var(--lam-gold), var(--lam-gold-d));
      color: var(--lam-black);
      box-shadow: 0 4px 20px rgba(249,149,34,0.3);
    }
    .btn-outline {
      background: transparent;
      color: var(--lam-gold);
      border: 2px solid var(--lam-gold);
    }
    .btn-back {
      background: var(--lam-green);
      color: #fff;
      box-shadow: 0 4px 20px rgba(11,79,48,0.3);
    }

    /* ── Badge kode warna per error ──────────────────── */
    .error-badge {
      display: inline-block;
      padding: 0.2rem 0.8rem;
      border-radius: 99px;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      margin-bottom: 1rem;
    }
    .badge-red    { background: rgba(139,26,26,0.3); color: #ff9999; border: 1px solid rgba(139,26,26,0.6); }
    .badge-yellow { background: rgba(249,149,34,0.15); color: var(--lam-gold); border: 1px solid rgba(249,149,34,0.4); }
    .badge-green  { background: rgba(11,79,48,0.3); color: #6deba4; border: 1px solid rgba(11,79,48,0.6); }

    /* ── Footer ──────────────────────────────────────── */
    footer {
      text-align: center;
      padding: 1.25rem;
      color: rgba(232,224,204,0.3);
      font-size: 0.78rem;
      border-top: 1px solid rgba(249,149,34,0.1);
    }
    footer a { color: var(--lam-gold); text-decoration: none; opacity: 0.7; }
    footer a:hover { opacity: 1; }

    /* ── Floating motif background ───────────────────── */
    .bg-motif {
      position: fixed;
      inset: 0;
      z-index: -1;
      overflow: hidden;
      pointer-events: none;
    }
    .bg-motif__circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(80px);
      opacity: 0.06;
    }
    .bg-motif__circle--gold {
      width: 600px; height: 600px;
      background: var(--lam-gold);
      top: -150px; right: -150px;
    }
    .bg-motif__circle--green {
      width: 500px; height: 500px;
      background: var(--lam-green);
      bottom: -100px; left: -100px;
    }
    .bg-motif__circle--maroon {
      width: 400px; height: 400px;
      background: var(--lam-maroon);
      top: 40%; left: 40%;
      opacity: 0.04;
    }

    /* ── Responsive ──────────────────────────────────── */
    @media (max-width: 480px) {
      main::before, main::after { width: 24px; background-size: 24px auto; opacity: 0.12; }
      .error-pucuk { height: 14px; }
    }

    /* ── Subtle animation ────────────────────────────── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .anim { animation: fadeUp 0.6s ease both; }
    .anim-d1 { animation-delay: 0.1s; }
    .anim-d2 { animation-delay: 0.2s; }
    .anim-d3 { animation-delay: 0.35s; }
    .anim-d4 { animation-delay: 0.5s; }
  </style>
</head>
<body>

  {{-- Floating ambient background --}}
  <div class="bg-motif">
    <div class="bg-motif__circle bg-motif__circle--gold"></div>
    <div class="bg-motif__circle bg-motif__circle--green"></div>
    <div class="bg-motif__circle bg-motif__circle--maroon"></div>
  </div>

  {{-- Top ornament + gold line --}}
  <div class="ornament-bar"></div>
  <div class="gold-line"></div>

  <main>
    {{-- Error code number --}}
    <div class="error-code-wrap anim">
      <div class="error-code">{{ $code }}</div>
    </div>

    {{-- Pucuk rebung separator --}}
    <div class="error-pucuk anim anim-d1"></div>

    {{-- Badge kategori --}}
    <span class="error-badge {{ $badgeClass ?? 'badge-yellow' }} anim anim-d1">{{ $badgeLabel ?? 'Galat' }}</span>

    {{-- Kuntum Bujang ornament --}}
    <div class="kuntum-deco anim anim-d2"></div>

    {{-- Title & description --}}
    <h1 class="error-title anim anim-d2">{{ $title }}</h1>

    @if(!empty($peribahasa))
    <p class="error-peribahasa anim anim-d2">"{{ $peribahasa }}"</p>
    @endif

    <p class="error-desc anim anim-d3">{{ $description }}</p>

    {{-- Buttons --}}
    <div class="btn-group anim anim-d4">
      <a href="{{ url('/') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Kembali ke Beranda
      </a>
      <button onclick="history.back()" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Halaman Sebelumnya
      </button>
    </div>
  </main>

  {{-- Bottom gold line + ornament --}}
  <div class="gold-line"></div>
  <div class="ornament-bar" style="transform:scaleY(-1);"></div>

  <footer>
    &copy; {{ date('Y') }} <a href="{{ url('/') }}">LAM Bengkalis</a> — Lembaga Adat Melayu Kabupaten Bengkalis
  </footer>

</body>
</html>
