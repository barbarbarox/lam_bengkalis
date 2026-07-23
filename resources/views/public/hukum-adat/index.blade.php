@extends('layouts.app')
@section('title', __('ui.hukum_adat_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'hukum-adat',
    'gradient' => '#BF360C 0%, #7f2000',
    'title'    => __('ui.hukum_adat_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Hukum Adat'],
    ]
])

<section class="section-pad">
  <div class="container">
    <p class="section-lead">{{ __('ui.hukum_adat_desc') }}</p>

    {{-- Filter Jenis ─── --}}
    <div class="filter-pills">
      <a href="{{ route('hukum-adat.index') }}" class="filter-pill {{ !$jenis ? 'is-active' : '' }}">{{ __('ui.semua_jenis') }}</a>
      @foreach(\App\Models\HukumAdat::JENIS as $key => $label)
        <a href="{{ route('hukum-adat.index', ['jenis' => $key]) }}"
           class="filter-pill {{ $jenis === $key ? 'is-active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @if($items->count() > 0)
      <div class="dokumen-grid">
        @foreach($items as $item)
          <div class="dokumen-card">
            <div class="dokumen-card__icon" style="background: rgba(191,54,12,.12);">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="#BF360C" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div class="dokumen-card__body">
              <div class="dokumen-card__meta">
                <span class="badge" style="background:rgba(191,54,12,.1);color:#BF360C;">{{ $item->label_jenis }}</span>
                @if($item->tahun) <span class="dokumen-card__tahun">{{ $item->tahun }}</span> @endif
              </div>
              <h3 class="dokumen-card__title">{{ $item->judul }}</h3>
              @if($item->nomor_dokumen)
                <p class="dokumen-card__nomor">{{ $item->nomor_dokumen }}</p>
              @endif
              @if($item->ringkasan)
                <p class="dokumen-card__desc">{{ Str::limit($item->ringkasan, 100) }}</p>
              @endif
              <div class="dokumen-card__actions">
                <a href="{{ route('hukum-adat.show', $item->slug) }}" class="btn-action">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                  Lihat Dokumen
                </a>
                @if($item->file_path)
                  <a href="{{ route('hukum-adat.unduh', $item) }}" class="btn-action btn-action--dl" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh PDF @if($item->ukuran_file)<small>({{ $item->ukuran_human }})</small>@endif
                  </a>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="pagination-wrap">{{ $items->links() }}</div>
    @else
      <div class="empty-state">Belum ada data hukum adat yang tersedia.</div>
    @endif
  </div>
</section>

@include('public.partials._page-styles')
@endsection
