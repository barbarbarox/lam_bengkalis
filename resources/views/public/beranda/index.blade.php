@extends('layouts.app')

@section('title', ($setting->nama_lembaga ?? 'LAM Bengkalis') . ' — Beranda')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO: Split Layout — Konten Kiri | Foto Kanan
════════════════════════════════════════════════════════════════ --}}
<section
  id="hero"
  class="hero-split"
  x-data="backgroundCarousel(window.lamSlidesData)"
  aria-label="Hero beranda Lembaga Adat Melayu"
>

  {{-- ── KOLOM KIRI: Konten & Motif Budaya ─────────────────────── --}}
  <div class="hero-split__left">

    {{-- Shield: wrapper overflow:hidden HANYA untuk dekorasi absolut (bukan konten teks) --}}
    <div class="hero-split__deco-shield" aria-hidden="true">
      {{-- Dekorasi: Border motif vertikal kiri --}}
      <div class="hero-split__corak-border"></div>
      {{-- Dekorasi: Watermark motif kuntum bujang --}}
      <div class="hero-split__watermark"></div>
    </div>

    {{-- Konten utama --}}
    <div class="hero-split__content">

      {{-- Eyebrow / Sub-judul --}}
      <p class="hero-split__eyebrow">Lembaga Resmi Pemerintah Daerah</p>

      {{-- Judul Utama --}}
      <h1 class="hero-split__title">
        {{ $setting->nama_lembaga ?? 'Lembaga Adat Melayu Kabupaten Bengkalis' }}
      </h1>

      {{-- Divider motif kuntum bersanding --}}
      <div class="hero-split__divider" aria-hidden="true">
        <span class="hero-split__divider-line"></span>
        <img
          src="{{ asset('images/kuntum_bersanding.svg') }}"
          alt=""
          class="hero-split__divider-motif"
          loading="eager"
        >
        <span class="hero-split__divider-line"></span>
      </div>

      {{-- Deskripsi --}}
      <p class="hero-split__desc">
        {{ $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu (LAM) Kabupaten Bengkalis — menjaga dan melestarikan budaya, adat istiadat Melayu Riau di Kabupaten Bengkalis, Provinsi Riau.' }}
      </p>

      {{-- Tombol CTA --}}
      <div class="hero-split__actions">
        <a href="{{ route('profil') }}" class="hero-split__btn hero-split__btn--primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          Profil Lembaga
        </a>
        <a href="{{ route('berita.index') }}" class="hero-split__btn hero-split__btn--outline">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
          Baca Berita
        </a>
      </div>

    </div>{{-- /hero-split__content --}}

    {{-- Dot indicators (di kiri bawah, desktop) --}}
    @if($slides->count() > 1)
      <div class="hero-split__dots" role="tablist" aria-label="Pilih slide foto">
        @foreach($slides as $i => $slide)
          <button
            @click="goTo({{ $i }})"
            class="hero-split__dot"
            :class="{ 'is-active': currentSlide === {{ $i }} }"
            role="tab"
            :aria-selected="currentSlide === {{ $i }}"
            :aria-label="'Slide ' + ({{ $i }} + 1)"
          ></button>
        @endforeach
      </div>
    @endif

  </div>{{-- /hero-split__left --}}

  {{-- ── KOLOM KANAN: Foto Carousel ────────────────────────────── --}}
  <div class="hero-split__right" aria-hidden="true">
    @forelse($slides as $i => $slide)
      <div
        class="hero-split__slide"
        style="background-image:url('{{ Storage::url($slide->image_path) }}');z-index:0;"
        :style="{ opacity: currentSlide === {{ $i }} ? 1 : 0 }"
        role="img"
        :aria-label="slides[{{ $i }}]?.alt_text || ''"
      ></div>
    @empty
      {{-- Fallback gradien jika tidak ada slide --}}
      <div class="hero-split__slide" style="background:linear-gradient(135deg,var(--lam-green-d) 0%,#004d1a 100%);opacity:1;z-index:0;"></div>
    @endforelse

    {{-- Aura gradasi responsif: bawah gelap (mobile) / kiri gelap (desktop) — z-index:2 --}}
    <div class="hero-split__aura"></div>

    {{-- Ornamen sudut sudut kiri bawah — di atas aura (z-index:3) --}}
    <img
      src="{{ asset('images/motif-kiri.svg') }}"
      class="hero-split__photo-corner hero-split__photo-corner--bl"
      alt=""
      aria-hidden="true"
    >

    {{-- Ornamen sudut kanan bawah — di atas aura (z-index:3) --}}
    <img
      src="{{ asset('images/motif-kanan.svg') }}"
      class="hero-split__photo-corner hero-split__photo-corner--br"
      alt=""
      aria-hidden="true"
    >

    {{-- Scroll cue --}}
    <a href="#sambutan" class="hero-split__scroll-cue" aria-label="Gulir ke bawah">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
    </a>
  </div>{{-- /hero-split__right --}}

</section>

<style>
/* ─── Hero Split Layout ─────────────────────────────────────── */

/* Wrapper utama: Flexbox row di desktop, column di mobile */
#hero.hero-split {
  display: flex;
  flex-direction: row;          /* desktop: side by side */
  height: 85vh;                 /* Batasi tinggi layar */
  max-height: 700px;
  min-height: 600px;
  /* TIDAK ada overflow:hidden di sini — agar konten teks tidak terpotong */
  position: relative;
}

/* ── Kolom Kiri (Konten) ── */
.hero-split__left {
  position: relative;
  /* TIDAK overflow:hidden — dekorasi absolut dikelola di deco-shield */
  background: #0f172a;
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 0 0 40%;           /* 40% lebar di desktop */
  height: 100%;
  z-index: 1;
}

/* Shield: overflow:hidden HANYA untuk dekorasi absolut */
.hero-split__deco-shield {
  position: absolute;
  inset: 0;
  overflow: hidden;        /* clip dekorasi — tidak pengaruhi konten teks */
  pointer-events: none;
  z-index: 0;
}

/* Border motif vertikal kiri */
.hero-split__corak-border {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  width: 44px;
  background: url('{{ asset('images/corak.svg') }}') repeat-y left top;
  background-size: 44px auto;
  opacity: 0.45;
}

/* Watermark kuntum bujang */
.hero-split__watermark {
  position: absolute;
  inset: 0;
  background: url('{{ asset('images/kuntum-bujang.svg') }}') no-repeat center center;
  background-size: 85%;
  opacity: 0.07;
}

/* Konten dalam kolom kiri — z-index lebih tinggi dari shield */
.hero-split__content {
  position: relative;
  z-index: 2;              /* di atas deco-shield */
  padding: 5rem 3.5rem 4rem 4.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Ornamen sudut di kolom foto */
.hero-split__photo-corner {
  position: absolute;
  bottom: 1rem;
  width: 140px;
  height: auto;
  z-index: 3;              /* di atas aura (z:2), di bawah scroll-cue (z:4) */
  pointer-events: none;
  display: block;
  filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
}
.hero-split__photo-corner--bl {
  left: 0;
}
.hero-split__photo-corner--br {
  right: 0;
}

/* Eyebrow */
.hero-split__eyebrow {
  font-family: var(--font-body);
  font-size: 0.65rem;
  letter-spacing: 0.35em;
  text-transform: uppercase;
  color: #d4af37;
  font-weight: 700;
  margin: 0;
  line-height: 1.4;
}

/* Judul */
.hero-split__title {
  font-family: var(--font-head);
  font-size: clamp(1.8rem, 3.2vw, 3rem);
  font-weight: 700;
  color: #f0d060;
  line-height: 1.2;
  margin: 0;
  text-shadow: 0 2px 20px rgba(0,0,0,0.5);
}

/* Divider motif */
.hero-split__divider {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}
.hero-split__divider-line {
  flex: 1;
  height: 1px;
  background: linear-gradient(to right, transparent, rgba(212,175,55,0.6), transparent);
}
.hero-split__divider-motif {
  height: 36px;
  width: auto;
  flex-shrink: 0;
  filter: brightness(0) saturate(100%) invert(82%) sepia(36%) saturate(400%) hue-rotate(2deg) brightness(105%);
  opacity: 0.9;
}

/* Deskripsi */
.hero-split__desc {
  font-family: var(--font-body);
  font-size: 0.9rem;
  color: rgba(240,240,240,0.78);
  line-height: 1.85;
  margin: 0;
  max-width: 420px;
}

/* Tombol */
.hero-split__actions {
  display: flex;
  gap: 0.875rem;
  flex-wrap: wrap;
  margin-top: 0.25rem;
}

.hero-split__btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  font-family: var(--font-body);
  font-size: 0.85rem;
  font-weight: 700;
  letter-spacing: 0.04em;
  border-radius: 6px;
  text-decoration: none;
  transition: all 0.3s ease;
  white-space: nowrap;
  cursor: pointer;
}

.hero-split__btn--primary {
  background: #d4af37;
  color: #0f172a;
  border: 2px solid #d4af37;
  box-shadow: 0 4px 20px rgba(212,175,55,0.35);
}
.hero-split__btn--primary:hover {
  background: #e8c44a;
  border-color: #e8c44a;
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(212,175,55,0.5);
}

.hero-split__btn--outline {
  background: transparent;
  color: rgba(255,255,255,0.85);
  border: 1.5px solid rgba(255,255,255,0.35);
}
.hero-split__btn--outline:hover {
  background: rgba(255,255,255,0.08);
  border-color: rgba(255,255,255,0.65);
  color: #fff;
  transform: translateY(-2px);
}

/* Dot indicators */
.hero-split__dots {
  position: absolute;
  bottom: 2rem;
  left: 4.5rem;
  display: flex;
  gap: 0.4rem;
  z-index: 3;
}
.hero-split__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  border: none;
  background: rgba(255,255,255,0.25);
  cursor: pointer;
  transition: background 0.3s, width 0.3s, border-radius 0.3s;
  padding: 0;
}
.hero-split__dot.is-active {
  background: #d4af37;
  width: 24px;
  border-radius: 4px;
}

/* ── Kolom Kanan (Foto) ── */
.hero-split__right {
  position: relative;
  overflow: hidden;        /* clip foto — aman karena tidak ada teks di sini */
  flex: 1;                 /* ambil sisa lebar (60%) di desktop */
  height: 100%;
  width: 100%;
}

/* Slide foto */
.hero-split__slide {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center bottom;
  transition: opacity 900ms ease;
  opacity: 0;
}

/* Aura gradasi responsif:
   Desktop → gelap dari kiri (menyatu dengan panel kiri), memudar ke kanan
   Mobile  → gelap dari bawah (menyatu dengan panel teks di bawah), memudar ke atas
*/
.hero-split__aura {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 2;
  /* Desktop default: kiri ke kanan */
  background: linear-gradient(
    to right,
    #0f172a 0%,
    rgba(15,23,42,0.7) 18%,
    rgba(15,23,42,0.2) 38%,
    transparent 58%
  );
}

/* Scroll cue */
.hero-split__scroll-cue {
  position: absolute;
  bottom: 2rem;
  right: 1.5rem;
  z-index: 2;
  color: rgba(255,255,255,0.5);
  animation: hero-bounce 2s infinite;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(0,0,0,0.2);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(255,255,255,0.15);
  transition: color 0.3s, background 0.3s;
}
.hero-split__scroll-cue:hover {
  color: #d4af37;
  background: rgba(0,0,0,0.35);
}

@keyframes hero-bounce {
  0%, 100% { transform: translateY(0); }
  50%       { transform: translateY(6px); }
}

/* ── Responsive: Tablet (1024px) ── */
@media (max-width: 1024px) {
  .hero-split__left  { flex: 0 0 45%; }
  .hero-split__content {
    padding: 4rem 2.5rem 3.5rem 3.5rem;
  }
  /* Scroll cue z-index lebih tinggi dari motif sudut */
  .hero-split__scroll-cue {
    z-index: 4;
  }

  /* Kecilkan ornamen sudut foto di tablet */
  .hero-split__photo-corner {
    width: 90px;
  }
}

/* ── Responsive: Mobile (≤768px) ── */
@media (max-width: 768px) {
  /* Ubah ke kolom vertikal — foto di atas, teks di bawah */
  #hero.hero-split {
    flex-direction: column;  /* stack vertikal */
    height: auto;            /* w-full h-auto: Hapus tinggi tetap desktop 85vh */
    max-height: none;
    min-height: 0;
  }

  /* Kolom kiri (teks) pindah ke bawah */
  .hero-split__left {
    order: 2;
    flex: none;              /* hapus flex-basis 40% */
    width: 100%;
    height: auto;            /* h-auto: Hapus height 100% dari desktop */
    padding-bottom: 3rem;    /* pb-12: Padding bawah agar dark bg berhenti setelah dots */
    justify-content: flex-start;
  }

  /* Kolom kanan (foto) pindah ke atas, tinggi eksplisit */
  .hero-split__right {
    order: 1;
    flex: none;
    flex-shrink: 0;          /* shrink-0: Mencegah container menyusut tergencet konten bawah */
    width: 100%;
    height: 35vh;            /* h-[35vh] */
    min-height: 288px;       /* h-72 */
  }

  /* Konten teks: padding atas cukup, gap wajar */
  .hero-split__content {
    padding: 3.5rem 1.5rem 2.25rem 2.5rem;
    gap: 1.1rem;
  }

  .hero-split__corak-border {
    width: 28px;
    background-size: 28px auto;
  }

  .hero-split__eyebrow {
    font-size: 0.6rem;
    letter-spacing: 0.25em;
  }

  .hero-split__title {
    font-size: clamp(1.4rem, 5.5vw, 1.9rem);
  }

  .hero-split__desc {
    font-size: 0.83rem;
    max-width: 100%;
  }

  /* Layout tombol berdampingan khusus mobile */
  .hero-split__actions {
    flex-direction: row;
    flex-wrap: nowrap;
    width: 100%;
    gap: 0.75rem;
    margin-top: 1.5rem;
    margin-bottom: 2rem;
  }

  .hero-split__btn {
    flex: 1;
    justify-content: center;
    padding: 0.75rem 0.5rem;
    font-size: 0.875rem;
  }

  /* Dots tetap di atas kolom kiri (bawah kiri area teks) */
  .hero-split__dots {
    position: static;
    padding: 0 0 1.25rem 2.5rem;
    margin-top: -0.5rem;
  }

  /* Aura: mobile → gradasi dari bawah ke atas (foto atas ≠> teks bawah) */
  .hero-split__aura {
    background: linear-gradient(
      to top,
      #0f172a 0%,
      rgba(15,23,42,0.60) 50%,
      transparent 100%
    );
  }

  /* Kecilkan ornamen sudut foto di mobile */
  .hero-split__photo-corner {
    width: 72px;
  }

  .hero-split__scroll-cue {
    display: none;
  }
}

/* ── Responsive: HP Kecil (≤480px) ── */
@media (max-width: 480px) {
  .hero-split__right {
    height: 35vh;
    min-height: 250px;
  }

  .hero-split__content {
    padding: 2.5rem 1rem 1.75rem 1.75rem;
    gap: 0.9rem;
  }

  .hero-split__divider-motif {
    height: 26px;
  }
  .hero-split__dots {
    padding-left: 1.75rem;
  }

  /* Ornamen lebih kecil di HP sempit */
  .hero-split__photo-corner {
    width: 52px;
  }
}

@media (prefers-reduced-motion: reduce) {
  .hero-split__slide { transition: none !important; }
  .hero-split__scroll-cue { animation: none !important; }
}
</style>

{{-- ══════════════════════════════════════════════════════════════
     LAYANAN
════════════════════════════════════════════════════════════════ --}}
@if(isset($layanans) && $layanans->count() > 0)
<section id="layanan" style="background:var(--lam-black); padding:3rem 0 4rem;">
  <div class="container">
    <div class="section-heading">
      <span class="section-heading__eyebrow">Apa yang Kami Tawarkan</span>
      <h2 class="section-heading__title">Layanan & Kategori</h2>
      <div class="section-heading__divider"><span></span><i></i><span></span></div>
    </div>

    <div class="layanan-outer" id="layananOuter">
      {{-- Panah Kiri --}}
      <button class="layanan-arrow layanan-arrow--left" id="layananPrev"
              onclick="layananScroll(-1)" aria-label="Geser kiri" style="display:none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
             stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
          <polyline points="15 18 9 12 15 6"/>
        </svg>
      </button>

      <div class="layanan-container" id="layananScroll">
        <div class="layanan-scroll-wrapper">
          @foreach($layanans as $idx => $layanan)
            @php
              $warna = $layanan->warna ?: '#F99522';
              $accents = ['#F99522', '#008000', '#EB2D3A', '#FFC90E', '#009900'];
              $accent = $warna !== '#F99522' ? $warna : ($accents[$idx % count($accents)] ?? '#F99522');
              $isExt = $layanan->url && (str_starts_with($layanan->url,'http://') || str_starts_with($layanan->url,'https://'));
            @endphp
            
            <a href="{{ $layanan->url ?? '#' }}" 
               class="layanan-box" 
               style="--accent: {{ $accent }};"
               @if($isExt) target="_blank" rel="noopener noreferrer" @endif>
              
              <div class="layanan-box__icon-wrapper">
                @if($layanan->jenis_icon === 'image' && $layanan->image)
                  <img src="{{ Storage::url($layanan->image) }}" alt="{{ $layanan->nama }}" class="layanan-box__img" loading="lazy">
                @elseif($layanan->icon)
                  <x-dynamic-component :component="$layanan->icon" class="layanan-box__icon-svg" aria-hidden="true" />
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="layanan-box__icon-svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                @endif
              </div>
              
              <span class="layanan-box__title">{{ $layanan->nama }}</span>
            </a>
          @endforeach
        </div>
      </div>

      {{-- Panah Kanan --}}
      <button class="layanan-arrow layanan-arrow--right" id="layananNext"
              onclick="layananScroll(1)" aria-label="Geser kanan">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
             stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
          <polyline points="9 18 15 12 9 6"/>
        </svg>
      </button>
    </div>
  </div>
</section>

<style>
  /* ─── Layanan: Outer Wrapper + Arrows ──────────────────────── */
  .layanan-outer {
    position: relative;
    display: flex;
    align-items: center;
    gap: 0;
  }

  /* Panah Navigasi */
  .layanan-arrow {
    flex-shrink: 0;
    width: 42px; height: 42px;
    border-radius: 50%;
    border: 1.5px solid rgba(249,149,34,.45);
    background: rgba(249,149,34,.08);
    color: var(--lam-gold);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, transform .15s ease, opacity .25s ease;
    z-index: 2;
    outline: none;
  }
  .layanan-arrow:hover {
    background: rgba(249,149,34,.22);
    border-color: var(--lam-gold);
    transform: scale(1.1);
  }
  .layanan-arrow:active { transform: scale(.95); }
  .layanan-arrow[style*="display:none"],
  .layanan-arrow[style*="display: none"] { opacity: 0; pointer-events: none; }

  .layanan-container {
    flex: 1;
    overflow: hidden;
    /* Fade edges saat panah ada */
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
    mask-image:         linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
  }
  
  .layanan-scroll-wrapper {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: 1.5rem;
    padding: 1rem 4%;
    /* Hide scrollbar */
    -ms-overflow-style: none;
    scrollbar-width: none;
    scroll-behavior: smooth;
    align-items: flex-start;
  }
  .layanan-scroll-wrapper::-webkit-scrollbar { display: none; }
  
  .layanan-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    min-width: 90px;
    max-width: 110px;
    transition: transform 0.2s ease;
  }
  .layanan-box:hover { transform: translateY(-4px); }
  
  .layanan-box__icon-wrapper {
    width: 64px; height: 64px;
    background: var(--lam-black-l);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    color: var(--accent);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    overflow: hidden;
  }
  .layanan-box:hover .layanan-box__icon-wrapper {
    background: color-mix(in srgb, var(--accent) 15%, var(--lam-black-l));
    border-color: color-mix(in srgb, var(--accent) 30%, transparent);
    box-shadow: 0 8px 24px rgba(0,0,0,0.4), 0 0 0 1px var(--accent) inset;
  }
  .layanan-box__icon-svg { width: 32px; height: 32px; }
  .layanan-box__img { width: 100%; height: 100%; object-fit: cover; }
  .layanan-box__title {
    font-family: var(--font-body);
    font-size: 0.85rem; font-weight: 500;
    color: #F5F5F5; text-align: center;
    line-height: 1.3;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: color 0.3s ease;
  }
  .layanan-box:hover .layanan-box__title { color: var(--accent); }
  
  @media (max-width: 768px) {
    .layanan-outer { gap: .25rem; }
    .layanan-arrow { width: 36px; height: 36px; }
    .layanan-scroll-wrapper { padding: 1rem 3%; gap: 1rem; }
    .layanan-box { min-width: 80px; }
    .layanan-box__icon-wrapper { width: 56px; height: 56px; border-radius: 14px; }
    .layanan-box__icon-svg { width: 28px; height: 28px; }
    .layanan-box__title { font-size: 0.75rem; }
  }
</style>

<script>
  (function () {
    const SCROLL_AMOUNT = 260;

    function layananUpdateArrows() {
      var el = document.getElementById('layananScroll');
      var prev = document.getElementById('layananPrev');
      var next = document.getElementById('layananNext');
      if (!el || !prev || !next) return;

      var atStart = el.scrollLeft <= 4;
      var atEnd   = el.scrollLeft >= (el.scrollWidth - el.clientWidth - 4);

      prev.style.display = atStart ? 'none' : 'flex';
      next.style.display = atEnd   ? 'none' : 'flex';
    }

    window.layananScroll = function (dir) {
      var el = document.getElementById('layananScroll');
      if (!el) return;
      el.scrollBy({ left: dir * SCROLL_AMOUNT, behavior: 'smooth' });
      setTimeout(layananUpdateArrows, 350);
    };

    document.addEventListener('DOMContentLoaded', function () {
      var el = document.getElementById('layananScroll');
      if (!el) return;
      layananUpdateArrows();
      el.addEventListener('scroll', layananUpdateArrows, { passive: true });
      window.addEventListener('resize', layananUpdateArrows);
    });
  })();
</script>
@endif



{{-- ══════════════════════════════════════════════════════════════
     SAMBUTAN BPH
════════════════════════════════════════════════════════════════ --}}
@if($sambutan)
<section id="sambutan" class="section-pad" style="background:var(--lam-bg-alt); border-top: 3px solid var(--lam-gold); border-bottom: 3px solid var(--lam-gold);">
  <div class="container" style="position:relative;">

    {{-- Corak Vertikal Kiri --}}
    <div class="sambutan-corak sambutan-corak--left" aria-hidden="true"></div>

    {{-- Corak Vertikal Kanan --}}
    <div class="sambutan-corak sambutan-corak--right" aria-hidden="true"></div>

    {{-- Content (dengan padding agar tidak tertutup corak) --}}
    <div class="sambutan-content-pad">
      <div style="display:grid;grid-template-columns:1fr 2fr;gap:3rem;align-items:center;" class="sambutan-grid">
        {{-- Foto --}}
        <div style="text-align:center;">
          @if($sambutan->foto)
            <img src="{{ Storage::url($sambutan->foto) }}"
                 alt="Foto {{ $sambutan->nama_ketua }}"
                 style="width:220px;height:260px;object-fit:cover;border-radius:var(--radius);
                        box-shadow:0 12px 40px rgba(11,79,48,.2);border:4px solid var(--lam-gold);
                        margin:0 auto;">
          @else
            <div style="width:220px;height:260px;background:var(--lam-cream);border-radius:var(--radius);
                        display:flex;align-items:center;justify-content:center;margin:0 auto;border:4px solid var(--lam-gold);">
              <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" stroke="var(--lam-green)" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          @endif
          <p style="margin-top:1rem;font-weight:700;color:var(--lam-green);font-size:.95rem;">{{ $sambutan->nama_ketua }}</p>
          <p style="font-size:.8rem;color:var(--lam-text-l);">{{ $sambutan->jabatan }}</p>
          @if($sambutan->periode_mulai)
            <p style="font-size:.75rem;color:var(--lam-gold);font-weight:600;">Periode {{ $sambutan->periodeLabel() }}</p>
          @endif
        </div>
        {{-- Isi Sambutan --}}
        <div>
          <div class="section-heading" style="text-align:left;margin-bottom:1.5rem;">
            <span class="section-heading__eyebrow">Sambutan</span>
            <h2 class="section-heading__title">Kata Sambutan Dewan Pimpinan Harian</h2>
            <div class="section-heading__divider" style="justify-content:flex-start;">
              <span></span><i></i><span></span>
            </div>
          </div>
          <div class="sambutan-konten" style="color:var(--lam-text-m);line-height:1.85;font-size:.95rem;">
            {!! $sambutan->isi_sambutan !!}
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<style>
  .sambutan-konten p { margin-bottom: 1rem; text-align: justify; }
  
  /* Corak motif styles */
  .sambutan-corak {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 48px;
    background: url('/images/corak.svg') repeat-y center top;
    background-size: 48px auto;
    opacity: 0.55;
    pointer-events: none;
  }
  .sambutan-corak--left  { left: 0; }
  .sambutan-corak--right { right: 0; transform: scaleX(-1); }
  .sambutan-content-pad  { padding-left: 64px; padding-right: 64px; }

  @media (max-width: 768px) {
    .sambutan-grid { grid-template-columns: 1fr !important; }
    .sambutan-grid > div:first-child { display: none; }
    /* Corak lebih kecil di tablet */
    .sambutan-corak        { width: 28px; background-size: 28px auto; }
    .sambutan-content-pad  { padding-left: 36px; padding-right: 36px; }
  }
  @media (max-width: 576px) {
    /* Corak lebih kecil lagi di HP kecil */
    .sambutan-corak        { width: 18px; background-size: 18px auto; opacity: 0.4; }
    .sambutan-content-pad  { padding-left: 24px; padding-right: 24px; }
  }
</style>
@endif

{{-- ══════════════════════════════════════════════════════════════
     BANNER IKLAN (Swipeable Carousel)
════════════════════════════════════════════════════════════════ --}}
@if($banners->count() > 0)
<section class="section-pad-sm" style="background:var(--lam-cream-d);">
  <div class="container">
    <div
      x-data="bannerCarousel(window.lamBannersData)"
      style="position:relative;border-radius:var(--radius);overflow:hidden;"
    >
      {{-- Slides --}}
      <div
        style="display:flex;transition:transform .45s ease;"
        :style="{ transform: 'translateX(-' + (currentIndex * 100) + '%)' }"
        @touchstart.passive="touchStartX = $event.changedTouches[0].clientX"
        @touchend.passive="handleSwipe($event.changedTouches[0].clientX)"
      >
        @foreach($banners as $banner)
          <div style="min-width:100%;position:relative;flex-shrink:0;">
            @if($banner->link_url)
              <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer"
                 aria-label="{{ $banner->alt_text ?? 'Banner' }}">
            @endif
              <img src="{{ Storage::url($banner->image_path) }}"
                   alt="{{ $banner->alt_text ?? 'Banner iklan' }}"
                   style="width:100%;height:clamp(180px,30vw,360px);object-fit:cover;display:block;">
            @if($banner->link_url)
              </a>
            @endif
          </div>
        @endforeach
      </div>

      @if($banners->count() > 1)
        {{-- Prev --}}
        <button @click="prev()" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);
                background:rgba(255,255,255,.85);border:none;border-radius:50%;width:36px;height:36px;
                cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;"
                aria-label="Banner sebelumnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>

        {{-- Next --}}
        <button @click="next()" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);
                background:rgba(255,255,255,.85);border:none;border-radius:50%;width:36px;height:36px;
                cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;"
                aria-label="Banner berikutnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>

        {{-- Dots --}}
        <div style="position:absolute;bottom:.75rem;left:50%;transform:translateX(-50%);display:flex;gap:.5rem;">
          @foreach($banners as $i => $banner)
            <button @click="goTo({{ $i }})"
                    :style="{ background: currentIndex === {{ $i }} ? 'var(--lam-gold)' : 'rgba(255,255,255,.5)', width: currentIndex === {{ $i }} ? '20px' : '8px', borderRadius: currentIndex === {{ $i }} ? '3px' : '50%' }"
                    style="width:8px;height:8px;border-radius:50%;border:none;cursor:pointer;transition:all .3s;"
                    :aria-label="'Banner ' + ({{ $i }} + 1)" :aria-current="currentIndex === {{ $i }}"></button>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════
     BERITA TERBARU
════════════════════════════════════════════════════════════════ --}}
@if($beritaTerbaru->count() > 0)
<section class="section-pad" style="background:var(--lam-cream);">
  <div class="container">
    <div class="section-heading">
      <span class="section-heading__eyebrow">Informasi Terkini</span>
      <h2 class="section-heading__title">Berita Terbaru</h2>
      <div class="section-heading__divider"><span></span><i></i><span></span></div>
    </div>

    <div class="beranda-berita-list">
      @foreach($beritaTerbaru->take(3) as $idx => $b)
        <article class="beranda-berita-card" onclick="window.location.href='{{ route('berita.show', $b->slug) }}'">
          {{-- Thumbnail --}}
          <div class="beranda-berita-card__thumb">
            @if($b->thumbnail)
              <img src="{{ Storage::url($b->thumbnail) }}" alt="{{ $b->judul }}" loading="lazy">
            @else
              <div class="beranda-berita-card__thumb-placeholder" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="var(--lam-gold)" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              </div>
            @endif
            {{-- Nomor urut --}}
            <span class="beranda-berita-card__num">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
          </div>
          {{-- Konten --}}
          <div class="beranda-berita-card__body">
            <p class="beranda-berita-card__cat">{{ $b->kategori?->nama ?? 'Umum' }}</p>
            <h3 class="beranda-berita-card__title">
              <a href="{{ route('berita.show', $b->slug) }}">{{ $b->judul }}</a>
            </h3>
            <p class="beranda-berita-card__excerpt">{{ $b->excerpt }}</p>
            <div class="beranda-berita-card__meta">
              <time datetime="{{ $b->tanggal_publish?->toISOString() }}">
                {{ $b->tanggal_publish?->translatedFormat('d M Y') ?? '—' }}
              </time>
              <a href="{{ route('berita.show', $b->slug) }}" class="beranda-berita-card__read">
                Selengkapnya
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </a>
            </div>
          </div>
        </article>
      @endforeach
    </div>

    <div style="text-align:center;margin-top:2.5rem;">
      <a href="{{ route('berita.index') }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
        Lihat Semua Berita
      </a>
    </div>
  </div>
</section>

<style>
  /* ─── Beranda: Berita List ──────────────────────────────────── */
  .beranda-berita-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  .beranda-berita-card {
    display: flex;
    align-items: stretch;
    background: var(--lam-bg-alt);
    border: 1px solid var(--lam-border);
    border-left: 4px solid var(--lam-gold);
    border-radius: var(--radius);
    overflow: hidden;
    cursor: pointer;
    transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
    min-height: 160px;
  }
  .beranda-berita-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 36px rgba(249,149,34,.18), 0 4px 16px rgba(0,0,0,.08);
    border-left-color: var(--lam-gold-d);
    border-color: var(--lam-gold);
  }
  /* Thumbnail */
  .beranda-berita-card__thumb {
    width: 220px;
    min-width: 220px;
    position: relative;
    flex-shrink: 0;
    overflow: hidden;
    background: var(--lam-black-d);
  }
  .beranda-berita-card__thumb img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform .4s ease;
  }
  .beranda-berita-card:hover .beranda-berita-card__thumb img {
    transform: scale(1.06);
  }
  .beranda-berita-card__thumb-placeholder {
    width: 100%; height: 100%; min-height: 160px;
    display: flex; align-items: center; justify-content: center;
    background: var(--lam-black-d);
  }
  /* Nomor urut */
  .beranda-berita-card__num {
    position: absolute; top: .6rem; left: .6rem;
    font-size: .7rem; font-weight: 800;
    font-family: var(--font-head);
    color: var(--lam-gold);
    background: rgba(0,0,0,.65);
    backdrop-filter: blur(4px);
    padding: .2rem .5rem;
    border-radius: 4px;
    letter-spacing: .06em;
    line-height: 1;
  }
  /* Body */
  .beranda-berita-card__body {
    flex: 1;
    padding: 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: .5rem;
  }
  .beranda-berita-card__cat {
    font-size: .68rem; font-weight: 700;
    letter-spacing: .15em; text-transform: uppercase;
    color: var(--lam-gold); margin: 0;
  }
  .beranda-berita-card__title {
    font-family: var(--font-head);
    font-size: clamp(1rem, 2vw, 1.15rem);
    font-weight: 700; line-height: 1.35; margin: 0;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  }
  .beranda-berita-card__title a {
    color: var(--lam-text);
    transition: color .2s;
  }
  .beranda-berita-card:hover .beranda-berita-card__title a {
    color: var(--lam-gold);
  }
  .beranda-berita-card__excerpt {
    font-size: .85rem; color: var(--lam-text-l); margin: 0; line-height: 1.6;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  }
  .beranda-berita-card__meta {
    display: flex; align-items: center; justify-content: space-between;
    font-size: .78rem; color: var(--lam-text-l);
    padding-top: .6rem; border-top: 1px solid var(--lam-border);
    flex-wrap: wrap; gap: .4rem;
  }
  .beranda-berita-card__read {
    color: var(--lam-gold); font-weight: 600; font-size: .8rem;
    display: flex; align-items: center; gap: .25rem;
    transition: color .2s; white-space: nowrap;
  }
  .beranda-berita-card__read:hover { color: var(--lam-gold-d); }

  /* Mobile */
  @media (max-width: 640px) {
    .beranda-berita-card {
      min-height: 130px;
    }
    .beranda-berita-card__thumb {
      width: 120px; min-width: 120px;
    }
    .beranda-berita-card__body {
      padding: .875rem 1rem;
    }
    .beranda-berita-card__excerpt {
      display: none;
    }
  }
</style>
@endif

{{-- ══════════════════════════════════════════════════════════════
     CTA STRIP
════════════════════════════════════════════════════════════════ --}}
<section style="background:var(--lam-green);padding:3.5rem 0;">
  <div class="container" style="text-align:center;">
    <p style="font-size:.8rem;letter-spacing:.2em;text-transform:uppercase;color:var(--lam-gold);margin-bottom:.75rem;font-weight:600;">Hubungi Kami</p>
    <h2 style="font-family:var(--font-head);font-size:clamp(1.5rem,3vw,2.2rem);color:white;margin-bottom:1rem;">
      Ada pertanyaan atau pengaduan?
    </h2>
    <p style="color:rgba(255,255,255,.75);margin-bottom:2rem;max-width:500px;margin-left:auto;margin-right:auto;">
      Sampaikan aspirasi dan pengaduan Anda melalui formulir kontak resmi kami.
    </p>
    <a href="{{ route('kontak') }}" class="btn btn-primary">Kirim Pesan</a>
  </div>
</section>

@endsection

@push('body_scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "GovernmentOrganization",
  "name": "{{ addslashes($setting->nama_lembaga ?? 'Lembaga Adat Melayu Kabupaten Bengkalis') }}",
  "alternateName": "LAM Bengkalis",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/icon-512x512.png') }}",
  "description": "{{ addslashes($setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Kabupaten Bengkalis.') }}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Bengkalis",
    "addressRegion": "Riau",
    "addressCountry": "ID"
  }
  @if($setting->email_kontak || $setting->no_telp)
  ,"contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer service"
    @if($setting->email_kontak)
    ,"email": "{{ $setting->email_kontak }}"
    @endif
    @if($setting->no_telp)
    ,"telephone": "{{ $setting->no_telp }}"
    @endif
  }
  @endif
}
</script>
@endpush

@push('body_scripts')
<script>
window.lamSlidesData = @json($slides);
window.lamBannersData = @json($banners);

function backgroundCarousel(slidesData) {
  return {
    slides:       slidesData,
    currentSlide: 0,
    timer:        null,
    reduced:      false,

    init() {
      this.reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (!this.reduced && this.slides.length > 1) {
        this.startAuto();
      }
    },
    goTo(index) {
      this.currentSlide = index;
      if (!this.reduced) { this.restart(); }
    },
    next() { this.goTo((this.currentSlide + 1) % this.slides.length); },
    startAuto() {
      this.timer = setInterval(() => {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
      }, 4000);
    },
    restart() {
      clearInterval(this.timer);
      this.startAuto();
    }
  };
}

function bannerCarousel(bannersData) {
  return {
    banners:      bannersData,
    currentIndex: 0,
    touchStartX:  0,
    timer:        null,
    reduced:      false,

    init() {
      this.reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (!this.reduced && this.banners.length > 1) {
        this.timer = setInterval(() => {
          this.currentIndex = (this.currentIndex + 1) % this.banners.length;
        }, 6000);
      }
    },
    goTo(i) {
      this.currentIndex = i;
      clearInterval(this.timer);
      if (!this.reduced) {
        this.timer = setInterval(() => {
          this.currentIndex = (this.currentIndex + 1) % this.banners.length;
        }, 6000);
      }
    },
    next() { this.goTo((this.currentIndex + 1) % this.banners.length); },
    prev() { this.goTo((this.currentIndex - 1 + this.banners.length) % this.banners.length); },
    handleSwipe(endX) {
      var diff = this.touchStartX - endX;
      if (Math.abs(diff) > 40) {
        diff > 0 ? this.next() : this.prev();
      }
    }
  };
}
</script>
@endpush
