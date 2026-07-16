@extends('layouts.app')

@section('title', 'Galeri Foto — ' . ($setting->nama_lembaga ?? 'LAM Bengkalis'))
@section('meta_description', 'Galeri foto kegiatan dan dokumentasi ' . ($setting->nama_lembaga ?? 'Lembaga Adat Melayu Bengkalis'))

@section('content')

{{-- Page Header --}}
@php $heroBg = $setting->heroUrl('galeri'); @endphp
<div class="page-hero" style="{{ $heroBg ? 'background-image:url('.$heroBg.')' : '' }}">
  <div class="page-hero__overlay"></div>
  @if($heroBg)<div class="page-hero__gold-edge"></div>@endif
  <div class="container" style="position:relative;z-index:2;text-align:center;">
    <p style="font-size:.75rem;letter-spacing:.25em;text-transform:uppercase;color:var(--lam-gold);font-weight:600;margin-bottom:.75rem;">Dokumentasi &amp; Kenangan</p>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.75rem,4vw,2.75rem);color:white;">Galeri Foto</h1>
    <p style="color:rgba(255,255,255,.7);margin-top:.75rem;">Momen dan kegiatan Lembaga Adat Melayu Kabupaten Bengkalis</p>
  </div>
</div>
<style>
  .page-hero{position:relative;padding:5rem 0 4rem;text-align:center;background:linear-gradient(135deg,var(--lam-black-d) 0%,#1a1200 50%,var(--lam-black-d) 100%);background-size:cover;background-position:center;background-repeat:no-repeat;}
  .page-hero__overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.45) 0%,rgba(0,0,0,.35) 60%,rgba(0,0,0,.65) 100%);z-index:1;}
  .page-hero__gold-edge{position:absolute;inset:0;background:linear-gradient(to right,rgba(249,149,34,.35) 0%,transparent 18%,transparent 82%,rgba(249,149,34,.35) 100%),linear-gradient(to bottom,rgba(249,149,34,.2) 0%,transparent 30%);z-index:1;pointer-events:none;}
</style>



{{-- Galeri Kosong --}}
@if($fotos->isEmpty())
<section style="background:var(--lam-black);min-height:60vh;display:flex;align-items:center;justify-content:center;">
  <div style="text-align:center;padding:4rem 1rem;">
    <div style="width:80px;height:80px;margin:0 auto 1.5rem;background:rgba(249,149,34,.1);border-radius:50%;display:flex;align-items:center;justify-content:center;">
      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="var(--lam-gold)" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
        <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
      </svg>
    </div>
    <p style="color:rgba(255,255,255,.5);font-size:1.05rem;">Belum ada foto di galeri.</p>
    <p style="color:rgba(255,255,255,.3);font-size:.85rem;margin-top:.5rem;">Foto akan muncul di sini setelah admin menambahkannya.</p>
  </div>
</section>
@else

{{-- Masonry Galeri --}}
<section style="background:var(--lam-black);padding:3rem 0 5rem;">
  <div class="container">
    {{-- Masonry Container --}}
    <div id="masonry-container" style="position:relative;width:100%;transition:height .4s ease;">
      @foreach($fotos as $foto)
        <div class="masonry-item"
             data-id="{{ $foto->id }}"
             data-height="{{ 200 + ($foto->id % 5) * 80 }}"
             @if($foto->judul) title="{{ $foto->judul }}" @endif>
          <div class="masonry-img-wrap">
            <img src="{{ Storage::url($foto->foto_path) }}"
                 alt="{{ $foto->judul ?? 'Foto galeri LAM Bengkalis' }}"
                 loading="lazy"
                 class="masonry-img">
            @if($foto->judul || $foto->deskripsi)
            <div class="masonry-caption">
              @if($foto->judul)<p class="masonry-caption__title">{{ $foto->judul }}</p>@endif
              @if($foto->deskripsi)<p class="masonry-caption__desc">{{ $foto->deskripsi }}</p>@endif
            </div>
            @endif
            <div class="masonry-overlay"></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endif

<style>
  /* ── JS Masonry Layout (React Port) ───────────────────────── */
  #masonry-container {
    position: relative;
    width: 100%;
    min-height: 500px;
    margin: 1rem 0;
  }

  .masonry-item {
    position: absolute;
    will-change: transform, width, height, opacity;
    padding: 6px;
    cursor: pointer;
    top: 0; left: 0;
    /* Mulai dengan opacity 0, GSAP akan menampilkannya */
    opacity: 0;
  }

  .masonry-img-wrap {
    position: relative;
    width: 100%; height: 100%;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 50px -10px rgba(0,0,0,.2);
    background: #1a1a1a;
  }
  
  .masonry-img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    pointer-events: none;
  }

  /* Overlay gradient */
  .masonry-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.75) 0%, transparent 60%);
    opacity: 0;
    transition: opacity .3s;
    border-radius: 10px;
    pointer-events: none;
  }
  .masonry-item:hover .masonry-overlay { opacity: 1; }

  /* Gold border on hover */
  .masonry-img-wrap::after {
    content: '';
    position: absolute; inset: 0;
    border-radius: 10px;
    border: 2px solid var(--lam-gold);
    opacity: 0;
    transition: opacity .3s;
    pointer-events: none;
  }
  .masonry-item:hover .masonry-img-wrap::after { opacity: 1; }

  /* Caption */
  .masonry-caption {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 1.25rem 1rem 1rem;
    z-index: 2;
    transform: translateY(10px);
    opacity: 0;
    transition: transform .3s, opacity .3s;
    pointer-events: none;
  }
  .masonry-item:hover .masonry-caption { transform: translateY(0); opacity: 1; }
  .masonry-caption__title {
    font-family: var(--font-head);
    font-size: 1rem;
    font-weight: 700;
    color: white;
    margin-bottom: .2rem;
    text-shadow: 0 2px 4px rgba(0,0,0,.8);
  }
  .masonry-caption__desc {
    font-size: .8rem;
    color: rgba(255,255,255,.8);
    text-shadow: 0 1px 2px rgba(0,0,0,.8);
  }

  /* Lightbox */
  .masonry-lightbox {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.92);
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(8px);
    animation: lbFadeIn .2s ease;
  }
  @keyframes lbFadeIn { from { opacity:0; } to { opacity:1; } }
  .masonry-lightbox img {
    max-width: 90vw; max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 0 0 1px rgba(249,149,34,.3);
  }
  .masonry-lightbox__close {
    position: absolute; top: 1.5rem; right: 1.5rem;
    background: rgba(255,255,255,.1); border: none; border-radius: 50%;
    width: 44px; height: 44px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: white; transition: background .2s;
  }
  .masonry-lightbox__close:hover { background: rgba(249,149,34,.4); }
  .masonry-lightbox__caption {
    position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
    text-align: center; color: rgba(255,255,255,.9); font-size: 1rem;
    max-width: 600px; padding: 0 1rem;
  }
</style>

@push('body_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script>
(function() {
  const fotoData = @json($fotosJson);
  let lightboxEl = null;

  // Konfigurasi animasi
  const config = {
    ease: 'power3.out',
    duration: 0.6,
    stagger: 0.05,
    animateFrom: 'bottom',
    hoverScale: 0.95,
    blurToFocus: true
  };

  // Preload gambar
  const preloadImages = async (urls) => {
    await Promise.all(
      urls.map(src => new Promise(resolve => {
        const img = new Image();
        img.src = src;
        img.onload = img.onerror = () => resolve();
      }))
    );
  };

  // Hitung jumlah kolom berdasarkan lebar viewport
  function getColumns(w) {
    if (w >= 1500) return 5;
    if (w >= 1000) return 4;
    if (w >= 600)  return 3;
    if (w >= 400)  return 2;
    return 1;
  }

  function getInitialPosition(item, animateFrom) {
    const containerRect = document.getElementById('masonry-container')?.getBoundingClientRect();
    if (!containerRect) return { x: item.x, y: item.y };

    let direction = animateFrom;
    if (animateFrom === 'random') {
      const directions = ['top', 'bottom', 'left', 'right'];
      direction = directions[Math.floor(Math.random() * directions.length)];
    }

    switch (direction) {
      case 'top':    return { x: item.x, y: -200 };
      case 'bottom': return { x: item.x, y: window.innerHeight + 200 };
      case 'left':   return { x: -200, y: item.y };
      case 'right':  return { x: window.innerWidth + 200, y: item.y };
      case 'center': return { x: containerRect.width / 2 - item.w / 2, y: containerRect.height / 2 - item.h / 2 };
      default:       return { x: item.x, y: item.y + 100 };
    }
  }

  let hasMounted = false;

  function layoutMasonry() {
    const container = document.getElementById('masonry-container');
    if (!container || typeof gsap === 'undefined') return;

    const itemsEl = Array.from(container.querySelectorAll('.masonry-item'));
    if (!itemsEl.length) return;

    const width = container.offsetWidth;
    const cols = getColumns(window.innerWidth);
    const colW = width / cols;
    const colHeights = new Array(cols).fill(0);

    // Hitung grid layout (Grid calculation murni)
    const grid = fotoData.map((data, index) => {
      const col = colHeights.indexOf(Math.min(...colHeights));
      const x = colW * col;
      // Gunakan tinggi dataset / 2 seperti pengaturan sebelumnya, atau dataset jika tersedia
      const rawH = data.height || 400;
      const height = rawH / 2; 
      const y = colHeights[col];

      colHeights[col] += height;
      
      return { id: data.id, x, y, w: colW, h: height, el: itemsEl[index] };
    });

    container.style.height = Math.max(...colHeights) + 'px';

    // Animasi menggunakan state
    grid.forEach((item, index) => {
      const animationProps = {
        x: item.x,
        y: item.y,
        width: item.w,
        height: item.h
      };

      if (!hasMounted) {
        const initialPos = getInitialPosition(item, config.animateFrom);
        const initialState = {
          opacity: 0,
          x: initialPos.x,
          y: initialPos.y,
          width: item.w,
          height: item.h,
          ...(config.blurToFocus && { filter: 'blur(10px)' })
        };

        gsap.fromTo(item.el, initialState, {
          opacity: 1,
          ...animationProps,
          ...(config.blurToFocus && { filter: 'blur(0px)' }),
          duration: 0.8,
          ease: config.ease,
          delay: index * config.stagger
        });
      } else {
        gsap.to(item.el, {
          ...animationProps,
          duration: config.duration,
          ease: config.ease,
          overwrite: 'auto'
        });
      }
    });

    hasMounted = true;
  }

  function init() {
    // Jalankan setelah script gsap & gambar siap
    preloadImages(fotoData.map(i => i.src)).then(() => {
      layoutMasonry();
      
      // Setup Hover Animations
      document.querySelectorAll('.masonry-item').forEach(el => {
        el.addEventListener('mouseenter', () => {
          gsap.to(el, { scale: config.hoverScale, duration: 0.3, ease: 'power2.out' });
        });
        el.addEventListener('mouseleave', () => {
          gsap.to(el, { scale: 1, duration: 0.3, ease: 'power2.out' });
        });
        el.addEventListener('click', () => openLightbox(el.dataset.id));
      });

      // Resize observer
      const ro = new ResizeObserver(() => layoutMasonry());
      ro.observe(document.getElementById('masonry-container'));
    });
  }

  function checkGsap(attempts = 30) {
    if (typeof gsap !== 'undefined') init();
    else if (attempts > 0) setTimeout(() => checkGsap(attempts - 1), 100);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => checkGsap());
  } else {
    checkGsap();
  }


  // ── Lightbox Logic ──────────────────────────────────────────────────────────
  function openLightbox(id) {
    const foto = fotoData.find(f => String(f.id) === String(id));
    if (!foto) return;

    if (lightboxEl) lightboxEl.remove();

    lightboxEl = document.createElement('div');
    lightboxEl.className = 'masonry-lightbox';
    lightboxEl.innerHTML = `
      <button class="masonry-lightbox__close" aria-label="Tutup">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <img src="${foto.src}" alt="${foto.judul ?? 'Foto galeri'}">
      ${foto.judul ? `<div class="masonry-lightbox__caption"><strong>${foto.judul}</strong>${foto.deskripsi ? '<br><span style="opacity:.7;font-size:.85rem;">' + foto.deskripsi + '</span>' : ''}</div>` : ''}
    `;

    document.body.appendChild(lightboxEl);
    document.body.style.overflow = 'hidden';

    lightboxEl.querySelector('.masonry-lightbox__close').addEventListener('click', closeLightbox);
    lightboxEl.addEventListener('click', e => { if (e.target === lightboxEl) closeLightbox(); });
    document.addEventListener('keydown', onKeyDown);
  }

  function closeLightbox() {
    if (lightboxEl) {
      lightboxEl.remove();
      lightboxEl = null;
      document.body.style.overflow = '';
      document.removeEventListener('keydown', onKeyDown);
    }
  }

  function onKeyDown(e) {
    if (e.key === 'Escape') closeLightbox();
  }
})();
</script>
@endpush


@endsection
