@extends('layouts.app')

@section('title', ($setting->nama_lembaga ?? 'LAMR Bengkalis') . ' — Beranda')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO: Split Layout — Konten Kiri | Foto Kanan
════════════════════════════════════════════════════════════════ --}}
<section
  id="hero"
  class="hero-split"
  x-data="backgroundCarousel(window.lamSlidesData)"
  aria-label="Hero beranda Lembaga Adat Melayu Riau"
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
        {{ $setting->nama_lembaga ?? 'Lembaga Adat Melayu Riau Kabupaten Bengkalis' }}
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
        {{ $setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Riau (LAMR) Kabupaten Bengkalis — menjaga dan melestarikan budaya, adat istiadat Melayu Riau di Kabupaten Bengkalis, Provinsi Riau.' }}
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
      {{-- Fallback foto balai adat --}}
      @if($setting->foto_balai_adat)
        <div class="hero-split__slide" style="background-image:url('{{ Storage::url($setting->foto_balai_adat) }}');opacity:1;z-index:0;"></div>
      @else
        <div class="hero-split__slide" style="background:linear-gradient(135deg,var(--lam-green-d) 0%,#004d1a 100%);opacity:1;z-index:0;"></div>
      @endif
    @endforelse

    {{-- Label Balai Adat telah dihapus --}}

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
    <a href="#selayang-pandang" class="hero-split__scroll-cue" aria-label="Gulir ke bawah">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
    </a>
  </div>{{-- /hero-split__right --}}

</section>

<style>
/* ─── Hero Split Layout ─────────────────────────────────────── */
#hero.hero-split {
  display: flex;
  flex-direction: row;
  height: 85vh;
  max-height: 700px;
  min-height: 600px;
  position: relative;
}
.hero-split__left {
  position: relative;
  background: #0f172a;
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 0 0 40%;
  height: 100%;
  z-index: 1;
}
.hero-split__deco-shield {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
  z-index: 0;
}
.hero-split__corak-border {
  position: absolute;
  top: 0; left: 0; bottom: 0;
  width: 44px;
  background: url('{{ asset('images/corak.svg') }}') repeat-y left top;
  background-size: 44px auto;
  opacity: 0.45;
}
.hero-split__watermark {
  position: absolute;
  inset: 0;
  background: url('{{ asset('images/kuntum-bujang.svg') }}') no-repeat center center;
  background-size: 85%;
  opacity: 0.07;
}
.hero-split__content {
  position: relative;
  z-index: 2;
  padding: 5rem 3.5rem 4rem 4.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.hero-split__photo-corner {
  position: absolute;
  bottom: 1rem;
  width: 140px;
  height: auto;
  z-index: 3;
  pointer-events: none;
  display: block;
  filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
}
.hero-split__photo-corner--bl { left: 0; }
.hero-split__photo-corner--br { right: 0; }

/* Label badge "BALAI ADAT" */
.hero-split__label-badge {
  position: absolute;
  top: 1.25rem;
  left: 1.25rem;
  z-index: 4;
  background: rgba(0,0,0,0.6);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(212,175,55,0.6);
  border-radius: 6px;
  padding: 0.35rem 0.8rem;
}
.hero-split__label-badge span {
  font-family: var(--font-head);
  font-size: 0.7rem;
  font-weight: 800;
  letter-spacing: 0.2em;
  color: #d4af37;
  text-transform: uppercase;
}

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
.hero-split__title {
  font-family: var(--font-head);
  font-size: clamp(1.8rem, 3.2vw, 3rem);
  font-weight: 700;
  color: #f0d060;
  line-height: 1.2;
  margin: 0;
  text-shadow: 0 2px 20px rgba(0,0,0,0.5);
}
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
.hero-split__desc {
  font-family: var(--font-body);
  font-size: 0.9rem;
  color: rgba(240,240,240,0.78);
  line-height: 1.85;
  margin: 0;
  max-width: 420px;
}
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
.hero-split__dots {
  position: absolute;
  bottom: 2rem;
  left: 4.5rem;
  display: flex;
  gap: 0.4rem;
  z-index: 3;
}
.hero-split__dot {
  width: 8px; height: 8px;
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
.hero-split__right {
  position: relative;
  overflow: hidden;
  flex: 1;
  height: 100%;
  width: 100%;
}
.hero-split__slide {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center bottom;
  transition: opacity 900ms ease;
  opacity: 0;
}
.hero-split__aura {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 2;
  background: linear-gradient(
    to right,
    #0f172a 0%,
    rgba(15,23,42,0.7) 18%,
    rgba(15,23,42,0.2) 38%,
    transparent 58%
  );
}
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
  width: 40px; height: 40px;
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

/* Tablet */
@media (max-width: 1024px) {
  .hero-split__left  { flex: 0 0 45%; }
  .hero-split__content { padding: 4rem 2.5rem 3.5rem 3.5rem; }
  .hero-split__scroll-cue { z-index: 4; }
  .hero-split__photo-corner { width: 90px; }
}

/* Mobile */
@media (max-width: 768px) {
  #hero.hero-split { flex-direction: column; height: auto; max-height: none; min-height: 0; }
  .hero-split__left { order: 2; flex: none; width: 100%; height: auto; padding-bottom: 3rem; justify-content: flex-start; }
  .hero-split__right { order: 1; flex: none; flex-shrink: 0; width: 100%; height: 35vh; min-height: 288px; }
  .hero-split__content { padding: 3.5rem 1.5rem 2.25rem 2.5rem; gap: 1.1rem; }
  .hero-split__corak-border { width: 28px; background-size: 28px auto; }
  .hero-split__eyebrow { font-size: 0.6rem; letter-spacing: 0.25em; }
  .hero-split__title { font-size: clamp(1.4rem, 5.5vw, 1.9rem); }
  .hero-split__desc { font-size: 0.83rem; max-width: 100%; }
  .hero-split__actions { flex-direction: row; flex-wrap: nowrap; width: 100%; gap: 0.75rem; margin-top: 1.5rem; margin-bottom: 2rem; }
  .hero-split__btn { flex: 1; justify-content: center; padding: 0.75rem 0.5rem; font-size: 0.875rem; }
  .hero-split__dots { position: static; padding: 0 0 1.25rem 2.5rem; margin-top: -0.5rem; }
  .hero-split__aura { background: linear-gradient(to top, #0f172a 0%, rgba(15,23,42,0.60) 50%, transparent 100%); }
  .hero-split__photo-corner { width: 72px; }
  .hero-split__scroll-cue { display: none; }
}

@media (max-width: 480px) {
  .hero-split__right { height: 35vh; min-height: 250px; }
  .hero-split__content { padding: 2.5rem 1rem 1.75rem 1.75rem; gap: 0.9rem; }
  .hero-split__divider-motif { height: 26px; }
  .hero-split__dots { padding-left: 1.75rem; }
  .hero-split__photo-corner { width: 52px; }
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
      <h2 class="section-heading__title" style="color: #F8F9FA;">Layanan & Kategori</h2>
      <div class="section-heading__divider"><span></span><i></i><span></span></div>
    </div>

    <div class="layanan-outer" id="layananOuter">
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
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="layanan-box__icon-svg"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                @endif
              </div>
              
              <span class="layanan-box__title">{{ $layanan->nama }}</span>
            </a>
          @endforeach
        </div>
      </div>

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
  .layanan-outer { position: relative; display: flex; align-items: center; gap: 0; }
  .layanan-arrow {
    flex-shrink: 0; width: 42px; height: 42px; border-radius: 50%;
    border: 1.5px solid rgba(249,149,34,.45); background: rgba(249,149,34,.08);
    color: var(--lam-gold); display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .2s ease, border-color .2s ease, transform .15s ease, opacity .25s ease;
    z-index: 2; outline: none;
  }
  .layanan-arrow:hover { background: rgba(249,149,34,.22); border-color: var(--lam-gold); transform: scale(1.1); }
  .layanan-arrow:active { transform: scale(.95); }
  .layanan-arrow[style*="display:none"], .layanan-arrow[style*="display: none"] { opacity: 0; pointer-events: none; }
  .layanan-container {
    flex: 1; overflow: hidden;
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
    mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
  }
  .layanan-scroll-wrapper {
    display: flex; flex-wrap: nowrap; overflow-x: auto;
    gap: 1.5rem; padding: 1rem 4%;
    -ms-overflow-style: none; scrollbar-width: none; scroll-behavior: smooth; align-items: flex-start;
  }
  .layanan-scroll-wrapper::-webkit-scrollbar { display: none; }
  .layanan-box { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; text-decoration: none; min-width: 90px; max-width: 110px; transition: transform 0.2s ease; }
  .layanan-box:hover { transform: translateY(-4px); }
  .layanan-box__icon-wrapper { width: 64px; height: 64px; background: var(--lam-black-l); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; display: flex; align-items: center; justify-content: center; color: var(--accent); box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: all 0.3s ease; overflow: hidden; }
  .layanan-box:hover .layanan-box__icon-wrapper { background: color-mix(in srgb, var(--accent) 15%, var(--lam-black-l)); border-color: color-mix(in srgb, var(--accent) 30%, transparent); box-shadow: 0 8px 24px rgba(0,0,0,0.4), 0 0 0 1px var(--accent) inset; }
  .layanan-box__icon-svg { width: 32px; height: 32px; }
  .layanan-box__img { width: 100%; height: 100%; object-fit: cover; }
  .layanan-box__title { font-family: var(--font-body); font-size: 0.85rem; font-weight: 500; color: #F5F5F5; text-align: center; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: color 0.3s ease; }
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
     SELAYANG PANDANG (Tentang LAMR Bengkalis)
════════════════════════════════════════════════════════════════ --}}
<section id="selayang-pandang" class="sp-section">
  <div class="container">
    <div class="sp-grid">
      {{-- Kiri: Foto Balai Adat --}}
      <div class="sp-foto-wrap">
        @if($setting->foto_balai_adat)
          <img src="{{ Storage::url($setting->foto_balai_adat) }}"
               alt="Balai Adat LAMR Bengkalis"
               class="sp-foto"
               loading="lazy">
        @else
          <div class="sp-foto-placeholder">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" stroke="var(--lam-gold)" stroke-width="1" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <p>Foto Balai Adat</p>
          </div>
        @endif
        <div class="sp-foto-badge" aria-hidden="true">BALAI ADAT</div>
      </div>

      {{-- Kanan: Konten Selayang Pandang + Statistik --}}
      <div class="sp-konten">
        <div class="section-heading" style="text-align:left; margin-bottom:1.5rem;">
          <span class="section-heading__eyebrow">Tentang Kami</span>
          <h2 class="section-heading__title">TENTANG LAMR BENGKALIS</h2>
          <div class="section-heading__divider" style="justify-content:flex-start;"><span></span><i></i><span></span></div>
        </div>

        <div class="sp-teks">
          @if($setting->selayang_pandang)
            {!! $setting->selayang_pandang !!}
          @else
            <p>{{ $setting->meta_deskripsi ?? 'LAM Kabupaten Bengkalis merupakan lembaga yang bertujuan memelihara, melestarikan dan mengembangkan adat istiadat Melayu Riau yang bersendikan syarak dan berlandaskan Kitabullah.' }}</p>
          @endif
        </div>

        {{-- Statistik --}}
        <div class="sp-stats">
          <div class="sp-stat">
            <span class="sp-stat__num">{{ number_format($setting->stat_kecamatan ?? 11) }}</span>
            <span class="sp-stat__label">Kecamatan</span>
          </div>
          <div class="sp-stat">
            <span class="sp-stat__num">{{ number_format($setting->stat_desa_kelurahan ?? 105) }}</span>
            <span class="sp-stat__label">Desa/Kelurahan</span>
          </div>
          <div class="sp-stat">
            <span class="sp-stat__num">{{ number_format($setting->stat_kegiatan_budaya ?? 250) }}+</span>
            <span class="sp-stat__label">Kegiatan Budaya</span>
          </div>
          <div class="sp-stat">
            <span class="sp-stat__num">{{ number_format($setting->stat_naskah_koleksi ?? 1250) }}+</span>
            <span class="sp-stat__label">Naskah & Koleksi</span>
          </div>
        </div>

        <a href="{{ route('profil') }}" class="sp-btn">
          Selengkapnya Profil
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

<style>
/* ─── Selayang Pandang ─────────────────────────────────────── */
.sp-section {
  background: var(--lam-cream);
  padding: 4rem 0;
}
.sp-grid {
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 3rem;
  align-items: center;
}
.sp-foto-wrap {
  position: relative;
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: 0 16px 48px rgba(11,79,48,0.2);
  border: 4px solid var(--lam-gold);
  aspect-ratio: 4/3;
}
.sp-foto {
  width: 100%; height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.5s ease;
}
.sp-foto-wrap:hover .sp-foto { transform: scale(1.04); }
.sp-foto-placeholder {
  width: 100%; height: 100%; min-height: 260px;
  background: var(--lam-bg-alt);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 0.75rem; color: var(--lam-text-l);
  font-size: 0.85rem;
}
.sp-foto-badge {
  position: absolute;
  top: 1rem; left: 1rem;
  background: rgba(0,0,0,0.65);
  backdrop-filter: blur(6px);
  border: 1px solid var(--lam-gold);
  border-radius: 4px;
  padding: 0.3rem 0.75rem;
  font-family: var(--font-head);
  font-size: 0.65rem;
  font-weight: 800;
  letter-spacing: 0.2em;
  color: var(--lam-gold);
  text-transform: uppercase;
}
.sp-teks {
  font-size: 0.92rem;
  color: var(--lam-text-m);
  line-height: 1.9;
  margin-bottom: 1.75rem;
}
.sp-teks p { margin-bottom: 0.75rem; text-align: justify; }
.sp-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  margin-bottom: 1.75rem;
  background: var(--lam-bg-alt);
  border: 1px solid var(--lam-border);
  border-radius: var(--radius);
  padding: 1.25rem 1rem;
}
.sp-stat {
  text-align: center;
  padding: 0 0.5rem;
  border-right: 1px solid var(--lam-border);
}
.sp-stat:last-child { border-right: none; }
.sp-stat__num {
  display: block;
  font-family: var(--font-head);
  font-size: clamp(1.4rem, 2.5vw, 2rem);
  font-weight: 800;
  color: var(--lam-green);
  line-height: 1.1;
}
.sp-stat__label {
  display: block;
  font-size: 0.72rem;
  color: var(--lam-text-l);
  margin-top: 0.3rem;
  font-weight: 500;
}
.sp-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--lam-green);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.25s ease;
  font-family: var(--font-body);
}
.sp-btn:hover {
  background: var(--lam-green-d);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(11,79,48,0.35);
}
@media (max-width: 900px) {
  .sp-grid { grid-template-columns: 1fr; }
  .sp-stats { grid-template-columns: repeat(2, 1fr); }
  .sp-stat:nth-child(2) { border-right: none; }
  .sp-stat:nth-child(3) { border-right: 1px solid var(--lam-border); }
}
@media (max-width: 576px) {
  .sp-section { padding: 3rem 0; }
  .sp-stats { grid-template-columns: repeat(2, 1fr); }
}
</style>


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
        <button @click="prev()" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);
                background:rgba(255,255,255,.85);border:none;border-radius:50%;width:36px;height:36px;
                cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;"
                aria-label="Banner sebelumnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        </button>
        <button @click="next()" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);
                background:rgba(255,255,255,.85);border:none;border-radius:50%;width:36px;height:36px;
                cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:2;"
                aria-label="Banner berikutnya">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
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
     MAIN CONTENT: Berita + Agenda + Jelajah + Pustaka
════════════════════════════════════════════════════════════════ --}}
<section class="beranda-main" style="background: var(--lam-cream); padding: 4rem 0;">
  <div class="container">

    {{-- ROW 1: Berita Terbaru (Kiri) + Agenda Mendatang (Kanan) --}}
    <div class="beranda-row beranda-row--2col">

      {{-- ── BERITA TERBARU ────────────────────────────────────── --}}
      @if($beritaTerbaru->count() > 0)
      <div class="beranda-col">
        <div class="beranda-col__header">
          <div class="beranda-col__heading">
            <span class="beranda-col__eyebrow">Informasi Terkini</span>
            <h2 class="beranda-col__title">BERITA TERBARU</h2>
          </div>
          <a href="{{ route('berita.index') }}" class="beranda-col__more">
            Lihat Semua Berita
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>

        <div class="berita-layout">
          {{-- Berita Unggulan --}}
          @if($beritaUnggulan)
          <article class="berita-unggulan" onclick="window.location.href='{{ route('berita.show', $beritaUnggulan->slug) }}'">
            <div class="berita-unggulan__thumb">
              @if($beritaUnggulan->thumbnail)
                <img src="{{ Storage::url($beritaUnggulan->thumbnail) }}" alt="{{ $beritaUnggulan->judul }}" loading="lazy">
              @else
                <div class="berita-unggulan__thumb-placeholder"></div>
              @endif
              <div class="berita-unggulan__overlay">
                <span class="berita-unggulan__cat">{{ $beritaUnggulan->kategori?->nama ?? 'Berita' }}</span>
                <time class="berita-unggulan__date" datetime="{{ $beritaUnggulan->tanggal_publish?->toISOString() }}">
                  {{ $beritaUnggulan->tanggal_publish?->translatedFormat('d M Y') ?? '' }}
                </time>
                <h3 class="berita-unggulan__title">
                  <a href="{{ route('berita.show', $beritaUnggulan->slug) }}">{{ $beritaUnggulan->judul }}</a>
                </h3>
              </div>
            </div>
          </article>
          @endif

          {{-- Daftar Berita Sampingan --}}
          <div class="berita-sampingan">
            @foreach($beritaSampingan as $b)
            <article class="berita-item" onclick="window.location.href='{{ route('berita.show', $b->slug) }}'">
              <div class="berita-item__thumb">
                @if($b->thumbnail)
                  <img src="{{ Storage::url($b->thumbnail) }}" alt="{{ $b->judul }}" loading="lazy">
                @else
                  <div class="berita-item__thumb-placeholder"></div>
                @endif
              </div>
              <div class="berita-item__body">
                <div class="berita-item__meta">
                  <time datetime="{{ $b->tanggal_publish?->toISOString() }}">{{ $b->tanggal_publish?->translatedFormat('d M Y') ?? '' }}</time>
                  <span class="berita-item__cat">{{ $b->kategori?->nama ?? 'Berita' }}</span>
                </div>
                <h4 class="berita-item__title">
                  <a href="{{ route('berita.show', $b->slug) }}">{{ $b->judul }}</a>
                </h4>
              </div>
            </article>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      {{-- ── AGENDA MENDATANG ──────────────────────────────────── --}}
      @if($agendaMendatang->count() > 0)
      <div class="beranda-col">
        <div class="beranda-col__header">
          <div class="beranda-col__heading">
            <span class="beranda-col__eyebrow">Kegiatan Mendatang</span>
            <h2 class="beranda-col__title">AGENDA MENDATANG</h2>
          </div>
          <a href="{{ route('agenda.index') }}" class="beranda-col__more">
            Lihat Semua Agenda
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        </div>

        <div class="agenda-list">
          @foreach($agendaMendatang as $agenda)
          <article class="agenda-item">
            <div class="agenda-item__date-box">
              <span class="agenda-item__day">{{ $agenda->tanggal_mulai->format('d') }}</span>
              <span class="agenda-item__month">{{ strtoupper($agenda->tanggal_mulai->translatedFormat('M')) }}</span>
            </div>
            <div class="agenda-item__body">
              <h4 class="agenda-item__title">
                <a href="{{ route('agenda.index') }}">{{ $agenda->judul }}</a>
              </h4>
              @if($agenda->lokasi)
              <div class="agenda-item__info">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>{{ $agenda->lokasi }}</span>
              </div>
              @endif
              @if($agenda->waktu_mulai)
              <div class="agenda-item__info">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>{{ \Carbon\Carbon::parse($agenda->waktu_mulai)->format('H.i') }} WIB</span>
              </div>
              @endif
            </div>
            <div class="agenda-item__arrow">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </article>
          @endforeach
        </div>
      </div>
      @endif

    </div>{{-- /ROW 1 --}}


    {{-- ROW 2: Jelajah Adat & Budaya --}}
    @if($jelajahItems->count() > 0)
    <div class="beranda-row" style="margin-top: 3.5rem;">
      <div class="beranda-col__header" style="margin-bottom: 1.5rem;">
        <div class="beranda-col__heading">
          <span class="beranda-col__eyebrow">Warisan Leluhur</span>
          <h2 class="beranda-col__title jelajah-title">
            <span class="jelajah-title__motif" aria-hidden="true">⬥</span>
            JELAJAH ADAT & BUDAYA BENGKALIS
            <span class="jelajah-title__motif" aria-hidden="true">⬥</span>
          </h2>
        </div>
        <a href="#" class="beranda-col__more">
          Lihat Semua Kategori
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <div class="jelajah-grid">
        @foreach($jelajahItems as $item)
        @php
          $jUrl = $item->url ?? '#';
          $isExtJ = $item->url && (str_starts_with($item->url,'http://') || str_starts_with($item->url,'https://'));
        @endphp
        <a href="{{ $jUrl }}" class="jelajah-card" @if($isExtJ) target="_blank" rel="noopener noreferrer" @endif>
          <div class="jelajah-card__thumb">
            @if($item->foto)
              <img src="{{ $item->fotoUrl() }}" alt="{{ $item->nama }}" loading="lazy">
            @else
              <div class="jelajah-card__placeholder" style="background: {{ $item->warna ?? 'var(--lam-green)' }}22;">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="{{ $item->warna ?? 'var(--lam-green)' }}" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
              </div>
            @endif
            <div class="jelajah-card__overlay"></div>
          </div>
          <span class="jelajah-card__label">{{ $item->nama }}</span>
        </a>
        @endforeach
      </div>
    </div>
    @endif


    {{-- ROW 3: Pustaka & Dokumen Terbaru --}}
    @if($dokumenTerbaru->count() > 0)
    <div class="beranda-row" style="margin-top: 3.5rem;">
      <div class="beranda-col__header" style="margin-bottom: 1.5rem;">
        <div class="beranda-col__heading">
          <span class="beranda-col__eyebrow">Referensi Resmi</span>
          <h2 class="beranda-col__title pustaka-title">
            <span class="jelajah-title__motif" aria-hidden="true">⬥</span>
            PUSTAKA & DOKUMEN TERBARU
            <span class="jelajah-title__motif" aria-hidden="true">⬥</span>
          </h2>
        </div>
        <a href="{{ route('dokumen.index') }}" class="beranda-col__more">
          Lihat Semua Dokumen
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>

      <div class="pustaka-grid">
        @foreach($dokumenTerbaru as $dok)
        @php
          $isPdf = $dok->is_pdf;
          $ext = strtoupper(pathinfo($dok->file_path ?? '', PATHINFO_EXTENSION)) ?: 'DOC';
          $iconColor = $isPdf ? '#e53e3e' : '#3182ce';
        @endphp
        <article class="pustaka-card">
          <div class="pustaka-card__icon" style="color: {{ $iconColor }};">
            @if($isPdf)
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            @else
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            @endif
            <span class="pustaka-card__ext">{{ $ext }}</span>
          </div>
          <div class="pustaka-card__body">
            <span class="pustaka-card__jenis">{{ $dok->label_jenis }}</span>
            <h4 class="pustaka-card__title">{{ $dok->judul }}</h4>
            <div class="pustaka-card__meta">
              <span>{{ $ext }}</span>
              @if($dok->ukuran_file)
                <span class="pustaka-card__sep">·</span>
                <span>{{ $dok->ukuran_human }}</span>
              @endif
            </div>
          </div>
          @if($dok->file_path)
          <a href="{{ Storage::url($dok->file_path) }}"
             class="pustaka-card__dl"
             target="_blank"
             rel="noopener noreferrer"
             aria-label="Unduh {{ $dok->judul }}"
             onclick="event.stopPropagation();">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          </a>
          @endif
        </article>
        @endforeach
      </div>
    </div>
    @endif

  </div>
</section>

<style>
/* ─── Beranda Main: Shared Column Header ─────────────────────── */
.beranda-main { background: var(--lam-cream); }
.beranda-row { width: 100%; }
.beranda-row--2col {
  display: grid;
  grid-template-columns: 1fr 0.72fr;
  gap: 2.5rem;
  align-items: start;
}
.beranda-col__header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  margin-bottom: 1.25rem;
  flex-wrap: wrap;
  gap: 0.5rem;
}
.beranda-col__eyebrow {
  display: block;
  font-size: 0.65rem;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--lam-gold);
  font-weight: 700;
  margin-bottom: 0.2rem;
}
.beranda-col__title {
  font-family: var(--font-head);
  font-size: clamp(1rem, 1.8vw, 1.3rem);
  font-weight: 800;
  color: var(--lam-green-d);
  margin: 0;
  line-height: 1.2;
}
.beranda-col__more {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--lam-green);
  text-decoration: none;
  white-space: nowrap;
  transition: gap 0.2s;
}
.beranda-col__more:hover { gap: 0.6rem; color: var(--lam-green-d); }

/* ─── Berita Terbaru ─────────────────────────────────────────── */
.berita-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  align-items: start;
}
/* Berita Unggulan */
.berita-unggulan {
  cursor: pointer;
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: transform 0.25s, box-shadow 0.25s;
  grid-row: 1 / span 2;
}
.berita-unggulan:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(0,0,0,0.18); }
.berita-unggulan__thumb {
  position: relative;
  aspect-ratio: 3/4;
  overflow: hidden;
}
.berita-unggulan__thumb img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}
.berita-unggulan:hover .berita-unggulan__thumb img { transform: scale(1.06); }
.berita-unggulan__thumb-placeholder {
  width: 100%; height: 100%; min-height: 280px;
  background: linear-gradient(135deg, var(--lam-green-d), var(--lam-green));
}
.berita-unggulan__overlay {
  position: absolute;
  bottom: 0; left: 0; right: 0;
  padding: 1.5rem 1.25rem 1.25rem;
  background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
}
.berita-unggulan__cat {
  display: inline-block;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #fff;
  background: var(--lam-gold);
  padding: 0.2rem 0.6rem;
  border-radius: 3px;
  margin-bottom: 0.5rem;
}
.berita-unggulan__date {
  display: block;
  font-size: 0.72rem;
  color: rgba(255,255,255,0.75);
  margin-bottom: 0.4rem;
}
.berita-unggulan__title {
  font-family: var(--font-head);
  font-size: clamp(0.9rem, 1.5vw, 1.1rem);
  font-weight: 700;
  line-height: 1.35;
  margin: 0;
  color: #fff;
}
.berita-unggulan__title a { color: inherit; text-decoration: none; }
.berita-unggulan__title a:hover { color: var(--lam-gold); }

/* Berita Sampingan */
.berita-sampingan { display: flex; flex-direction: column; gap: 0.75rem; }
.berita-item {
  display: flex;
  gap: 0.75rem;
  background: var(--lam-bg-alt);
  border: 1px solid var(--lam-border);
  border-radius: var(--radius);
  overflow: hidden;
  cursor: pointer;
  transition: box-shadow 0.25s, border-color 0.25s, transform 0.25s;
  align-items: stretch;
}
.berita-item:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); border-color: var(--lam-gold); }
.berita-item__thumb {
  width: 80px; min-width: 80px;
  overflow: hidden;
  flex-shrink: 0;
  background: var(--lam-black-d);
}
.berita-item__thumb img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s; }
.berita-item:hover .berita-item__thumb img { transform: scale(1.06); }
.berita-item__thumb-placeholder { width: 100%; height: 100%; min-height: 80px; background: linear-gradient(135deg, var(--lam-green-d), #004d1a); }
.berita-item__body { flex: 1; padding: 0.75rem 0.75rem 0.75rem 0; display: flex; flex-direction: column; justify-content: center; gap: 0.3rem; }
.berita-item__meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.berita-item__meta time { font-size: 0.68rem; color: var(--lam-text-l); }
.berita-item__cat { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--lam-gold); background: rgba(212,175,55,0.12); padding: 0.1rem 0.4rem; border-radius: 3px; }
.berita-item__title { font-family: var(--font-head); font-size: 0.85rem; font-weight: 700; line-height: 1.35; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.berita-item__title a { color: var(--lam-text); text-decoration: none; transition: color 0.2s; }
.berita-item:hover .berita-item__title a { color: var(--lam-gold); }

/* ─── Agenda Mendatang ─────────────────────────────────────── */
.agenda-list { display: flex; flex-direction: column; gap: 0.85rem; }
.agenda-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: var(--lam-bg-alt);
  border: 1px solid var(--lam-border);
  border-radius: var(--radius);
  padding: 1rem 1rem 1rem 0.875rem;
  cursor: pointer;
  transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
  border-left: 4px solid var(--lam-green);
}
.agenda-item:hover { transform: translateX(4px); border-left-color: var(--lam-gold); box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
.agenda-item__date-box {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  min-width: 50px; text-align: center;
  border-right: 1px solid var(--lam-border);
  padding-right: 1rem;
}
.agenda-item__day {
  font-family: var(--font-head);
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--lam-green);
  line-height: 1;
}
.agenda-item__month {
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  color: var(--lam-text-l);
  text-transform: uppercase;
  margin-top: 0.2rem;
}
.agenda-item__body { flex: 1; }
.agenda-item__title { font-family: var(--font-head); font-size: 0.88rem; font-weight: 700; line-height: 1.35; margin: 0 0 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.agenda-item__title a { color: var(--lam-text); text-decoration: none; transition: color 0.2s; }
.agenda-item:hover .agenda-item__title a { color: var(--lam-green); }
.agenda-item__info { display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; color: var(--lam-text-l); margin-top: 0.25rem; }
.agenda-item__arrow { color: var(--lam-border); transition: color 0.25s, transform 0.25s; }
.agenda-item:hover .agenda-item__arrow { color: var(--lam-gold); transform: translateX(3px); }

/* ─── Jelajah Budaya ──────────────────────────────────────── */
.jelajah-title {
  display: flex; align-items: center; gap: 0.5rem;
}
.jelajah-title__motif { color: var(--lam-gold); font-size: 0.9em; }

.jelajah-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  gap: 1rem;
}
.jelajah-card {
  display: flex; flex-direction: column;
  text-decoration: none;
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  transition: transform 0.25s, box-shadow 0.25s;
  background: var(--lam-bg-alt);
}
.jelajah-card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(0,0,0,0.16); }
.jelajah-card__thumb {
  position: relative;
  aspect-ratio: 1;
  overflow: hidden;
}
.jelajah-card__thumb img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s ease; }
.jelajah-card:hover .jelajah-card__thumb img { transform: scale(1.1); }
.jelajah-card__placeholder { width: 100%; height: 100%; min-height: 120px; display: flex; align-items: center; justify-content: center; }
.jelajah-card__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 50%);
  opacity: 0; transition: opacity 0.3s;
}
.jelajah-card:hover .jelajah-card__overlay { opacity: 1; }
.jelajah-card__label {
  padding: 0.6rem 0.75rem;
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--lam-text);
  text-align: center;
  line-height: 1.3;
  transition: color 0.25s;
}
.jelajah-card:hover .jelajah-card__label { color: var(--lam-green); }

/* ─── Pustaka & Dokumen ───────────────────────────────────── */
.pustaka-title { display: flex; align-items: center; gap: 0.5rem; }

.pustaka-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
}
.pustaka-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: var(--lam-bg-alt);
  border: 1px solid var(--lam-border);
  border-radius: var(--radius);
  padding: 1rem;
  transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
  position: relative;
}
.pustaka-card:hover { border-color: var(--lam-gold); box-shadow: 0 6px 20px rgba(0,0,0,0.1); transform: translateY(-2px); }
.pustaka-card__icon {
  position: relative;
  flex-shrink: 0;
  width: 44px; height: 52px;
  display: flex; align-items: center; justify-content: center;
}
.pustaka-card__ext {
  position: absolute;
  bottom: 2px;
  left: 50%; transform: translateX(-50%);
  font-size: 0.55rem;
  font-weight: 800;
  letter-spacing: 0.05em;
  color: currentColor;
  background: white;
  padding: 0.1rem 0.3rem;
  border-radius: 2px;
  border: 1px solid currentColor;
}
.pustaka-card__body { flex: 1; min-width: 0; }
.pustaka-card__jenis { display: block; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--lam-gold); margin-bottom: 0.25rem; }
.pustaka-card__title { font-family: var(--font-head); font-size: 0.82rem; font-weight: 700; line-height: 1.35; color: var(--lam-text); margin: 0 0 0.3rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.pustaka-card__meta { display: flex; align-items: center; gap: 0.35rem; font-size: 0.7rem; color: var(--lam-text-l); }
.pustaka-card__sep { color: var(--lam-border); }
.pustaka-card__dl {
  position: absolute;
  top: 0.75rem; right: 0.75rem;
  width: 32px; height: 32px;
  border-radius: 50%;
  background: var(--lam-cream-d);
  display: flex; align-items: center; justify-content: center;
  color: var(--lam-green);
  transition: background 0.25s, color 0.25s;
  text-decoration: none;
}
.pustaka-card__dl:hover { background: var(--lam-green); color: white; }

/* ─── Responsive ─────────────────────────────────────────── */
@media (max-width: 1024px) {
  .beranda-row--2col { grid-template-columns: 1fr; }
  .berita-layout { grid-template-columns: 1fr 1fr; }
  .berita-unggulan { grid-row: auto; }
  .jelajah-grid { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); }
}
@media (max-width: 768px) {
  .beranda-row--2col { grid-template-columns: 1fr; gap: 2rem; }
  .berita-layout { grid-template-columns: 1fr; }
  .berita-unggulan { grid-row: auto; }
  .berita-unggulan__thumb { aspect-ratio: 16/9; }
  .pustaka-grid { grid-template-columns: 1fr; }
  .jelajah-grid { grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
}
@media (max-width: 480px) {
  .jelajah-grid { grid-template-columns: repeat(2, 1fr); }
  .sp-stats { grid-template-columns: repeat(2, 1fr); }
  .berita-layout { gap: 0.75rem; }
}
</style>


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
  "name": "{{ addslashes($setting->nama_lembaga ?? 'Lembaga Adat Melayu Riau Kabupaten Bengkalis') }}",
  "alternateName": "LAMR Bengkalis",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/icon-512x512.png') }}",
  "description": "{{ addslashes($setting->meta_deskripsi ?? 'Website resmi Lembaga Adat Melayu Riau Kabupaten Bengkalis.') }}",
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
