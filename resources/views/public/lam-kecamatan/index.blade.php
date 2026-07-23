@extends('layouts.app')
@section('title', __('ui.lam_kecamatan_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

{{-- Hero Mini ─────────────────────────────────────────────────────────────── --}}
@include('public.partials._hero-mini', [
    'halaman'  => 'lam-kecamatan',
    'gradient' => '#1B5E20 0%, #0a3d14',
    'title'    => __('ui.lam_kecamatan_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'LAM Kecamatan'],
    ]
])

{{-- Konten ─────────────────────────────────────────────────────────────────── --}}
<section class="section-pad">
  <div class="container">
    <p class="section-lead">{{ __('ui.lam_kecamatan_desc') }}</p>

    @if($kecamatans->count() > 0)
      <div class="kec-grid">
        @foreach($kecamatans as $kec)
          <div class="kec-card">
            {{-- Foto Ketua ── --}}
            <div class="kec-card__foto">
              @if($kec->foto_ketua_path)
                <img src="{{ Storage::url($kec->foto_ketua_path) }}" alt="Foto {{ $kec->nama_ketua }}" loading="lazy">
              @else
                <div class="kec-card__foto-ph">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="rgba(255,255,255,.5)" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                </div>
              @endif
            </div>

            {{-- Identitas ── --}}
            <div class="kec-card__body">
              <div class="kec-card__badge">Kecamatan {{ $kec->nama_kecamatan }}</div>
              @if($kec->nama_ketua)
                <p class="kec-card__ketua">{{ $kec->jabatan_ketua ?? 'Ketua LAM' }}: <strong>{{ $kec->nama_ketua }}</strong></p>
              @endif
              @if($kec->deskripsi)
                <p class="kec-card__desc">{{ Str::limit($kec->deskripsi, 120) }}</p>
              @endif

              <div class="kec-card__info">
                @if($kec->jumlah_nagori)
                  <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> {{ $kec->jumlah_nagori }} Desa/Kelurahan</span>
                @endif
                @if($kec->no_telp)
                  <a href="tel:{{ $kec->no_telp }}"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.69 9.74 19.79 19.79 0 011.63 5.11 2 2 0 013.6 2.87h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.91 10.5a16 16 0 006.29 6.29l.96-.96a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg> {{ $kec->no_telp }}</a>
                @endif
                @if($kec->email)
                  <a href="mailto:{{ $kec->email }}"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> {{ $kec->email }}</a>
                @endif
              </div>

              @if($kec->alamat)
                <p class="kec-card__alamat"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg> {{ $kec->alamat }}</p>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <p>Data LAM Kecamatan belum tersedia.</p>
      </div>
    @endif
  </div>
</section>

<style>
.hero-mini { padding: 3.5rem 0 2.5rem; }
.hero-mini__inner { display: flex; flex-direction: column; gap: .6rem; }
.hero-mini__title { font-size: clamp(1.6rem,3.5vw,2.4rem); color: #fff; font-weight: 700; margin: 0; }
.hero-mini__crumb { display: flex; gap: .5rem; font-size: .78rem; color: rgba(255,255,255,.65); }
.hero-mini__crumb a { color: var(--lam-gold); }

.section-lead { color: var(--lam-text-m); line-height: 1.8; font-size: .95rem; margin-bottom: 2.5rem; max-width: 700px; }

.kec-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}
.kec-card {
  background: var(--lam-bg-alt);
  border: 1px solid var(--lam-border);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--lam-shadow);
  transition: transform .2s, box-shadow .2s;
}
.kec-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.12); }

.kec-card__foto {
  height: 170px;
  background: linear-gradient(135deg, #1B5E20, #0a3d14);
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}
.kec-card__foto img { width: 100%; height: 100%; object-fit: cover; }
.kec-card__foto-ph { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }

.kec-card__body { padding: 1.25rem; }
.kec-card__badge {
  display: inline-block;
  background: rgba(27,94,32,.1);
  color: var(--lam-green);
  font-size: .72rem;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: .25rem .65rem;
  border-radius: 4px;
  margin-bottom: .65rem;
}
.kec-card__ketua { font-size: .88rem; color: var(--lam-text); margin: 0 0 .5rem; line-height: 1.4; }
.kec-card__desc { font-size: .82rem; color: var(--lam-text-l); line-height: 1.6; margin: 0 0 .75rem; }
.kec-card__info { display: flex; flex-direction: column; gap: .35rem; margin-bottom: .75rem; }
.kec-card__info span,
.kec-card__info a {
  display: flex; align-items: center; gap: .4rem;
  font-size: .78rem; color: var(--lam-text-m);
  text-decoration: none; transition: color .2s;
}
.kec-card__info a:hover { color: var(--lam-green); }
.kec-card__alamat { font-size: .78rem; color: var(--lam-text-l); display: flex; align-items: flex-start; gap: .4rem; margin: 0; line-height: 1.5; }

.empty-state { text-align: center; padding: 4rem 0; color: var(--lam-text-l); font-size: .95rem; }

@media (max-width: 640px) {
  .kec-grid { grid-template-columns: 1fr; }
  .kec-card__foto { height: 140px; }
}
</style>
@endsection
