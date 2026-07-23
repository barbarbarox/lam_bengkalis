@extends('layouts.app')
@section('title', __('ui.pendidikan_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'pendidikan',
    'gradient' => '#004D40 0%, #002b24',
    'title'    => __('ui.pendidikan_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Pendidikan &amp; Pelatihan'],
    ]
])

<section class="section-pad">
  <div class="container">
    <p class="section-lead">{{ __('ui.pendidikan_desc') }}</p>

    <div class="filter-pills">
      <a href="{{ route('pendidikan.index') }}" class="filter-pill {{ !$jenis ? 'is-active' : '' }}">Semua</a>
      @foreach(\App\Models\PendidikanPelatihan::JENIS as $key => $label)
        <a href="{{ route('pendidikan.index', ['jenis' => $key]) }}"
           class="filter-pill {{ $jenis === $key ? 'is-active' : '' }}">{{ $label }}</a>
      @endforeach
    </div>

    @if($items->count())
      <div class="pendidikan-grid">
        @foreach($items as $prog)
          <a href="{{ route('pendidikan.show', $prog->slug) }}" class="pendidikan-card">
            <div class="pendidikan-card__img">
              @if($prog->thumbnail)
                <img src="{{ Storage::url($prog->thumbnail) }}" alt="{{ $prog->judul }}" loading="lazy">
              @else
                <div class="pendidikan-card__img-ph" style="background: linear-gradient(135deg,#004D40,#00695C);">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="1.2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
              @endif
              <span class="pendidikan-card__jenis-badge">{{ $prog->label_jenis }}</span>
            </div>
            <div class="pendidikan-card__body">
              <h3 class="pendidikan-card__title">{{ $prog->judul }}</h3>
              <div class="pendidikan-card__meta">
                @if($prog->tanggal_mulai)
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="16" y1="2" x2="16" y2="6"/></svg>
                    {{ $prog->tanggal_mulai->translatedFormat('d M Y') }}
                  </span>
                @endif
                @if($prog->lokasi)
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ Str::limit($prog->lokasi, 30) }}
                  </span>
                @endif
              </div>
              @if($prog->deskripsi)
                <p class="pendidikan-card__desc">{{ Str::limit($prog->deskripsi, 100) }}</p>
              @endif
              <div class="pendidikan-card__footer">
                <span class="pendidikan-card__biaya" style="{{ $prog->biaya_human === 'Gratis' ? 'color:#1B5E20;background:rgba(27,94,32,.1)' : 'color:#E65100;background:rgba(230,81,0,.1)' }}">
                  {{ $prog->biaya_human }}
                </span>
                @if($prog->kuota)
                  <span class="pendidikan-card__kuota">Kuota: {{ number_format($prog->kuota) }} peserta</span>
                @endif
              </div>
            </div>
          </a>
        @endforeach
      </div>
      <div class="pagination-wrap">{{ $items->links() }}</div>
    @else
      <div class="empty-state" style="padding:4rem 0;text-align:center;color:var(--lam-text-l);">Belum ada program yang tersedia.</div>
    @endif
  </div>
</section>

<style>
.pendidikan-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
.pendidikan-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); overflow: hidden; text-decoration: none; display: flex; flex-direction: column; transition: transform .2s, box-shadow .2s; }
.pendidikan-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(0,77,64,.15); }
.pendidikan-card__img { position: relative; height: 180px; overflow: hidden; }
.pendidikan-card__img img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.pendidikan-card:hover .pendidikan-card__img img { transform: scale(1.06); }
.pendidikan-card__img-ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.pendidikan-card__jenis-badge { position: absolute; top: .75rem; left: .75rem; background: rgba(0,0,0,.65); color: #fff; font-size: .7rem; font-weight: 700; padding: .25rem .6rem; border-radius: 4px; letter-spacing: .05em; }
.pendidikan-card__body { padding: 1.25rem; display: flex; flex-direction: column; gap: .5rem; flex: 1; }
.pendidikan-card__title { font-size: .95rem; font-weight: 700; color: var(--lam-text); line-height: 1.35; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.pendidikan-card:hover .pendidikan-card__title { color: #004D40; }
.pendidikan-card__meta { display: flex; flex-direction: column; gap: .25rem; font-size: .77rem; color: var(--lam-text-l); }
.pendidikan-card__meta span { display: flex; align-items: center; gap: .35rem; }
.pendidikan-card__desc { font-size: .82rem; color: var(--lam-text-m); line-height: 1.55; margin: 0; flex: 1; }
.pendidikan-card__footer { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .35rem; margin-top: auto; padding-top: .75rem; border-top: 1px solid var(--lam-border); }
.pendidikan-card__biaya { font-size: .78rem; font-weight: 700; padding: .25rem .65rem; border-radius: 4px; }
.pendidikan-card__kuota { font-size: .72rem; color: var(--lam-text-l); }
</style>
@include('public.partials._page-styles')
@endsection
