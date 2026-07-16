@extends('layouts.app')

@section('title', ($setting->nama_lembaga ?? 'LAM Bengkalis') . ' — Beranda')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO: Background Carousel + Overlay Konten
════════════════════════════════════════════════════════════════ --}}
<section
  id="hero"
  x-data="backgroundCarousel(window.lamSlidesData)"
  style="position:relative;height:100svh;min-height:540px;max-height:900px;overflow:hidden;"
  aria-label="Gambar latar beranda"
>
  {{-- Slide images (crossfade) --}}
  <div style="position:absolute;inset:0;" aria-hidden="true">
    @forelse($slides as $i => $slide)
      <div
        style="position:absolute;inset:0;background-size:cover;background-position:center;transition:opacity 800ms ease;background-image:url('{{ Storage::url($slide->image_path) }}')"
        :style="{ opacity: currentSlide === {{ $i }} ? 1 : 0 }"
        role="img"
        :aria-label="slides[{{ $i }}]?.alt_text || ''"
      ></div>
    @empty
      {{-- Fallback: gradien jika tidak ada slide --}}
      <div style="position:absolute;inset:0;background:linear-gradient(135deg,var(--lam-green-d),var(--lam-green));"></div>
    @endforelse
  </div>

  {{-- Overlay gelap --}}
  <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.5) 0%,rgba(0,0,0,.2) 50%,rgba(11,79,48,.85) 100%);" aria-hidden="true"></div>

  {{-- Konten tengah hero --}}
  <div class="container" style="position:relative;z-index:2;height:100%;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding-top:4rem;">
    <div class="hero-box" style="position: relative; overflow: hidden; background: rgba(212, 160, 23, 0.75); backdrop-filter: blur(10px); padding: 4rem 3rem; border-radius: var(--radius); box-shadow: var(--lam-shadow); max-width: 800px;">
      
      {{-- SVG Borders --}}
      <div style="position: absolute; top: 0; left: 0; right: 0; height: 32px; background: url('{{ asset('images/itik_pulang_petang.svg') }}') repeat-x center; background-size: auto 100%; opacity: 0.8;" aria-hidden="true"></div>
      <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 32px; background: url('{{ asset('images/itik_pulang_petang.svg') }}') repeat-x center; background-size: auto 100%; opacity: 0.8; transform: rotate(180deg);" aria-hidden="true"></div>
      
      <p style="font-size:.75rem;letter-spacing:.3em;text-transform:uppercase;color:var(--lam-black-d);font-weight:700;margin-bottom:1rem;position:relative;z-index:2;">
        Lembaga Resmi Pemerintah Daerah
      </p>
      <h1 style="font-family:var(--font-head);font-size:clamp(2rem,5vw,3.5rem);color:var(--lam-black);font-weight:700;max-width:700px;line-height:1.2;margin-bottom:1.25rem;text-shadow:none;position:relative;z-index:2;">
        {{ $setting->nama_lembaga ?? 'Lembaga Adat Melayu Kabupaten Bengkalis' }}
      </h1>
      <p style="color:var(--lam-black-l);max-width:540px;font-size:1.05rem;margin-bottom:2rem;margin-left:auto;margin-right:auto;font-weight:500;position:relative;z-index:2;">
        {{ $setting->meta_deskripsi ?? 'Menjaga, melestarikan, dan mengembangkan adat budaya Melayu di Kabupaten Bengkalis.' }}
      </p>
      <div style="display:flex;gap:1rem;flex-wrap:wrap;justify-content:center;position:relative;z-index:2;">
        <a href="{{ route('profil') }}" class="btn hero-btn-primary" style="background:var(--lam-gold); color:var(--lam-black); border:2px solid var(--lam-gold); font-weight:700; transition:all 0.3s ease;">Profil Lembaga</a>
        <a href="{{ route('berita.index') }}" class="btn hero-btn-outline" style="background:transparent; border:2px solid rgba(255,255,255,0.7); color:#fff; font-weight:600; transition:all 0.3s ease;">Baca Berita</a>
      </div>
    </div>
  </div>

  {{-- Dot indicators --}}
  @if($slides->count() > 1)
    <div style="position:absolute;bottom:2rem;left:50%;transform:translateX(-50%);display:flex;gap:.5rem;z-index:3;" role="tablist" aria-label="Pilih slide">
      @foreach($slides as $i => $slide)
        <button
          @click="goTo({{ $i }})"
          :class="currentSlide === {{ $i }} ? 'is-active' : ''"
          style="width:8px;height:8px;border-radius:50%;border:none;background:rgba(255,255,255,.4);cursor:pointer;transition:background .3s,width .3s,border-radius .3s;"
          :style="{ background: currentSlide === {{ $i }} ? 'var(--lam-gold)' : 'rgba(255,255,255,.4)', width: currentSlide === {{ $i }} ? '24px' : '8px', borderRadius: currentSlide === {{ $i }} ? '4px' : '50%' }"
          role="tab" :aria-selected="currentSlide === {{ $i }}"
          :aria-label="'Slide ' + ({{ $i }} + 1)"
        ></button>
      @endforeach
    </div>
  @endif

  {{-- Scroll cue --}}
  <a href="#sambutan" style="position:absolute;bottom:3.5rem;right:2rem;z-index:3;color:rgba(255,255,255,.5);animation:bounce 2s infinite;" aria-label="Gulir ke bawah">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
  </a>
</section>

<style>
  @keyframes bounce { 0%,100%{transform:translateY(0);} 50%{transform:translateY(6px);} }
  @media (prefers-reduced-motion:reduce){
    [x-data*="backgroundCarousel"] div[style*="transition"] { transition:none !important; }
  }
  .hero-btn-primary:hover {
    background: #e5871b !important; /* Slightly darker gold/yellow */
    border-color: #e5871b !important;
    transform: translateY(-2px);
  }
  .hero-btn-outline:hover {
    background: rgba(255,255,255,0.15) !important;
    border-color: #fff !important;
    transform: translateY(-2px);
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

    <div class="layanan-container">
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
  </div>
</section>

<style>
  .layanan-container {
    width: 100%;
    overflow: hidden;
    /* Optional: fade effect on edges */
    -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
  }
  
  .layanan-scroll-wrapper {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: 1.5rem;
    padding: 1rem 10%;
    /* Hide scrollbar */
    -ms-overflow-style: none;
    scrollbar-width: none;
    scroll-behavior: smooth;
    align-items: flex-start;
  }
  .layanan-scroll-wrapper::-webkit-scrollbar {
    display: none;
  }
  
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
  
  .layanan-box:hover {
    transform: translateY(-4px);
  }
  
  .layanan-box__icon-wrapper {
    width: 64px;
    height: 64px;
    background: var(--lam-black-l);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
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
  
  .layanan-box__icon-svg {
    width: 32px;
    height: 32px;
  }
  
  .layanan-box__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .layanan-box__title {
    font-family: var(--font-sans);
    font-size: 0.85rem;
    font-weight: 500;
    color: #F5F5F5;
    text-align: center;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s ease;
  }
  
  .layanan-box:hover .layanan-box__title {
    color: var(--accent);
  }
  
  @media (max-width: 768px) {
    .layanan-scroll-wrapper {
      padding: 1rem 5%;
      gap: 1rem;
    }
    .layanan-box {
      min-width: 80px;
    }
    .layanan-box__icon-wrapper {
      width: 56px;
      height: 56px;
      border-radius: 14px;
    }
    .layanan-box__icon-svg {
      width: 28px;
      height: 28px;
    }
    .layanan-box__title {
      font-size: 0.75rem;
    }
  }
</style>
@endif



{{-- ══════════════════════════════════════════════════════════════
     SAMBUTAN BPH
════════════════════════════════════════════════════════════════ --}}
@if($sambutan)
<section id="sambutan" class="section-pad" style="background:var(--lam-bg-alt);">
  <div class="container">
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
</section>

<style>
  .sambutan-konten p { margin-bottom: 1rem; text-align: justify; }
  @media (max-width: 768px) {
    .sambutan-grid { grid-template-columns: 1fr !important; }
    .sambutan-grid > div:first-child { display: none; }
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

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;margin-bottom:2.5rem;">
      @foreach($beritaTerbaru as $b)
        <article class="card-berita" style="cursor:pointer;" onclick="window.location.href='{{ route('berita.show', $b->slug) }}'">
          @if($b->thumbnail)
            <img src="{{ Storage::url($b->thumbnail) }}" alt="{{ $b->judul }}" class="card-berita__img" loading="lazy">
          @else
            <div class="card-berita__img-placeholder" aria-hidden="true">
              <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="var(--lam-green)" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
          @endif
          <div class="card-berita__body">
            <p class="card-berita__cat">{{ $b->kategori?->nama ?? 'Umum' }}</p>
            <h3 class="card-berita__title">
              <a href="{{ route('berita.show', $b->slug) }}" style="color:inherit;">{{ $b->judul }}</a>
            </h3>
            <p class="card-berita__excerpt">{{ $b->excerpt }}</p>
            <div class="card-berita__meta">
              <time datetime="{{ $b->tanggal_publish?->toISOString() }}">
                {{ $b->tanggal_publish?->translatedFormat('d M Y') ?? '—' }}
              </time>
              <a href="{{ route('berita.show', $b->slug) }}"
                 style="color:var(--lam-green);font-weight:600;font-size:.8rem;display:flex;align-items:center;gap:.25rem;">
                Selengkapnya
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
              </a>
            </div>
          </div>
        </article>
      @endforeach
    </div>

    <div style="text-align:center;">
      <a href="{{ route('berita.index') }}" class="btn btn-outline">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
        Lihat Semua Berita
      </a>
    </div>
  </div>
</section>
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
