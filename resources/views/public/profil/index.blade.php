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

{{-- Tab Navigation (Alpine) --}}
<div x-data="{ activeTab: 'sejarah' }" style="background:white;border-bottom:1px solid var(--lam-border);position:sticky;top:64px;z-index:50;">
  <div class="container" style="overflow-x:auto;">
    <nav style="display:flex;gap:0;min-width:max-content;" role="tablist" aria-label="Konten Profil">
      @foreach([
        ['id'=>'sejarah',      'label'=>'Sejarah'],
        ['id'=>'visi-misi',    'label'=>'Visi & Misi'],
        ['id'=>'tugas-fungsi', 'label'=>'Tugas & Fungsi'],
        ['id'=>'dasar-hukum',  'label'=>'Dasar Hukum'],
        ['id'=>'struktur',     'label'=>'Struktur Organisasi'],
      ] as $tab)
        <button
          @click="activeTab = '{{ $tab['id'] }}'"
          :class="activeTab === '{{ $tab['id'] }}' ? 'is-active' : ''"
          style="padding:.9rem 1.25rem;background:none;border:none;border-bottom:3px solid transparent;
                 font-family:var(--font-body);font-size:.875rem;font-weight:500;cursor:pointer;
                 color:var(--lam-text-l);white-space:nowrap;transition:color .2s,border-color .2s;"
          :style="activeTab === '{{ $tab['id'] }}' ? 'color:var(--lam-green);border-bottom-color:var(--lam-gold);' : ''"
          role="tab" :aria-selected="activeTab === '{{ $tab['id'] }}'"
          :id="'tab-{{ $tab['id'] }}'" :aria-controls="'panel-{{ $tab['id'] }}'">
          {{ $tab['label'] }}
        </button>
      @endforeach
    </nav>
  </div>
</div>

<div x-data="{ activeTab: 'sejarah' }" class="section-pad" style="background:var(--lam-cream);">
  <div class="container">

    {{-- Sejarah --}}
    <div x-show="activeTab === 'sejarah'" role="tabpanel" id="panel-sejarah" aria-labelledby="tab-sejarah">
      @if($konten->get('sejarah-lam'))
        <div style="max-width:820px;margin:0 auto;">
          <div class="section-heading" style="text-align:left;margin-bottom:2rem;">
            <span class="section-heading__eyebrow">Latar Belakang</span>
            <h2 class="section-heading__title">{{ $konten->get('sejarah-lam')?->judul ?? 'Sejarah LAM Bengkalis' }}</h2>
            <div class="section-heading__divider" style="justify-content:flex-start;"><span></span><i></i><span></span></div>
          </div>
          <div class="prose-konten">{!! $konten->get('sejarah-lam')?->konten !!}</div>
        </div>
      @else
        <p style="text-align:center;color:var(--lam-text-l);padding:3rem 0;">Konten belum tersedia.</p>
      @endif
    </div>

    {{-- Visi & Misi --}}
    <div x-show="activeTab === 'visi-misi'" role="tabpanel" id="panel-visi-misi" aria-labelledby="tab-visi-misi">
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
                         background:white;border-radius:var(--radius-sm);padding:1rem 1.25rem;
                         box-shadow:0 2px 8px rgba(11,79,48,.07);border-left:4px solid var(--lam-gold);">
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
    <div x-show="activeTab === 'tugas-fungsi'" role="tabpanel" id="panel-tugas-fungsi" aria-labelledby="tab-tugas-fungsi">
      @if($konten->get('tugas-fungsi'))
        <div style="max-width:820px;margin:0 auto;">
          <h2 style="font-family:var(--font-head);color:var(--lam-green);margin-bottom:1.5rem;">Tugas dan Fungsi</h2>
          <div class="prose-konten">{!! $konten->get('tugas-fungsi')?->konten !!}</div>
        </div>
      @else
        <p style="text-align:center;color:var(--lam-text-l);padding:3rem 0;">Konten belum tersedia.</p>
      @endif
    </div>

    {{-- Dasar Hukum --}}
    <div x-show="activeTab === 'dasar-hukum'" role="tabpanel" id="panel-dasar-hukum" aria-labelledby="tab-dasar-hukum">
      @if($konten->get('dasar-hukum'))
        <div style="max-width:820px;margin:0 auto;">
          <h2 style="font-family:var(--font-head);color:var(--lam-green);margin-bottom:1.5rem;">Dasar Hukum</h2>
          <div class="prose-konten">{!! $konten->get('dasar-hukum')?->konten !!}</div>
        </div>
      @else
        <p style="text-align:center;color:var(--lam-text-l);padding:3rem 0;">Konten belum tersedia.</p>
      @endif
    </div>

    {{-- Struktur Organisasi --}}
    <div x-show="activeTab === 'struktur'" role="tabpanel" id="panel-struktur" aria-labelledby="tab-struktur">
      @php
        // Helper untuk grouping berdasarkan jabatan dengan mempertahankan urutan
        function groupByJabatan($koleksi) {
            $groups = [];
            foreach($koleksi as $anggota) {
                $groups[$anggota->jabatan][] = $anggota;
            }
            return $groups;
        }
        $groupedMka = groupByJabatan($strukturMka);
        $groupedDph = groupByJabatan($strukturDph);
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
          gap: 2rem;
          position: relative;
          width: 100%;
        }
        .org-group + .org-group {
          margin-top: 3.5rem;
        }
        /* Vertical connecting line */
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
        .org-card {
          width: 200px;
          text-align: center;
          background: white;
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
      </style>

      @if($strukturMka->count() > 0)
        <div style="margin-bottom:5rem;">
          <h3 style="font-family:var(--font-head);color:var(--lam-green);text-align:center;margin-bottom:2.5rem;font-size:1.6rem;">
            Majelis Kerapatan Adat (MKA)
          </h3>
          <div class="org-chart" style="--card-color: var(--lam-gold);">
            @foreach($groupedMka as $jabatan => $anggotas)
              <div class="org-group">
                @foreach($anggotas as $anggota)
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
            @foreach($groupedDph as $jabatan => $anggotas)
              <div class="org-group">
                @foreach($anggotas as $anggota)
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
</div>

<style>
  .prose-konten { color: var(--lam-text-m); line-height: 1.85; }
  .prose-konten p { margin-bottom: 1rem; }
  .prose-konten h2, .prose-konten h3 { color: var(--lam-green); margin: 1.5rem 0 .75rem; }
  .prose-konten ul, .prose-konten ol { margin-left: 1.5rem; margin-bottom: 1rem; }
  .prose-konten li { margin-bottom: .4rem; }
  .prose-konten blockquote { border-left: 4px solid var(--lam-gold); padding-left: 1rem; color: var(--lam-text-l); font-style: italic; }
  .prose-konten a { color: var(--lam-green); text-decoration: underline; }
</style>

@endsection
