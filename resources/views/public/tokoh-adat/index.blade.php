@extends('layouts.app')
@section('title', __('ui.tokoh_adat_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'tokoh-adat',
    'gradient' => '#4A148C 0%, #2a0a52',
    'title'    => __('ui.tokoh_adat_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Tokoh Adat'],
    ]
])

<section class="section-pad">
  <div class="container">
    <p class="section-lead">{{ __('ui.tokoh_adat_desc') }}</p>

    {{-- Filter Kecamatan ── --}}
    @if($kecamatans->count())
      <div class="filter-pills" style="margin-bottom:2rem;">
        <a href="{{ route('tokoh-adat.index') }}" class="filter-pill {{ !$kecamatan ? 'is-active' : '' }}">Semua</a>
        @foreach($kecamatans as $kec)
          <a href="{{ route('tokoh-adat.index', ['kecamatan' => $kec]) }}"
             class="filter-pill {{ $kecamatan === $kec ? 'is-active' : '' }}">{{ $kec }}</a>
        @endforeach
      </div>
    @endif

    @if($items->count())
      <div class="tokoh-grid">
        @foreach($items as $tokoh)
          <a href="{{ route('tokoh-adat.show', $tokoh->slug) }}" class="tokoh-card">
            <div class="tokoh-card__foto">
              @if($tokoh->foto_path)
                <img src="{{ Storage::url($tokoh->foto_path) }}" alt="{{ $tokoh->nama }}" loading="lazy">
              @else
                <div class="tokoh-card__foto-ph">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="rgba(255,255,255,.4)" stroke-width="1.2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                </div>
              @endif
              @if(!$tokoh->masih_hidup)
                <div class="tokoh-card__wafat-badge">Almarhum/a</div>
              @endif
            </div>
            <div class="tokoh-card__body">
              @if($tokoh->gelar_adat)
                <p class="tokoh-card__gelar">{{ $tokoh->gelar_adat }}</p>
              @endif
              <h3 class="tokoh-card__nama">{{ $tokoh->nama }}</h3>
              @if($tokoh->jabatan)
                <p class="tokoh-card__jabatan">{{ $tokoh->jabatan }}</p>
              @endif
              @if($tokoh->kecamatan)
                <p class="tokoh-card__kec">
                  <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  Kec. {{ $tokoh->kecamatan }}
                </p>
              @endif
              @if($tokoh->ringkasan)
                <p class="tokoh-card__desc">{{ Str::limit($tokoh->ringkasan, 90) }}</p>
              @endif
            </div>
          </a>
        @endforeach
      </div>
      <div class="pagination-wrap">{{ $items->links() }}</div>
    @else
      <div class="empty-state">Belum ada data tokoh adat yang tersedia.</div>
    @endif
  </div>
</section>

<style>
.tokoh-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; }
.tokoh-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); overflow: hidden; text-decoration: none; display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; }
.tokoh-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(74,20,140,.15); }
.tokoh-card__foto { position: relative; height: 220px; background: linear-gradient(135deg, #4A148C, #2a0a52); overflow: hidden; }
.tokoh-card__foto img { width: 100%; height: 100%; object-fit: cover; object-position: top; transition: transform .3s; }
.tokoh-card:hover .tokoh-card__foto img { transform: scale(1.05); }
.tokoh-card__foto-ph { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
.tokoh-card__wafat-badge { position: absolute; bottom: .5rem; left: .5rem; background: rgba(0,0,0,.65); color: #fff; font-size: .65rem; font-weight: 700; padding: .2rem .5rem; border-radius: 4px; letter-spacing: .05em; }
.tokoh-card__body { padding: 1rem; display: flex; flex-direction: column; gap: .25rem; flex: 1; }
.tokoh-card__gelar { font-size: .72rem; color: #7B1FA2; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; margin: 0; }
.tokoh-card__nama { font-size: 1rem; font-weight: 700; color: var(--lam-text); margin: 0; line-height: 1.3; }
.tokoh-card:hover .tokoh-card__nama { color: #7B1FA2; }
.tokoh-card__jabatan { font-size: .78rem; color: var(--lam-text-m); margin: 0; }
.tokoh-card__kec { font-size: .72rem; color: var(--lam-text-l); display: flex; align-items: center; gap: .3rem; margin: .1rem 0 0; }
.tokoh-card__desc { font-size: .78rem; color: var(--lam-text-l); line-height: 1.5; margin: .35rem 0 0; }
@media (max-width: 480px) { .tokoh-grid { grid-template-columns: repeat(2, 1fr); } .tokoh-card__foto { height: 160px; } }
</style>
@include('public.partials._page-styles')
@endsection
