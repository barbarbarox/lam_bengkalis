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
  <div style="background:linear-gradient(to top, var(--lam-gold) 0%, var(--lam-black) 100%);position:sticky;top:64px;z-index:50;box-shadow:0 4px 15px rgba(0,0,0,0.1);" x-init="init()">
    <div class="container" style="position:relative; padding:0;">
      
      <!-- Left Arrow -->
      <button class="nav-arrow left-arrow" x-show="canScrollLeft" @click="scrollNav('left')" style="display:none;" x-transition.opacity>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
      </button>

      <div class="nav-scroll-container" x-ref="navContainer" @scroll.passive="checkNavScroll" style="overflow-x:auto;scrollbar-width:none;-ms-overflow-style:none;">
        <div class="radio-inputs">
          @foreach([
            ['id'=>'sambutan',     'label'=>'Kata Sambutan'],
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

      {{-- Kata Sambutan --}}
      <div id="sambutan" class="scroll-section">
        @if($sambutan)
        <div class="section-heading" style="text-align:center;margin-bottom:3rem;">
          <span class="section-heading__eyebrow">Dari Pimpinan</span>
          <h2 class="section-heading__title">Kata Sambutan</h2>
          <div class="section-heading__divider"><span></span><i></i><span></span></div>
        </div>

        <div style="max-width:900px;margin:0 auto;display:flex;gap:3rem;align-items:flex-start;flex-wrap:wrap;">
          {{-- Foto & Identitas Ketua --}}
          <div style="flex:0 0 220px;text-align:center;">
            @if($sambutan->foto)
              <img src="{{ Storage::url($sambutan->foto) }}"
                   alt="{{ $sambutan->nama_ketua }}"
                   style="width:180px;height:220px;object-fit:cover;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.15);border:4px solid var(--lam-gold);">
            @else
              <div style="width:180px;height:220px;border-radius:12px;background:linear-gradient(135deg,var(--lam-green),var(--lam-black));display:flex;align-items:center;justify-content:center;margin:0 auto;border:4px solid var(--lam-gold);">
                <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
              </div>
            @endif
            <p style="margin-top:1rem;font-weight:700;color:var(--lam-black);font-size:.95rem;">{{ $sambutan->nama_ketua }}</p>
            <p style="font-size:.8rem;color:var(--lam-green);font-weight:600;">{{ $sambutan->jabatan }}</p>
            @if($sambutan->periode_mulai)
              <p style="font-size:.75rem;color:#777;margin-top:.25rem;">Periode {{ $sambutan->periodeLabel() }}</p>
            @endif
          </div>

          {{-- Teks Sambutan --}}
          <div style="flex:1;min-width:260px;">
            {{-- Tanda kutip dekoratif --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 100 100"
                 style="color:var(--lam-gold);opacity:.3;margin-bottom:.5rem;" fill="currentColor">
              <path d="M30 20 Q10 35 10 55 Q10 70 22 75 Q34 80 40 70 Q46 60 40 50 Q34 40 30 45 Q26 35 30 20Z"/>
              <path d="M70 20 Q50 35 50 55 Q50 70 62 75 Q74 80 80 70 Q86 60 80 50 Q74 40 70 45 Q66 35 70 20Z"/>
            </svg>
            <div class="prose-konten sambutan-prose"
                 style="font-size:.97rem;line-height:1.85;color:#333;font-family:var(--font-body);font-style:italic;">
              {!! $sambutan->isi_sambutan !!}
            </div>
            <div style="margin-top:1.5rem;padding-top:1rem;border-top:2px solid var(--lam-gold);display:flex;align-items:center;gap:.75rem;">
              <div style="width:40px;height:3px;background:var(--lam-gold);border-radius:2px;"></div>
              <p style="font-weight:700;color:var(--lam-green);font-size:.9rem;">{{ $sambutan->nama_ketua }}</p>
            </div>
          </div>
        </div>
        @else
        <div style="text-align:center;padding:4rem 0;color:#aaa;">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <p>Kata sambutan belum tersedia.</p>
        </div>
        @endif
      </div>

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
              <div class="timeline-item {{ $alignClass }}" x-data="{ expanded: false }">
                <div class="timeline-content">
                  @if(!empty($item['gambar']))
                    <img src="{{ Storage::url($item['gambar']) }}" alt="{{ $item['tahun'] ?? 'Sejarah' }}" class="timeline-img" loading="lazy">
                  @endif
                  <h3>{{ $item['tahun'] ?? '' }}</h3>
                  <div class="timeline-prose-wrap" :class="{ 'is-collapsed': !expanded }">
                    <div class="prose-konten">
                      {!! $item['deskripsi'] ?? '' !!}
                    </div>
                    <div class="timeline-fade-overlay" x-show="!expanded" aria-hidden="true"></div>
                  </div>
                  <button
                    class="timeline-read-more"
                    @click.stop="expanded = !expanded"
                    :aria-expanded="expanded"
                    x-text="expanded ? 'Tutup ↑' : 'Baca Selengkapnya ↓'"
                  ></button>
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
      </div>      {{-- Struktur Organisasi --}}
      <div id="struktur" class="scroll-section" style="padding-top:3rem;">
      @php
        function groupByUrutan($koleksi) {
            $groups = [];
            foreach($koleksi as $anggota) {
                $groups[$anggota->urutan][] = $anggota;
            }
            ksort($groups);
            return $groups;
        }
        $groupedMka = groupByUrutan($strukturMka);
        $groupedDph = groupByUrutan($strukturDph);
      @endphp

      <style>
        /* ─── ORG CHART ROOT ───────────────────────────── */
        .org-tree-outer {
          width: 100%;
          overflow-x: auto;
          overflow-y: visible;
          scrollbar-width: none;
          -ms-overflow-style: none;
          padding-bottom: 1.5rem;
          display: flex;
          justify-content: center;
        }
        .org-tree-outer::-webkit-scrollbar { display: none; }

        .org-chart {
          display: inline-flex;
          flex-direction: column;
          align-items: center;
          padding: 1rem 2rem 1rem;
          min-width: max-content;
        }

        /* ─── EACH TIER ROW ─────────────────────────────── */
        .org-tier {
          display: flex;
          flex-direction: column;
          align-items: center;
          width: 100%;
        }

        /* Vertical connector from parent tier to this tier */
        .org-tier + .org-tier > .org-tier-connector {
          display: block;
          width: 2px;
          height: 2.5rem;
          background: var(--lam-gold);
          margin: 0 auto;
          flex-shrink: 0;
        }
        .org-tier-connector { display: none; }

        /* ─── ROW OF CARDS ──────────────────────────────── */
        .org-row {
          display: flex;
          flex-wrap: nowrap;
          justify-content: center;
          align-items: flex-start;
          position: relative;
        }

        /* ─── CARD WRAPPER & CONNECTORS ─────────────────── */
        .org-card-wrapper {
          position: relative;
          padding: 2rem 0.75rem 0;
          display: flex;
          flex-direction: column;
          align-items: center;
        }
        .org-tier:first-child .org-card-wrapper { padding-top: 0; }

        /* Vertical drop line per card (for non-first tiers) */
        .org-tier:not(:first-child) .org-row:not(.org-row--slider) .org-card-wrapper::before {
          content: '';
          position: absolute;
          top: 0; left: 50%;
          transform: translateX(-50%);
          width: 2px; height: 2rem;
          background: var(--lam-gold);
        }

        /* Horizontal bridge line */
        .org-tier:not(:first-child) .org-row:not(.org-row--slider) .org-card-wrapper::after {
          content: '';
          position: absolute;
          top: 0; left: 0;
          width: 100%; height: 2px;
          background: var(--lam-gold);
        }
        .org-tier:not(:first-child) .org-row:not(.org-row--slider) .org-card-wrapper:first-child::after {
          left: 50%; width: 50%;
        }
        .org-tier:not(:first-child) .org-row:not(.org-row--slider) .org-card-wrapper:last-child::after {
          left: 0; width: 50%;
        }
        .org-tier:not(:first-child) .org-row:not(.org-row--slider) .org-card-wrapper:first-child:last-child::after {
          display: none;
        }

        /* ─── SLIDER ROW (> 4 cards) ─────────────────────── */
        .org-row--slider {
          position: relative;
          width: calc(100vw - 2.5rem);
          max-width: 900px;
          overflow: hidden;
        }
        .org-row--slider .org-row-inner {
          display: flex;
          flex-wrap: nowrap;
          overflow-x: auto;
          scroll-behavior: smooth;
          scrollbar-width: none;
          -ms-overflow-style: none;
          padding: 0.5rem 52px;
          justify-content: flex-start;
        }
        .org-row--slider .org-row-inner::-webkit-scrollbar { display: none; }

        /* Connector line above slider row */
        .org-tier:not(:first-child) .org-row--slider::before {
          content: '';
          display: block;
          width: 2px;
          height: 2rem;
          background: var(--lam-gold);
          margin: 0 auto;
        }

        /* Card-wrappers inside slider: no connector lines */
        .org-row--slider .org-card-wrapper {
          padding: 0 0.5rem;
          flex-shrink: 0;
        }
        .org-row--slider .org-card-wrapper::before,
        .org-row--slider .org-card-wrapper::after { display: none !important; }

        /* ─── SLIDER BUTTONS ─────────────────────────────── */
        .row-slider-btn {
          position: absolute;
          top: 50%; transform: translateY(-50%);
          z-index: 20;
          width: 40px; height: 40px;
          background: rgba(255,255,255,0.92);
          border: 1px solid rgba(0,0,0,0.08);
          border-radius: 50%;
          box-shadow: 0 3px 10px rgba(0,0,0,0.15);
          display: flex; align-items: center; justify-content: center;
          cursor: pointer;
          color: var(--card-color, var(--lam-gold));
          transition: transform 0.2s, background 0.2s, box-shadow 0.2s;
          backdrop-filter: blur(4px);
        }
        .row-slider-btn:hover {
          transform: translateY(-50%) scale(1.08);
          background: #fff;
          box-shadow: 0 5px 14px rgba(0,0,0,0.22);
        }
        .row-slider-btn.left  { left: 4px; }
        .row-slider-btn.right { right: 4px; }

        /* ─── ORG CARD ───────────────────────────────────── */
        .org-card {
          width: 170px;
          text-align: center;
          background: var(--lam-bg-alt);
          border-radius: var(--radius);
          padding: 1.25rem 0.875rem;
          box-shadow: 0 4px 15px rgba(11,79,48,.08);
          position: relative;
          border-top: 4px solid var(--card-color, var(--lam-gold));
          transition: transform 0.2s;
          flex-shrink: 0;
        }
        .org-card:hover { transform: translateY(-4px); }
        .org-card__img {
          width: 72px; height: 72px; border-radius: 50%; object-fit: cover;
          margin: 0 auto 0.875rem; border: 3px solid var(--card-color, var(--lam-gold));
          display: block;
        }
        .org-card__placeholder {
          width: 72px; height: 72px; border-radius: 50%; background: var(--lam-cream);
          margin: 0 auto 0.875rem; border: 3px solid var(--card-color, var(--lam-gold));
          display: flex; align-items: center; justify-content: center;
        }
        .org-card-name {
          font-weight: 700; color: var(--lam-text); font-size: .9rem;
          margin-bottom: .2rem; line-height: 1.3;
        }
        .org-card-title { font-size: .75rem; font-weight: 600; line-height: 1.3; }

        /* ─── SCROLL HINT ────────────────────────────────── */
        .scroll-hint { display: none; }

        @media (max-width: 768px) {
          /* Kartu lebih compact di mobile */
          .org-card {
            width: 110px;
            padding: 0.75rem 0.375rem;
          }
          .org-card__img, .org-card__placeholder {
            width: 44px; height: 44px;
            margin-bottom: 0.4rem;
            border-width: 2px;
          }
          .org-card-name { font-size: .72rem; }
          .org-card-title { font-size: .65rem; }
          /* Kurangi padding antar card-wrapper di mobile */
          .org-card-wrapper { padding: 1.5rem 0.4rem 0; }
          /* Slider row lebih sempit di mobile */
          .org-row--slider {
            width: calc(100vw - 2.5rem);
            max-width: 100%;
          }
          .org-row--slider .org-row-inner {
            padding: 0.375rem 44px;
          }
          .row-slider-btn {
            width: 34px; height: 34px;
          }
          .row-slider-btn.left  { left: 2px; }
          .row-slider-btn.right { right: 2px; }
          /* org-chart lebih compact */
          .org-chart { padding: 0.5rem 0.5rem; }
          /* Vertical connectors lebih pendek */
          .org-tier + .org-tier > .org-tier-connector {
            height: 1.75rem;
          }
          .org-tier:not(:first-child) .org-row:not(.org-row--slider) .org-card-wrapper::before {
            height: 1.5rem;
          }
          .org-tier + .org-tier > .org-tier-connector,
          .org-tier:not(:first-child) .org-row--slider::before {
            height: 1.5rem;
          }
          .scroll-hint {
            display: flex !important; align-items: center; justify-content: center;
            gap: 0.5rem; font-size: 0.75rem; color: var(--lam-gold);
            background: rgba(249,149,34,0.1); padding: 0.4rem 1rem;
            border-radius: 20px; margin: 0 auto 1.5rem; width: fit-content;
          }
          .scroll-hint svg { animation: slide-hint 1.5s ease-in-out infinite; }
          @keyframes slide-hint {
            0%, 100% { transform: translateX(-3px); }
            50% { transform: translateX(3px); }
          }
        }

        /* ─── BIDANG-BIDANG GRID ─────────────────────────── */
        .bidang-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
          gap: 1.5rem;
          max-width: 1100px;
          margin: 0 auto;
        }

        /* ── Kartu ── */
        .kartu-bidang { position: relative; }
        .kartu-bidang__card {
          background: var(--lam-bg-alt);
          border-radius: var(--radius);
          border: 1px solid rgba(0,0,0,.1);
          border-top: 4px solid var(--lam-gold);
          box-shadow: 0 4px 18px rgba(0,0,0,.06);
          padding: 1.25rem;
          display: flex;
          flex-direction: column;
          gap: 1rem;
          height: 100%;
          transition: box-shadow 0.25s, transform 0.25s;
        }
        .kartu-bidang__card:hover {
          box-shadow: 0 10px 30px rgba(11,79,48,.13);
          transform: translateY(-3px);
        }
        .kartu-bidang__header {
          display: flex;
          align-items: flex-start;
          gap: 0.625rem;
          border-bottom: 1px solid rgba(11,79,48,.08);
          padding-bottom: 0.875rem;
        }
        .kartu-bidang__header-icon {
          flex-shrink: 0;
          width: 30px; height: 30px;
          background: rgba(11,79,48,.08);
          border-radius: 6px;
          display: flex; align-items: center; justify-content: center;
          color: var(--lam-green);
          margin-top: 1px;
        }
        .kartu-bidang__nama-bidang {
          font-family: var(--font-head);
          font-size: .97rem;
          font-weight: 700;
          color: var(--lam-green);
          line-height: 1.4;
          margin: 0;
        }
        .kartu-bidang__pimpinan {
          display: flex;
          align-items: center;
          gap: 0.75rem;
        }
        .kartu-bidang__avatar {
          width: 52px; height: 52px;
          border-radius: 50%;
          flex-shrink: 0;
          object-fit: cover;
          border: 2.5px solid var(--lam-green);
        }
        .kartu-bidang__avatar--inisial {
          background: linear-gradient(135deg, rgba(11,79,48,.12), rgba(11,79,48,.22));
          display: flex; align-items: center; justify-content: center;
          font-size: 1rem; font-weight: 700; color: var(--lam-green);
        }
        .kartu-bidang__pimpinan-info { flex: 1; min-width: 0; }
        .kartu-bidang__pimpinan-label {
          display: inline-block;
          font-size: .68rem; font-weight: 700;
          text-transform: uppercase; letter-spacing: .05em;
          color: var(--lam-green);
          background: rgba(11,79,48,.08);
          border-radius: 4px; padding: 1px 6px; margin-bottom: 3px;
        }
        .kartu-bidang__pimpinan-nama {
          font-weight: 700; font-size: .88rem; color: var(--lam-text);
          margin: 0 0 .15rem; line-height: 1.3;
          white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .kartu-bidang__pimpinan-jabatan {
          font-size: .75rem; color: var(--lam-text-l); margin: 0; line-height: 1.3;
        }
        .kartu-bidang__footer {
          display: flex; align-items: center; justify-content: space-between;
          gap: 0.5rem; margin-top: auto;
          padding-top: 0.75rem; border-top: 1px solid rgba(11,79,48,.07);
        }
        .kartu-bidang__badge {
          display: inline-flex; align-items: center; gap: 0.35rem;
          font-size: .75rem; font-weight: 600; color: var(--lam-text-l);
        }
        .kartu-bidang__badge--empty { font-style: italic; color: var(--lam-text-l); }
        .kartu-bidang__btn-lihat {
          display: inline-flex; align-items: center; gap: 0.35rem;
          font-size: .8rem; font-weight: 700; font-family: var(--font-body);
          color: var(--lam-black, #1a1a1a); background: var(--lam-gold); border: none;
          border-radius: 999px; padding: 0.4rem 0.9rem; cursor: pointer;
          transition: opacity 0.2s, transform 0.15s; white-space: nowrap;
        }
        .kartu-bidang__btn-lihat:hover { opacity: .88; transform: translateX(2px); }

        /* Backdrop */
        .kartu-bidang__backdrop {
          position: fixed; inset: 0;
          background: rgba(5,20,10,.6);
          backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);
          z-index: 9000;
        }
        /* Modal wrap */
        .kartu-bidang__modal-wrap {
          position: fixed; inset: 0; z-index: 9001;
          display: flex; align-items: center; justify-content: center;
          padding: 1rem;
        }
        /* Modal panel */
        .kartu-bidang__modal-panel {
          background: #fff; border-radius: 16px;
          box-shadow: 0 24px 64px rgba(0,0,0,.22), 0 4px 16px rgba(0,0,0,.1),
            inset 0 0 0 1px rgba(11,79,48,.12);
          width: 100%; max-width: 460px; max-height: 88vh;
          display: flex; flex-direction: column; overflow: hidden;
        }
        /* Modal header */
        .kartu-bidang__modal-header {
          display: flex; align-items: center; gap: 0.875rem;
          padding: 1.25rem 1.5rem;
          background: linear-gradient(135deg, rgba(11,79,48,.06) 0%, rgba(11,79,48,.02) 100%);
          border-bottom: 1px solid rgba(11,79,48,.1); flex-shrink: 0;
        }
        .kartu-bidang__modal-header-icon {
          width: 40px; height: 40px; border-radius: 10px;
          background: var(--lam-green);
          display: flex; align-items: center; justify-content: center;
          color: #fff; flex-shrink: 0;
        }
        .kartu-bidang__modal-header-text { flex: 1; min-width: 0; }
        .kartu-bidang__modal-eyebrow {
          font-size: .68rem; font-weight: 700; text-transform: uppercase;
          letter-spacing: .07em; color: var(--lam-green); opacity: .75;
          margin: 0 0 2px;
        }
        .kartu-bidang__modal-judul {
          font-family: var(--font-head); font-size: 1.05rem; font-weight: 700;
          color: var(--lam-text); margin: 0; line-height: 1.3;
        }
        .kartu-bidang__modal-close {
          flex-shrink: 0; width: 34px; height: 34px;
          background: rgba(0,0,0,.06); border: none; border-radius: 50%;
          display: flex; align-items: center; justify-content: center;
          cursor: pointer; color: var(--lam-text-l);
          transition: background 0.18s, color 0.18s;
        }
        .kartu-bidang__modal-close:hover {
          background: rgba(11,79,48,.12); color: var(--lam-green);
        }
        /* Pimpinan di modal */
        .kartu-bidang__modal-pimpinan {
          flex-shrink: 0; padding: 0.875rem 1.5rem;
          background: rgba(11,79,48,.03); border-bottom: 1px solid rgba(11,79,48,.08);
        }
        .kartu-bidang__modal-pimpinan-badge {
          display: inline-block; font-size: .65rem; font-weight: 700;
          text-transform: uppercase; letter-spacing: .06em;
          color: var(--lam-green); background: rgba(11,79,48,.1);
          border-radius: 4px; padding: 2px 7px; margin-bottom: 0.5rem;
        }
        .kartu-bidang__modal-pimpinan-row {
          display: flex; align-items: center; gap: 0.75rem;
        }
        .kartu-bidang__modal-pimpinan-avatar {
          width: 44px; height: 44px; border-radius: 50%;
          object-fit: cover; border: 2.5px solid var(--lam-green); flex-shrink: 0;
        }
        .kartu-bidang__modal-pimpinan-avatar--inisial {
          background: linear-gradient(135deg, rgba(11,79,48,.15), rgba(11,79,48,.25));
          display: flex; align-items: center; justify-content: center;
          font-size: .95rem; font-weight: 700; color: var(--lam-green);
        }
        .kartu-bidang__modal-pimpinan-nama {
          font-weight: 700; font-size: .9rem; color: var(--lam-text);
          margin: 0 0 2px; line-height: 1.3;
        }
        .kartu-bidang__modal-pimpinan-jabatan {
          font-size: .75rem; color: var(--lam-text-l); margin: 0;
        }
        /* Body list */
        .kartu-bidang__modal-body {
          flex: 1; overflow-y: auto; padding: 1rem 1.5rem 0.5rem;
          scrollbar-width: thin; scrollbar-color: rgba(11,79,48,.25) transparent;
        }
        .kartu-bidang__modal-body::-webkit-scrollbar { width: 5px; }
        .kartu-bidang__modal-body::-webkit-scrollbar-track { background: transparent; }
        .kartu-bidang__modal-body::-webkit-scrollbar-thumb {
          background: rgba(11,79,48,.25); border-radius: 10px;
        }
        .kartu-bidang__modal-section-label {
          font-size: .72rem; font-weight: 700; text-transform: uppercase;
          letter-spacing: .07em; color: var(--lam-text-l); margin: 0 0 0.625rem;
        }
        .kartu-bidang__anggota-list {
          list-style: none; margin: 0; padding: 0 0 0.75rem;
          display: flex; flex-direction: column; gap: 0.5rem;
        }
        .kartu-bidang__anggota-item {
          display: flex; align-items: center; gap: 0.75rem;
          padding: 0.55rem 0.75rem;
          background: #f8fbf9; border-radius: 10px;
          border: 1px solid rgba(11,79,48,.07);
          transition: background 0.15s, border-color 0.15s;
        }
        .kartu-bidang__anggota-item:hover {
          background: rgba(11,79,48,.05); border-color: rgba(11,79,48,.14);
        }
        .kartu-bidang__anggota-no {
          width: 22px; height: 22px; border-radius: 50%;
          background: rgba(11,79,48,.1); color: var(--lam-green);
          font-size: .68rem; font-weight: 700;
          display: flex; align-items: center; justify-content: center;
          flex-shrink: 0;
        }
        .kartu-bidang__anggota-avatar {
          width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
          object-fit: cover; border: 2px solid rgba(11,79,48,.15);
        }
        .kartu-bidang__anggota-avatar--inisial {
          background: linear-gradient(135deg, rgba(11,79,48,.1), rgba(11,79,48,.2));
          display: flex; align-items: center; justify-content: center;
          font-size: .78rem; font-weight: 700; color: var(--lam-green);
        }
        .kartu-bidang__anggota-info { flex: 1; min-width: 0; }
        .kartu-bidang__anggota-nama {
          font-weight: 600; font-size: .87rem; color: var(--lam-text);
          margin: 0 0 1px; line-height: 1.3;
          white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .kartu-bidang__anggota-jabatan {
          font-size: .72rem; color: var(--lam-text-l); margin: 0; line-height: 1.2;
        }
        /* Footer modal */
        .kartu-bidang__modal-footer {
          display: flex; align-items: center; justify-content: space-between;
          gap: 0.75rem; padding: 0.875rem 1.5rem;
          border-top: 1px solid rgba(11,79,48,.08);
          background: rgba(11,79,48,.02); flex-shrink: 0;
        }
        .kartu-bidang__modal-counter {
          display: inline-flex; align-items: center; gap: 0.35rem;
          font-size: .78rem; color: var(--lam-text-l); margin: 0;
        }
        .kartu-bidang__modal-btn-tutup {
          font-size: .83rem; font-weight: 700; font-family: var(--font-body);
          background: var(--lam-green); color: #fff; border: none;
          border-radius: 999px; padding: 0.45rem 1.25rem; cursor: pointer;
          transition: opacity 0.18s, transform 0.15s;
        }
        .kartu-bidang__modal-btn-tutup:hover { opacity: .86; transform: translateY(-1px); }
        [x-cloak] { display: none !important; }

        @media (max-width: 768px) {
          .bidang-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
          }
          .kartu-bidang__card { padding: 0.875rem; gap: 0.75rem; }
          .kartu-bidang__header { padding-bottom: 0.5rem; gap: 0.4rem; }
          .kartu-bidang__header-icon { width: 24px; height: 24px; border-radius: 4px; }
          .kartu-bidang__header-icon svg { width: 14px; height: 14px; }
          .kartu-bidang__nama-bidang { font-size: .85rem; line-height: 1.2; }
          .kartu-bidang__pimpinan { gap: 0.5rem; }
          .kartu-bidang__avatar { width: 40px; height: 40px; border-width: 2px; }
          .kartu-bidang__avatar--inisial { font-size: .9rem; }
          .kartu-bidang__pimpinan-label { font-size: .6rem; padding: 1px 4px; margin-bottom: 2px; }
          .kartu-bidang__pimpinan-nama { font-size: .8rem; }
          .kartu-bidang__pimpinan-jabatan { font-size: .65rem; }
          .kartu-bidang__footer { flex-direction: column; align-items: stretch; padding-top: 0.5rem; gap: 0.4rem; }
          .kartu-bidang__badge { font-size: .7rem; justify-content: center; }
          .kartu-bidang__btn-lihat { font-size: .75rem; padding: 0.35rem 0.5rem; justify-content: center; }
          
          .kartu-bidang__modal-panel { max-height: 90vh; border-radius: 14px; }
          .kartu-bidang__modal-header,
          .kartu-bidang__modal-footer,
          .kartu-bidang__modal-pimpinan { padding-left: 1.25rem; padding-right: 1.25rem; }
          .kartu-bidang__modal-body { padding: 0.875rem 1.25rem 0.5rem; }
        }

        @media (max-width: 640px) {
          .kartu-bidang__pimpinan { 
            flex-direction: column; 
            align-items: center; 
            text-align: center; 
          }
          .kartu-bidang__pimpinan-nama { 
            white-space: normal; /* biarkan wrap */
            text-align: center;
          }
          .kartu-bidang__pimpinan-jabatan { 
            text-align: center;
          }
          .kartu-bidang__btn-lihat { 
            width: 100%; 
            border-radius: 8px; 
          }
          .kartu-bidang__modal-panel { 
            max-height: 92vh; 
          }
          .kartu-bidang__modal-header,
          .kartu-bidang__modal-footer,
          .kartu-bidang__modal-pimpinan { 
            padding-left: 1rem; 
            padding-right: 1rem; 
          }
          .kartu-bidang__modal-body { 
            padding: 0.75rem 1rem 0.5rem; 
          }
        }
      </style>

      @php
        $hasManyMka = false;
        foreach($groupedMka as $anggotas) {
            if(count($anggotas) > 3) { $hasManyMka = true; break; }
        }
        $hasManyDph = false;
        foreach($groupedDph as $anggotas) {
            if(count($anggotas) > 3) { $hasManyDph = true; break; }
        }
      @endphp

      {{-- ── MKA ─────────────────────────────────────────────── --}}
      @if($strukturMka->count() > 0)
        <div style="margin-bottom:5rem;">
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:1.5rem;font-size:1.6rem;">
            Majelis Kerapatan Adat (MKA)
          </h3>
          @if($hasManyMka)
          <div class="scroll-hint">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 9l-4 3 4 3"/><path d="M16 9l4 3-4 3"/><path d="M4 12h16"/></svg>
            <span>Geser kanan/kiri untuk melihat lebih banyak</span>
          </div>
          @endif

          <div class="org-tree-outer">
            <div class="org-chart" style="--card-color: var(--lam-gold);">
              @foreach($groupedMka as $urutan => $anggotas)
                @php
                  $isAnggotaGroup = str_starts_with(strtolower($anggotas[0]->jabatan), 'anggota')
                    && !str_contains(strtolower($anggotas[0]->jabatan), 'ex officio');
                  $useSlider = !$isAnggotaGroup && count($anggotas) > 3;
                  $tierId = 'mka-tier-' . $urutan;
                @endphp
                <div class="org-tier">
                  <div class="org-tier-connector"></div>

                  @if($isAnggotaGroup)
                    {{-- Modal button for bulk "Anggota" --}}
                    <div x-data="{ openModal: false }" style="text-align:center; padding-top: {{ $loop->first ? '0' : '0.5rem' }};">
                      <button @click="openModal = true" type="button"
                        style="border:2px solid var(--lam-gold);color:var(--lam-gold);padding:0.6rem 1.5rem;border-radius:999px;background:transparent;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:0.5rem;font-family:var(--font-body);transition:all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Lihat {{ count($anggotas) }} Anggota</span>
                      </button>
                      <template x-teleport="body">
                        <div x-show="openModal" style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);" x-transition.opacity x-cloak>
                          <div @click.away="openModal = false" style="background:var(--lam-cream);width:95%;max-width:520px;max-height:90vh;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.2);display:flex;flex-direction:column;overflow:hidden;">
                            <div style="position:relative;padding:1.25rem 1.5rem;border-bottom:1px solid rgba(0,0,0,0.07);flex-shrink:0;text-align:center;">
                              <button @click="openModal = false" style="position:absolute;top:50%;right:1rem;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1.75rem;line-height:1;color:var(--lam-green);">&times;</button>
                              <h4 style="color:var(--lam-green);font-family:var(--font-head);margin:0;font-size:1.35rem;">Daftar Anggota MKA</h4>
                            </div>
                            <div style="padding:1rem;overflow-y:auto;flex:1;scrollbar-width:thin;">
                              <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:0.75rem;">
                                @foreach($anggotas as $anggota)
                                  <div style="background:var(--lam-bg-alt);border-radius:var(--radius);padding:0.875rem 0.5rem;text-align:center;border-top:3px solid var(--lam-gold);">
                                    @if($anggota->foto)
                                      <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" style="width:52px;height:52px;border-radius:50%;object-fit:cover;margin:0 auto 0.5rem;display:block;border:2px solid var(--lam-gold);">
                                    @else
                                      <div style="width:52px;height:52px;border-radius:50%;background:var(--lam-cream);border:2px solid var(--lam-gold);display:flex;align-items:center;justify-content:center;margin:0 auto 0.5rem;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="var(--lam-green)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                      </div>
                                    @endif
                                    <p style="font-weight:700;color:var(--lam-text);font-size:.82rem;margin-bottom:.2rem;line-height:1.2;">{{ $anggota->nama }}</p>
                                    <p style="font-size:.68rem;color:var(--lam-green);font-weight:600;line-height:1.2;">{{ $anggota->jabatan }}</p>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                      </template>
                    </div>

                  @elseif($useSlider)
                    {{-- Slider row for > 4 cards --}}
                    <div class="org-row org-row--slider" x-data="{
                        id: '{{ $tierId }}',
                        canLeft: false,
                        canRight: true,
                        scroll(dir) {
                          this.$refs.inner.scrollBy({ left: dir * 360, behavior: 'smooth' });
                        },
                        check() {
                          const el = this.$refs.inner;
                          this.canLeft  = el.scrollLeft > 0;
                          this.canRight = el.scrollWidth - el.clientWidth - el.scrollLeft > 1;
                        },
                        init() { this.$nextTick(() => this.check()); }
                    }">
                      <button x-show="canLeft" x-transition.opacity @click="scroll(-1)"
                        class="row-slider-btn left" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                      </button>

                      <div x-ref="inner" class="org-row-inner" @scroll.passive="check()" @resize.window="check()">
                        @foreach($anggotas as $anggota)
                          <div class="org-card-wrapper">
                            <div class="org-card">
                              @if($anggota->foto)
                                <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" class="org-card__img" loading="lazy">
                              @else
                                <div class="org-card__placeholder" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="var(--lam-green)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                              @endif
                              <p class="org-card-name">{{ $anggota->nama }}</p>
                              <p class="org-card-title" style="color:var(--lam-green);">{{ $anggota->jabatan }}</p>
                            </div>
                          </div>
                        @endforeach
                      </div>

                      <button x-show="canRight" x-transition.opacity @click="scroll(1)"
                        class="row-slider-btn right" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                      </button>
                    </div>

                  @else
                    {{-- Normal row ≤ 4 cards --}}
                    <div class="org-row" style="--count:{{ count($anggotas) }};">
                      @foreach($anggotas as $anggota)
                        <div class="org-card-wrapper">
                          <div class="org-card">
                            @if($anggota->foto)
                              <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" class="org-card__img" loading="lazy">
                            @else
                              <div class="org-card__placeholder" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="var(--lam-green)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                              </div>
                            @endif
                            <p class="org-card-name">{{ $anggota->nama }}</p>
                            <p class="org-card-title" style="color:var(--lam-green);">{{ $anggota->jabatan }}</p>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>{{-- end org-tier --}}
              @endforeach
            </div>{{-- end org-chart --}}
          </div>{{-- end org-tree-outer --}}
        </div>
      @endif

      {{-- ── DPH ─────────────────────────────────────────────── --}}
      @if($strukturDph->count() > 0)
        <div style="margin-bottom:4rem;">
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:1.5rem;font-size:1.6rem;">
            Dewan Pengurus Harian (DPH)
          </h3>
          @if($hasManyDph)
          <div class="scroll-hint">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 9l-4 3 4 3"/><path d="M16 9l4 3-4 3"/><path d="M4 12h16"/></svg>
            <span>Geser kanan/kiri untuk melihat lebih banyak</span>
          </div>
          @endif

          <div class="org-tree-outer">
            <div class="org-chart" style="--card-color: var(--lam-maroon);">
              @foreach($groupedDph as $urutan => $anggotas)
                @php
                  $useSlider = count($anggotas) > 3;
                  $tierId = 'dph-tier-' . $urutan;
                @endphp
                <div class="org-tier">
                  <div class="org-tier-connector"></div>

                  @if($useSlider)
                    {{-- Slider row --}}
                    <div class="org-row org-row--slider" x-data="{
                        canLeft: false,
                        canRight: true,
                        scroll(dir) {
                          this.$refs.inner.scrollBy({ left: dir * 360, behavior: 'smooth' });
                        },
                        check() {
                          const el = this.$refs.inner;
                          this.canLeft  = el.scrollLeft > 0;
                          this.canRight = el.scrollWidth - el.clientWidth - el.scrollLeft > 1;
                        },
                        init() { this.$nextTick(() => this.check()); }
                    }">
                      <button x-show="canLeft" x-transition.opacity @click="scroll(-1)"
                        class="row-slider-btn left" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                      </button>

                      <div x-ref="inner" class="org-row-inner" @scroll.passive="check()" @resize.window="check()">
                        @foreach($anggotas as $anggota)
                          <div class="org-card-wrapper">
                            <div class="org-card">
                              @if($anggota->foto)
                                <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" class="org-card__img" loading="lazy">
                              @else
                                <div class="org-card__placeholder" aria-hidden="true">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="var(--lam-maroon)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                              @endif
                              <p class="org-card-name">{{ $anggota->nama }}</p>
                              <p class="org-card-title" style="color:var(--lam-maroon);">{{ $anggota->jabatan }}</p>
                            </div>
                          </div>
                        @endforeach
                      </div>

                      <button x-show="canRight" x-transition.opacity @click="scroll(1)"
                        class="row-slider-btn right" style="display:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                      </button>
                    </div>

                  @else
                    {{-- Normal row --}}
                    <div class="org-row" style="--count:{{ count($anggotas) }};">
                      @foreach($anggotas as $anggota)
                        <div class="org-card-wrapper">
                          <div class="org-card">
                            @if($anggota->foto)
                              <img src="{{ Storage::url($anggota->foto) }}" alt="{{ $anggota->nama }}" class="org-card__img" loading="lazy">
                            @else
                              <div class="org-card__placeholder" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="var(--lam-maroon)" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                              </div>
                            @endif
                            <p class="org-card-name">{{ $anggota->nama }}</p>
                            <p class="org-card-title" style="color:var(--lam-maroon);">{{ $anggota->jabatan }}</p>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>{{-- end org-tier --}}
              @endforeach
            </div>{{-- end org-chart --}}
          </div>{{-- end org-tree-outer --}}
        </div>
      @endif

      {{-- ── Bidang-Bidang ─────────────────────────────────────── --}}
      @if(!empty($strukturBidang))
        <div style="margin-top:4rem;">
          <h3 style="font-family:var(--font-head);color:var(--lam-gold);text-align:center;margin-bottom:.5rem;font-size:1.6rem;text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
            Bidang-Bidang
          </h3>
          <p style="text-align:center;color:var(--lam-text-l);font-size:.875rem;margin-bottom:2rem;">Klik "Lihat Anggota" untuk melihat daftar lengkap anggota setiap bidang.</p>
          <div class="bidang-grid">
            @foreach($strukturBidang as $bidangData)
              <x-struktur.kartu-bidang
                :nama-bidang="$bidangData['nama']"
                :pimpinan="$bidangData['pimpinan']"
                :anggota-lain="$bidangData['anggota_lain']"
              />
            @endforeach
          </div>
        </div>
      @endif

      @if($strukturMka->count() === 0 && $strukturDph->count() === 0 && empty($strukturBidang))
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
  .prose-konten ol[type="a"] { list-style-type: lower-alpha; }
  .prose-konten ol[type="A"] { list-style-type: upper-alpha; }
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
  .left-arrow { left: 0; background: linear-gradient(to right, var(--lam-gold) 50%, transparent); }
  .right-arrow { right: 0; background: linear-gradient(to left, var(--lam-gold) 50%, transparent); }
  
  @media (min-width: 769px) {
    .nav-arrow { display: none !important; }
  }

  .radio-inputs {
    position: relative;
    display: flex;
    flex-wrap: nowrap;
    background-color: transparent;
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
    background-color: transparent;
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

    /* ── Expand / Collapse kartu timeline di mobile ── */
    .timeline-prose-wrap {
      position: relative;
    }
    .timeline-prose-wrap.is-collapsed {
      max-height: 90px;
      overflow: hidden;
    }
    .timeline-fade-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 52px;
      background: linear-gradient(to bottom, transparent, white);
      pointer-events: none;
    }
    .timeline-read-more {
      display: block;
      width: 100%;
      margin-top: 0.5rem;
      padding: 0.45rem 0.75rem;
      font-size: 0.8rem;
      font-weight: 700;
      font-family: var(--font-body);
      color: var(--lam-black);
      background: var(--lam-gold);
      border: none;
      border-radius: 999px;
      cursor: pointer;
      text-align: center;
      transition: opacity 0.2s, transform 0.15s;
    }
    .timeline-read-more:hover {
      opacity: 0.88;
      transform: translateY(-1px);
    }
  }

  /* Sembunyikan tombol di layar besar */
  @media screen and (min-width: 769px) {
    .timeline-read-more { display: none; }
    .timeline-fade-overlay { display: none; }
    .timeline-prose-wrap.is-collapsed { max-height: none; overflow: visible; }
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
