@extends('layouts.app')
@section('title', __('ui.gelar_adat_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'gelar-adat',
    'gradient' => '#E65100 0%, #9c3500',
    'title'    => __('ui.gelar_adat_title'),
    'crumbs'   => [['label'=>'Beranda','url'=>route('beranda')],['label'=>'Gelar & Kehormatan Adat']],
])

<section class="section-pad">
  <div class="container">
    <p class="section-lead">{{ __('ui.gelar_adat_desc') }}</p>

    <div class="filter-pills">
      <a href="{{ route('gelar-adat.index') }}" class="filter-pill {{ !$jenis ? 'is-active' : '' }}">Semua</a>
      @foreach(\App\Models\GelarAdat::JENIS as $key => $label)
        <a href="{{ route('gelar-adat.index', ['jenis' => $key]) }}"
           class="filter-pill {{ $jenis === $key ? 'is-active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @if($items->count())
      <div class="gelar-grid">
        @foreach($items as $gelar)
          <a href="{{ route('gelar-adat.show', $gelar->slug) }}" class="gelar-card">
            <div class="gelar-card__header">
              <div class="gelar-card__ornament">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="1.5" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
              </div>
              <span class="gelar-card__jenis">{{ $gelar->label_jenis }}</span>
            </div>
            <div class="gelar-card__body">
              <h3 class="gelar-card__nama">{{ $gelar->nama_gelar }}</h3>
              @if($gelar->tingkatan)
                <p class="gelar-card__tingkat">Tingkatan: {{ $gelar->tingkatan }}</p>
              @endif
              @if($gelar->deskripsi)
                <p class="gelar-card__desc">{{ Str::limit($gelar->deskripsi, 100) }}</p>
              @endif
              <span class="gelar-card__link">Selengkapnya →</span>
            </div>
          </a>
        @endforeach
      </div>
      <div class="pagination-wrap">{{ $items->links() }}</div>
    @else
      <div class="empty-state">Belum ada data gelar adat yang tersedia.</div>
    @endif
  </div>
</section>

<style>
.gelar-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.5rem; }
.gelar-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); overflow: hidden; text-decoration: none; display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; }
.gelar-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(230,81,0,.15); }
.gelar-card__header { background: linear-gradient(135deg, #E65100, #9c3500); padding: 1.25rem; display: flex; align-items: center; gap: .75rem; }
.gelar-card__ornament { width: 48px; height: 48px; border-radius: 10px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.gelar-card__jenis { color: rgba(255,255,255,.85); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; }
.gelar-card__body { padding: 1.25rem; display: flex; flex-direction: column; gap: .4rem; flex: 1; }
.gelar-card__nama { font-size: 1.05rem; font-weight: 800; color: var(--lam-text); margin: 0; font-family: var(--font-head); line-height: 1.3; }
.gelar-card:hover .gelar-card__nama { color: #E65100; }
.gelar-card__tingkat { font-size: .75rem; color: var(--lam-text-l); margin: 0; }
.gelar-card__desc { font-size: .82rem; color: var(--lam-text-m); line-height: 1.55; margin: .15rem 0 0; flex: 1; }
.gelar-card__link { font-size: .78rem; font-weight: 700; color: #E65100; margin-top: auto; padding-top: .75rem; border-top: 1px solid var(--lam-border); }
</style>
@include('public.partials._page-styles')
@endsection
