@extends('layouts.app')

@section('title', 'Profil Lembaga — ' . ($setting->nama_lembaga ?? 'LAM Bengkalis'))
@section('meta_description', $setting->meta_deskripsi ?? '')

@section('content')

{{-- Page Header --}}
<div style="background:var(--lam-green);padding:4rem 0 3rem;text-align:center;">
  <div class="container">
    <p style="font-size:.75rem;letter-spacing:.25em;text-transform:uppercase;color:var(--lam-gold);font-weight:600;margin-bottom:.75rem;">Tentang Kami</p>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.75rem,4vw,2.75rem);color:white;">Profil Lembaga Adat Melayu</h1>
    <p style="color:rgba(255,255,255,.7);margin-top:.75rem;">{{ $setting->nama_lembaga ?? 'LAM Bengkalis' }}</p>
  </div>
</div>

{{-- Root Alpine Component untuk Profil (Scrollspy) --}}
<div x-data="profileScrollspy()" @scroll.window="onScroll" style="position:relative;">

  {{-- Tab Navigation --}}
  <div style="background:var(--lam-green);position:sticky;top:64px;z-index:50;box-shadow:0 4px 15px rgba(0,0,0,0.1);">
    <div class="container" style="overflow-x:auto;scrollbar-width:none;-ms-overflow-style:none;">
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
  </div>

  <div class="section-pad" style="background:var(--lam-cream);min-height:60vh;padding-top:3rem;">
    <div class="container">

      {{-- Sejarah --}}
      <div id="sejarah" class="scroll-section">
        @if(!empty($sejarahTimeline) && count($sejarahTimeline) > 0)
          <div class="section-heading" style="text-align:center;margin-bottom:3rem;">
            <span class="section-heading__eyebrow">Latar Belakang</span>
            <h2 class="section-heading__title">Sejarah LAM Bengkalis</h2>
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
        .org-chart {
          display: flex;
          flex-direction: column;
          align-items: center;
          padding-top: 1rem;
        }
        .org-group {
          display: flex;
          flex-wrap: wrap;
          justify-content: center;
          position: relative;
          width: 100%;
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
          margin-bottom: 1rem;
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
          width: 200px;
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
        
        @media (max-width: 768px) {
          .org-group + .org-group::before,
          .org-card-wrapper::before,
          .org-card-wrapper::after {
            display: none !important;
          }
          .org-card-wrapper {
            padding-top: 0 !important;
          }
          .org-group + .org-group {
            margin-top: 1rem;
          }
        }
      </style>

      @if($strukturMka->count() > 0)
        <div style="margin-bottom:5rem;">
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:2.5rem;font-size:1.6rem;">
            Majelis Kerapatan Adat (MKA)
          </h3>
          <div class="org-chart" style="--card-color: var(--lam-gold);">
            @foreach($groupedMka as $urutan => $anggotas)
              <div class="org-group">
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
                    <p style="font-weight:700;color:var(--lam-text);font-size:.95rem;margin-bottom:.25rem;">{{ $anggota->nama }}</p>
                    <p style="font-size:.8rem;color:var(--lam-green);font-weight:600;">{{ $anggota->jabatan }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      @endif

      @if($strukturDph->count() > 0)
        <div>
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:2.5rem;font-size:1.6rem;">
            Dewan Pengurus Harian (DPH)
          </h3>
          <div class="org-chart" style="--card-color: var(--lam-maroon);">
            @foreach($groupedDph as $urutan => $anggotas)
              <div class="org-group">
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
                    <p style="font-weight:700;color:var(--lam-text);font-size:.95rem;margin-bottom:.25rem;">{{ $anggota->nama }}</p>
                    <p style="font-size:.8rem;color:var(--lam-maroon);font-weight:600;">{{ $anggota->jabatan }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @endforeach
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
  .radio-inputs {
    position: relative;
    display: flex;
    background-color: var(--lam-green);
    font-size: 14px;
    width: 100%;
    padding: 0.5rem 1.5rem 0 1.5rem;
    justify-content: center;
  }
  .radio-inputs .radio { margin: 0; }
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

  /* Timeline Styles */
  .timeline-container {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
  }
  .timeline-container::after {
    content: '';
    position: absolute;
    width: 4px;
    background-color: var(--lam-gold);
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -2px;
  }
  .timeline-item {
    padding: 10px 40px;
    position: relative;
    background-color: inherit;
    width: 50%;
    opacity: 0;
    transform: translateY(40px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
  }
  .timeline-item.show {
    opacity: 1;
    transform: translateY(0);
  }
  .timeline-left { left: 0; }
  .timeline-right { left: 50%; }
  .timeline-item::after {
    content: '';
    position: absolute;
    width: 24px;
    height: 24px;
    right: -12px;
    background-color: white;
    border: 4px solid var(--lam-gold);
    top: 24px;
    border-radius: 50%;
    z-index: 1;
  }
  .timeline-right::after { left: -12px; }
  .timeline-content {
    padding: 1.5rem;
    background-color: white;
    border: 2px solid var(--lam-gold);
    position: relative;
    border-radius: var(--radius);
    box-shadow: var(--lam-shadow);
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
  .timeline-img {
    width: 100%;
    max-height: 250px;
    object-fit: cover;
    border-radius: var(--radius-sm);
    margin-bottom: 1.25rem;
  }
  @media screen and (max-width: 768px) {
    .timeline-container::after { left: 24px; }
    .timeline-item { width: 100%; padding-left: 70px; padding-right: 0px; }
    .timeline-right { left: 0; }
    .timeline-left::after, .timeline-right::after { left: 12px; }
    .radio-inputs { justify-content: flex-start; padding: 0.5rem 1rem 0; }
  }
</style>

@endsection

@push('body_scripts')
<script>
  function profileScrollspy() {
    return {
      activeTab: 'sejarah',
      isScrolling: false,
      sections: ['sejarah', 'visi-misi', 'tugas-fungsi', 'dasar-hukum', 'struktur'],
      
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
