@extends('layouts.app')

@section('title', 'Profil Lembaga — ' . ($setting->nama_lembaga ?? 'LAMR Bengkalis'))
@section('meta_description', $setting->meta_deskripsi ?? '')

@section('content')

{{-- Page Header --}}
@php $heroBg = $setting->heroUrl('profil'); @endphp
<div class="page-hero" style="{{ $heroBg ? 'background-image:url('.$heroBg.')' : '' }}">
  <div class="page-hero__overlay"></div>
  @if($heroBg)<div class="page-hero__gold-edge"></div>@endif
  <div class="container" style="position:relative;z-index:2;text-align:center;">
    <p style="font-size:.75rem;letter-spacing:.25em;text-transform:uppercase;color:var(--lam-gold);font-weight:600;margin-bottom:.75rem;">Tentang Kami</p>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.75rem,4vw,2.75rem);color:white;">Profil Lembaga Adat Melayu Riau</h1>
    <p style="color:rgba(255,255,255,.7);margin-top:.75rem;">{{ $setting->nama_lembaga ?? 'LAMR Bengkalis' }}</p>
  </div>
</div>

<style>
  .page-hero {
    position: relative;
    padding: 5rem 0 4rem;
    text-align: center;
    background-color: var(--lam-black);
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
  .page-hero__overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,.55) 0%, rgba(0,0,0,.4) 60%, rgba(0,0,0,.7) 100%);
    z-index: 1;
  }
  .page-hero__gold-edge {
    position: absolute; inset: 0;
    background:
      linear-gradient(to right, rgba(249,149,34,.35) 0%, transparent 18%, transparent 82%, rgba(249,149,34,.35) 100%),
      linear-gradient(to bottom, rgba(249,149,34,.2) 0%, transparent 30%);
    z-index: 1;
    pointer-events: none;
  }
</style>



{{-- Root Alpine Component untuk Profil (Scrollspy) --}}
<div x-data="profileScrollspy()" @scroll.window="onScroll" style="position:relative;">

  {{-- Tab Navigation --}}
  <div style="background:var(--lam-green);position:sticky;top:64px;z-index:50;box-shadow:0 4px 15px rgba(0,0,0,0.1);" x-init="init()">
    <div class="container" style="position:relative; padding:0;">
      
      <!-- Left Arrow -->
      <button class="nav-arrow left-arrow" x-show="canScrollLeft" @click="scrollNav('left')" style="display:none;" x-transition.opacity>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
      </button>

      <div class="nav-scroll-container" x-ref="navContainer" @scroll.passive="checkNavScroll" style="overflow-x:auto;scrollbar-width:none;-ms-overflow-style:none;">
        <div class="radio-inputs">
          @foreach([
            ['id'=>'sejarah',      'label'=>'Sejarah'],
            ['id'=>'visi-misi',    'label'=>'Visi & Misi'],
            ['id'=>'tugas-fungsi', 'label'=>'Tugas & Fungsi'],
            ['id'=>'dasar-hukum',  'label'=>'Dasar Hukum'],
            ['id'=>'struktur',     'label'=>'Struktur Organisasi'],
          ] as $tab)
            <label class="radio" :class="activeTab === '{{ $tab['id'] }}' ? 'is-active' : ''">
              <span class="name" @click.prevent="scrollTo('{{ $tab['id'] }}')">
                <span class="pre-name"></span>
                <span class="pos-name"></span>
                <span style="white-space:nowrap;">{{ $tab['label'] }}</span>
              </span>
            </label>
          @endforeach
        </div>
      </div>

      <!-- Right Arrow -->
      <button class="nav-arrow right-arrow" x-show="canScrollRight" @click="scrollNav('right')" style="display:none;" x-transition.opacity>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
      </button>

    </div>
  </div>

  <div class="section-pad" style="background:var(--lam-cream);min-height:60vh;padding-top:3rem;">
    <div class="container">

      {{-- Sejarah --}}
      <div id="sejarah" class="scroll-section">
        @if(!empty($sejarahTimeline) && count($sejarahTimeline) > 0)
          <div class="section-heading" style="text-align:center;margin-bottom:3rem;">
            <span class="section-heading__eyebrow">Latar Belakang</span>
            <h2 class="section-heading__title">Sejarah LAMR Bengkalis</h2>
            <div class="section-heading__divider"><span></span><i></i><span></span></div>
          </div>

          <div class="timeline-container">
            @foreach($sejarahTimeline as $idx => $item)
              @php 
                $isRight = $idx % 2 !== 0; 
                $alignClass = $isRight ? 'timeline-right' : 'timeline-left';
              @endphp
              <div class="timeline-item {{ $alignClass }}">
                <div class="timeline-content">
                  @if(!empty($item['gambar']))
                    <img src="{{ Storage::url($item['gambar']) }}" alt="{{ $item['tahun'] ?? 'Sejarah' }}" class="timeline-img" loading="lazy">
                  @endif
                  <h3>{{ $item['tahun'] ?? '' }}</h3>
                  <div class="prose-konten">
                    {!! $item['deskripsi'] ?? '' !!}
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p style="text-align:center;color:var(--lam-text-l);padding:3rem 0;">Konten Sejarah belum tersedia.</p>
        @endif
      </div>

      {{-- Visi & Misi --}}
      <div id="visi-misi" class="scroll-section" style="padding-top:3rem;">
        <div style="max-width:820px;margin:0 auto;">
          @if($konten->get('visi-lam'))
            <div style="background:var(--lam-green);border-radius:var(--radius);padding:2.5rem;margin-bottom:2rem;text-align:center;">
              <p style="font-size:.7rem;letter-spacing:.25em;color:var(--lam-gold);text-transform:uppercase;font-weight:700;margin-bottom:.75rem;">Visi</p>
              <p style="font-family:var(--font-head);font-size:clamp(1.1rem,2.5vw,1.5rem);color:white;font-style:italic;line-height:1.6;">
                "{{ $konten->get('visi-lam')?->konten }}"
              </p>
            </div>
          @endif

          @if(!empty($misiPoin))
            <h3 style="font-family:var(--font-head);color:var(--lam-green);margin-bottom:1.25rem;">Misi</h3>
            <ol style="list-style:none;counter-reset:misi-counter;display:flex;flex-direction:column;gap:.875rem;">
              @foreach($misiPoin as $idx => $poin)
                <li style="counter-increment:misi-counter;display:flex;gap:1rem;align-items:flex-start;
                           background:var(--lam-bg-alt);border-radius:var(--radius-sm);padding:1rem 1.25rem;
                           box-shadow:0 2px 8px rgba(0,0,0,.5);border-left:4px solid var(--lam-gold);">
                  <span style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:var(--lam-gold);
                               color:var(--lam-green);font-weight:700;font-size:.8rem;
                               display:flex;align-items:center;justify-content:center;">{{ $loop->iteration }}</span>
                  <span style="color:var(--lam-text-m);line-height:1.7;">{{ $poin }}</span>
                </li>
              @endforeach
            </ol>
          @endif
        </div>
      </div>

      {{-- Tugas & Fungsi --}}
      <div id="tugas-fungsi" class="scroll-section" style="padding-top:3rem;">
        @if($konten->get('tugas-fungsi'))
          <div style="max-width:820px;margin:0 auto;">
            <h2 style="font-family:var(--font-head);color:var(--lam-green);margin-bottom:1.5rem;">Tugas dan Fungsi</h2>
            <div class="prose-konten">{!! $konten->get('tugas-fungsi')?->konten !!}</div>
          </div>
        @endif
      </div>

      {{-- Dasar Hukum --}}
      <div id="dasar-hukum" class="scroll-section" style="padding-top:3rem;">
        @if($konten->get('dasar-hukum'))
          <div style="max-width:820px;margin:0 auto;">
            <h2 style="font-family:var(--font-head);color:var(--lam-green);margin-bottom:1.5rem;">Dasar Hukum</h2>
            <div class="prose-konten">{!! $konten->get('dasar-hukum')?->konten !!}</div>
          </div>
        @endif
      </div>

      {{-- Struktur Organisasi --}}
      <div id="struktur" class="scroll-section" style="padding-top:3rem;">
      @php
        function groupByUrutan($koleksi) {
            $groups = [];
            foreach($koleksi as $anggota) {
                $groups[$anggota->urutan][] = $anggota;
            }
            ksort($groups); // Pastikan urutan dari kecil ke besar
            return $groups;
        }
        $groupedMka = groupByUrutan($strukturMka);
        $groupedDph = groupByUrutan($strukturDph);
      @endphp

      <style>
        .org-chart-wrapper {
          width: 100%;
          overflow-x: auto;
          -ms-overflow-style: none;
          scrollbar-width: thin;
          padding-bottom: 1rem;
        }
        .org-chart {
          display: flex;
          flex-direction: column;
          align-items: center;
          padding-top: 1rem;
          min-width: max-content; /* Ensure it takes full width of its children */
        }
        .org-group {
          display: flex;
          flex-wrap: nowrap; /* Prevent wrapping so lines stay intact */
          justify-content: center;
          position: relative;
          width: max-content;
          gap: 1rem; /* Adjust gap if needed, though card-wrapper has padding */
        }
        .org-group + .org-group {
          margin-top: 3.5rem;
        }
        /* Vertical connecting line dari group atas ke group ini */
        .org-group + .org-group::before {
          content: '';
          position: absolute;
          top: -3.5rem;
          left: 50%;
          transform: translateX(-50%);
          width: 2px;
          height: 3.5rem;
          background: var(--lam-gold);
        }
        .org-card-wrapper {
          position: relative;
          padding: 2rem 1rem 0;
        }
        .org-group:first-child .org-card-wrapper {
          padding-top: 0;
        }
        /* Vertical line turun ke card */
        .org-group:not(:first-child) .org-card-wrapper::before {
          content: '';
          position: absolute;
          top: 0;
          left: 50%;
          transform: translateX(-50%);
          width: 2px;
          height: 2rem;
          background: var(--lam-gold);
        }
        /* Horizontal line connecting cards */
        .org-group:not(:first-child) .org-card-wrapper::after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 2px;
          background: var(--lam-gold);
        }
        .org-group:not(:first-child) .org-card-wrapper:first-child::after {
          left: 50%;
          width: 50%;
        }
        .org-group:not(:first-child) .org-card-wrapper:last-child::after {
          left: 0;
          width: 50%;
        }
        .org-group:not(:first-child) .org-card-wrapper:first-child:last-child::after {
          display: none;
        }
        
        .org-card {
          width: 180px; /* Slightly narrower to fit better on mobile screens */
          text-align: center;
          background: var(--lam-bg-alt);
          border-radius: var(--radius);
          padding: 1.5rem 1rem;
          box-shadow: 0 4px 15px rgba(11,79,48,.08);
          position: relative;
          border-top: 4px solid var(--card-color, var(--lam-gold));
          transition: transform 0.2s;
        }
        .org-card:hover {
          transform: translateY(-5px);
        }
        .org-card__img {
          width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
          margin: 0 auto 1rem; border: 3px solid var(--card-color, var(--lam-gold));
        }
        .org-card__placeholder {
          width: 80px; height: 80px; border-radius: 50%; background: var(--lam-cream);
          margin: 0 auto 1rem; border: 3px solid var(--card-color, var(--lam-gold));
          display: flex; align-items: center; justify-content: center;
        }

        .scroll-hint {
          display: none;
        }
        
        @media (max-width: 768px) {
          .org-card {
             width: clamp(70px, calc((100vw - 2rem) / var(--count) - 0.5rem), 140px);
             padding: 0.5rem 0.2rem;
          }
          .org-card__img, .org-card__placeholder {
             width: clamp(35px, calc(50vw / var(--count)), 55px);
             height: clamp(35px, calc(50vw / var(--count)), 55px);
             margin-bottom: 0.4rem;
             border-width: 2px;
          }
          .org-card-name {
             font-size: clamp(0.65rem, calc(7vw / var(--count)), 0.9rem) !important;
          }
          .org-card-title {
             font-size: clamp(0.55rem, calc(5.5vw / var(--count)), 0.75rem) !important;
          }
          .scroll-hint {
             display: flex !important;
             align-items: center;
             justify-content: center;
             gap: 0.5rem;
             font-size: 0.75rem;
             color: var(--lam-gold);
             background: rgba(249, 149, 34, 0.1);
             padding: 0.4rem 1rem;
             border-radius: 20px;
             margin: 0 auto 1.5rem;
             width: fit-content;
          }
          .scroll-hint svg {
             animation: slide-hint 1.5s ease-in-out infinite;
          }
          @keyframes slide-hint {
             0%, 100% { transform: translateX(-3px); }
             50% { transform: translateX(3px); }
          }
          /* Hierarchy lines are no longer hidden! */
        }
      </style>

      @php
        $hasManyMka = false;
        foreach($groupedMka as $anggotas) {
            if(count($anggotas) > 4) { $hasManyMka = true; break; }
        }
        $hasManyDph = false;
        foreach($groupedDph as $anggotas) {
            if(count($anggotas) > 4) { $hasManyDph = true; break; }
        }
      @endphp

      @if($strukturMka->count() > 0)
        <div style="margin-bottom:5rem;">
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:1.5rem;font-size:1.6rem;">
            Majelis Kerapatan Adat (MKA)
          </h3>
          @if($hasManyMka)
          <div class="scroll-hint">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 9l-4 3 4 3"/><path d="M16 9l4 3-4 3"/><path d="M4 12h16"/></svg>
            <span>Geser untuk melihat bagian tepi</span>
          </div>
          @endif
          <div class="org-chart-wrapper">
            <div class="org-chart" style="--card-color: var(--lam-gold);">
              @foreach($groupedMka as $urutan => $anggotas)
                <div class="org-group" style="--count: {{ count($anggotas) }};">
                  @foreach($anggotas as $anggota)
                    <div class="org-card-wrapper">
                      <div class="org-card">
                      @if($anggota->foto)
                        <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" class="org-card__img" loading="lazy">
                      @else
                        <div class="org-card__placeholder" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="var(--lam-green)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                      @endif
                      <p class="org-card-name" style="font-weight:700;color:var(--lam-text);font-size:.95rem;margin-bottom:.25rem;line-height:1.2;">{{ $anggota->nama }}</p>
                      <p class="org-card-title" style="font-size:.8rem;color:var(--lam-green);font-weight:600;line-height:1.2;">{{ $anggota->jabatan }}</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif

      @if($strukturDph->count() > 0)
        <div>
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:1.5rem;font-size:1.6rem;">
            Dewan Pengurus Harian (DPH)
          </h3>
          @if($hasManyDph)
          <div class="scroll-hint">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 9l-4 3 4 3"/><path d="M16 9l4 3-4 3"/><path d="M4 12h16"/></svg>
            <span>Geser untuk melihat bagian tepi</span>
          </div>
          @endif
          <div class="org-chart-wrapper">
            <div class="org-chart" style="--card-color: var(--lam-maroon);">
              @foreach($groupedDph as $urutan => $anggotas)
                <div class="org-group" style="--count: {{ count($anggotas) }};">
                  @foreach($anggotas as $anggota)
                    <div class="org-card-wrapper">
                      <div class="org-card">
                      @if($anggota->foto)
                        <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" class="org-card__img" loading="lazy">
                      @else
                        <div class="org-card__placeholder" aria-hidden="true">
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="var(--lam-maroon)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                      @endif
                      <p class="org-card-name" style="font-weight:700;color:var(--lam-text);font-size:.95rem;margin-bottom:.25rem;line-height:1.2;">{{ $anggota->nama }}</p>
                      <p class="org-card-title" style="font-size:.8rem;color:var(--lam-maroon);font-weight:600;line-height:1.2;">{{ $anggota->jabatan }}</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif

      @if($strukturMka->count() === 0 && $strukturDph->count() === 0)
        <p style="text-align:center;color:var(--lam-text-l);padding:3rem 0;">Data struktur organisasi belum tersedia.</p>
      @endif
    </div>

  </div>
</div> {{-- End Root Alpine Component --}}

<style>
  .prose-konten { color: var(--lam-text-m); line-height: 1.85; }
  .prose-konten p { margin-bottom: 1rem; text-align: justify; }
  .prose-konten h2, .prose-konten h3 { color: var(--lam-green); margin: 1.5rem 0 .75rem; }
  .prose-konten ul, .prose-konten ol { margin-left: 1.5rem; margin-bottom: 1rem; }
  .prose-konten li { margin-bottom: .4rem; }
  .prose-konten blockquote { border-left: 4px solid var(--lam-gold); padding-left: 1rem; color: var(--lam-text-l); font-style: italic; }
  .prose-konten a { color: var(--lam-green); text-decoration: underline; }

  /* Uiverse Tabs adapted for scroll navigation */
  .nav-scroll-container::-webkit-scrollbar { display: none; }
  .nav-arrow {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 40px;
    background: transparent;
    color: var(--lam-cream);
    border: none;
    z-index: 10;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .left-arrow { left: 0; background: linear-gradient(to right, var(--lam-green) 50%, transparent); }
  .right-arrow { right: 0; background: linear-gradient(to left, var(--lam-green) 50%, transparent); }
  
  @media (min-width: 769px) {
    .nav-arrow { display: none !important; }
  }

  .radio-inputs {
    position: relative;
    display: flex;
    flex-wrap: nowrap;
    background-color: var(--lam-green);
    font-size: 14px;
    width: max-content;
    min-width: 100%;
    padding: 0.5rem 1.5rem 0 1.5rem;
    justify-content: center;
  }
  .radio-inputs .radio { 
    margin: 0; 
    flex: 0 0 auto; /* Prevent tabs from shrinking */
  }
  .radio-inputs .radio .name {
    display: flex;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    border: none;
    transition: all 0.15s ease-in-out;
    position: relative;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    color: rgba(255,255,255,0.8);
  }
  .radio-inputs .radio:hover .name { color: #fff; }
  .radio-inputs .radio .pre-name,
  .radio-inputs .radio .pos-name {
    content: "";
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: var(--lam-green);
    bottom: 0;
    opacity: 0;
    transition: opacity 0.1s;
  }
  .radio-inputs .radio .pre-name {
    right: -10px;
    border-bottom-left-radius: 300px;
    box-shadow: -3px 3px 0px 3px var(--lam-cream);
  }
  .radio-inputs .radio .pos-name {
    left: -10px;
    border-bottom-right-radius: 300px;
    box-shadow: 3px 3px 0px 3px var(--lam-cream);
  }
  .radio-inputs .radio.is-active .name {
    background-color: var(--lam-cream);
    font-weight: 600;
    cursor: default;
    color: var(--lam-text);
  }
  .radio-inputs .radio.is-active .pre-name,
  .radio-inputs .radio.is-active .pos-name {
    opacity: 1;
    z-index: 0;
  }
  .radio-inputs .radio .name span:last-child {
    z-index: 1;
    padding: 0.75rem 1.25rem;
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    background-color: transparent;
    transition: background-color 0.1s;
  }
  .radio-inputs .radio.is-active .name span:last-child {
    background-color: var(--lam-cream);
  }
  .scroll-section { scroll-margin-top: 140px; }

  /* ─── Timeline Styles ──────────────────────────────────────── */
  .timeline-container {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
  }
  /* Garis tengah timeline */
  .timeline-container::after {
    content: '';
    position: absolute;
    width: 3px;
    background: linear-gradient(to bottom, var(--lam-gold-d), var(--lam-gold), var(--lam-gold-d));
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -1.5px;
    border-radius: 2px;
  }

  /* ─── Timeline Item ─────────────────────────────────────────── */
  .timeline-item {
    padding: 10px 40px;
    position: relative;
    background-color: inherit;
    width: 50%;
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    z-index: 1;
  }
  .timeline-item.show {
    opacity: 1;
    transform: translateY(0);
  }
  .timeline-left  { left: 0; }
  .timeline-right { left: 50%; }

  /* ─── Titik Penanda — Pulsing Dot ───────────────────────────── */
  .timeline-item::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    right: -10px;
    background: radial-gradient(circle, #fff 40%, var(--lam-gold) 100%);
    border: 3px solid var(--lam-gold);
    top: 26px;
    border-radius: 50%;
    z-index: 2;
    /* Pulse animation */
    animation: tl-dot-pulse 2.4s ease-in-out infinite;
    box-shadow: 0 0 0 0 rgba(249,149,34,.75);
  }
  .timeline-right::after { left: -10px; right: auto; }

  /* Inner glow shimmer on dot */
  .timeline-item::before {
    content: '';
    position: absolute;
    width: 8px;
    height: 8px;
    right: -4px;
    top: 32px;
    border-radius: 50%;
    background: var(--lam-gold);
    z-index: 3;
    opacity: .85;
    animation: tl-dot-inner 2.4s ease-in-out infinite;
  }
  .timeline-right::before { left: -4px; right: auto; }

  @keyframes tl-dot-pulse {
    0%   { box-shadow: 0 0 0 0   rgba(249,149,34,.75); }
    50%  { box-shadow: 0 0 0 10px rgba(249,149,34,0);  }
    100% { box-shadow: 0 0 0 0   rgba(249,149,34,0);   }
  }
  @keyframes tl-dot-inner {
    0%,100% { transform: scale(1);    opacity: .85; }
    50%      { transform: scale(1.35); opacity: 1;   }
  }

  /* ─── Kotak Timeline Content ────────────────────────────────── */
  .timeline-content {
    padding: 1.5rem;
    background-color: white;
    border: 2px solid rgba(249,149,34,.4);
    position: relative;
    border-radius: var(--radius);
    box-shadow: var(--lam-shadow);
    transition:
      transform .35s cubic-bezier(.34,1.56,.64,1),
      box-shadow .35s ease,
      border-color .35s ease;
    cursor: default;
    /* Stacking context */
    isolation: isolate;
  }
  .timeline-content h3 {
    margin-top: 0;
    color: var(--lam-black);
    font-family: var(--font-head);
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }
  .timeline-content .prose-konten,
  .timeline-content .prose-konten p,
  .timeline-content .prose-konten li {
    color: var(--lam-black-l);
  }

  /* Hover: card naik & tajam di atas overlay blur */
  .timeline-content:hover {
    transform: translateY(-6px) scale(1.025);
    box-shadow:
      0 32px 64px rgba(249,149,34,.22),
      0 12px 28px rgba(0,0,0,.25);
    border-color: var(--lam-gold);
  }
  /* Elevasi item yang di-hover di atas overlay */
  .timeline-item:has(.timeline-content:hover) {
    z-index: 500;
  }

  /* ─── Blur Overlay (full-screen, CSS :has) ──────────────────── */
  #timeline-blur-overlay {
    position: fixed;
    inset: 0;
    background: rgba(5, 5, 5, .45);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 200;
    opacity: 0;
    pointer-events: none;
    transition: opacity .3s ease;
  }
  /* Aktif saat ada .timeline-content yang di-hover */
  html:has(.timeline-content:hover) #timeline-blur-overlay {
    opacity: 1;
  }

  .timeline-img {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    margin-bottom: 1.25rem;
  }

  /* ─── Responsive ─────────────────────────────────────────────── */
  @media screen and (max-width: 768px) {
    .timeline-item {
      padding: 10px 10px;
    }
    .timeline-item::after {
      width: 14px;
      height: 14px;
      right: -7px;
      top: 16px;
      border-width: 2.5px;
    }
    .timeline-item::before {
      width: 6px;
      height: 6px;
      right: -3px;
      top: 20px;
    }
    .timeline-right::after { left: -7px; right: auto; }
    .timeline-right::before { left: -3px; right: auto; }
    .timeline-content {
      padding: 0.75rem;
    }
    .timeline-content h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }
    .timeline-img {
      max-height: 120px;
      margin-bottom: 0.75rem;
    }
    .timeline-content .prose-konten {
      font-size: 0.85rem;
    }
    .timeline-content .prose-konten p,
    .timeline-content .prose-konten li {
      text-align: left;
    }
    .radio-inputs { justify-content: flex-start; padding: 0.5rem 1rem 0; }
  }

  /* Reduced motion — matikan animasi dot */
  @media (prefers-reduced-motion: reduce) {
    .timeline-item::after,
    .timeline-item::before {
      animation: none !important;
    }
    .timeline-content:hover {
      transform: none;
    }
  }
</style>

{{-- Full-screen blur overlay (diaktifkan via CSS :has saat timeline-content di-hover) --}}
<div id="timeline-blur-overlay" aria-hidden="true"></div>

@endsection

@push('body_scripts')
<script>
  function profileScrollspy() {
    return {
      activeTab: 'sejarah',
      isScrolling: false,
      sections: ['sejarah', 'visi-misi', 'tugas-fungsi', 'dasar-hukum', 'struktur'],
      canScrollLeft: false,
      canScrollRight: false,
      
      init() {
        setTimeout(() => this.checkNavScroll(), 200);
        window.addEventListener('resize', () => this.checkNavScroll());
      },
      checkNavScroll() {
        const el = this.$refs.navContainer;
        if (el) {
          // Hanya tampilkan panah pada mobile (width < 769px)
          if (window.innerWidth <= 768) {
            this.canScrollLeft = el.scrollLeft > 5;
            this.canScrollRight = el.scrollLeft < (el.scrollWidth - el.clientWidth - 5);
          } else {
            this.canScrollLeft = false;
            this.canScrollRight = false;
          }
        }
      },
      scrollNav(dir) {
        const el = this.$refs.navContainer;
        if (el) {
          const scrollAmount = 180;
          el.scrollBy({ left: dir === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' });
          setTimeout(() => this.checkNavScroll(), 400); // Check again after animation
        }
      },
      
      scrollTo(id) {
        this.activeTab = id;
        this.isScrolling = true;
        const el = document.getElementById(id);
        if (el) {
          el.scrollIntoView({ behavior: 'smooth' });
          setTimeout(() => { this.isScrolling = false; }, 800);
        }
      },
      onScroll() {
        if (this.isScrolling) return;
        let current = this.sections[0];
        for (let id of this.sections) {
          const el = document.getElementById(id);
          if (el) {
            const rect = el.getBoundingClientRect();
            if (rect.top <= 160) {
              current = id;
            }
          }
        }
        this.activeTab = current;
      }
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
        }
      });
    }, { threshold: 0.1, rootMargin: "0px 0px -50px 0px" });

    document.querySelectorAll('.timeline-item').forEach(el => {
      observer.observe(el);
    });
  });
</script>
@endpush
