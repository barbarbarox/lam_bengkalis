@extends('layouts.app')
@section('title', $item->nama_lengkap . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')
@include('public.partials._hero-mini', [
    'halaman'  => 'tokoh-adat',
    'gradient' => '#4A148C 0%, #2a0a52',
    'title'    => 'Tokoh Adat',
    'crumbs'   => [['label'=>'Beranda','url'=>route('beranda')],['label'=>'Tokoh Adat','url'=>route('tokoh-adat.index')],['label'=>$item->nama]],
])

<section class="section-pad">
  <div class="container">
    <div class="detail-layout">
      <main class="detail-layout__main">
        <div class="detail-card">
          <div class="tokoh-detail-header">
            <div class="tokoh-detail-foto">
              @if($item->foto_path)
                <img src="{{ Storage::url($item->foto_path) }}" alt="{{ $item->nama }}">
              @else
                <div class="tokoh-detail-foto-ph">
                  <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" stroke="rgba(255,255,255,.4)" stroke-width="1" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M20 21a8 8 0 10-16 0"/></svg>
                </div>
              @endif
            </div>
            <div class="tokoh-detail-meta">
              @if($item->gelar_adat)
                <span class="tokoh-detail-gelar">{{ $item->gelar_adat }}</span>
              @endif
              <h1 class="tokoh-detail-nama">{{ $item->nama }}</h1>
              @if($item->jabatan)
                <p class="tokoh-detail-jabatan">{{ $item->jabatan }}</p>
              @endif
              <div class="tokoh-detail-chips">
                @if($item->kecamatan)
                  <span class="chip"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Kec. {{ $item->kecamatan }}</span>
                @endif
                @if($item->tahun_lahir)
                  <span class="chip">{{ $item->tahun_lahir }}{{ $item->tahun_wafat ? ' – ' . $item->tahun_wafat : ' – sekarang' }}</span>
                @endif
              </div>
            </div>
          </div>

          @if($item->biografi)
            <div class="prose" style="margin-top: 2rem;">{!! $item->biografi !!}</div>
          @elseif($item->ringkasan)
            <p class="section-lead" style="margin-top:2rem;">{{ $item->ringkasan }}</p>
          @endif
        </div>
      </main>

      <aside class="detail-layout__aside">
        @if($lainnya->count())
          <div class="info-box">
            <h3 class="info-box__title">Tokoh Lainnya</h3>
            @foreach($lainnya as $tk)
              <a href="{{ route('tokoh-adat.show', $tk->slug) }}" class="tokoh-mini-item">
                @if($tk->foto_path)
                  <img src="{{ Storage::url($tk->foto_path) }}" alt="{{ $tk->nama }}" class="tokoh-mini-foto">
                @else
                  <div class="tokoh-mini-foto tokoh-mini-foto-ph"></div>
                @endif
                <div>
                  <p class="tokoh-mini-nama">{{ $tk->nama }}</p>
                  @if($tk->jabatan) <p class="tokoh-mini-jabatan">{{ Str::limit($tk->jabatan, 35) }}</p> @endif
                </div>
              </a>
            @endforeach
          </div>
        @endif
        <a href="{{ route('tokoh-adat.index') }}" class="btn-back">← Kembali ke Tokoh Adat</a>
      </aside>
    </div>
  </div>
</section>

<style>
.tokoh-detail-header { display: flex; gap: 1.75rem; align-items: flex-start; }
.tokoh-detail-foto { width: 140px; height: 170px; border-radius: 10px; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg,#4A148C,#2a0a52); }
.tokoh-detail-foto img { width: 100%; height: 100%; object-fit: cover; object-position: top; }
.tokoh-detail-foto-ph { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.tokoh-detail-meta { flex: 1; }
.tokoh-detail-gelar { font-size: .75rem; font-weight: 700; color: #7B1FA2; text-transform: uppercase; letter-spacing: .1em; }
.tokoh-detail-nama { font-size: 1.5rem; font-weight: 800; color: var(--lam-text); margin: .25rem 0 .5rem; font-family: var(--font-head); }
.tokoh-detail-jabatan { font-size: .9rem; color: var(--lam-text-m); margin: 0 0 .75rem; }
.tokoh-detail-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
.chip { font-size: .72rem; background: rgba(74,20,140,.08); color: #4A148C; padding: .25rem .65rem; border-radius: 50px; display: flex; align-items: center; gap: .35rem; font-weight: 600; }
.tokoh-mini-item { display: flex; align-items: center; gap: .75rem; text-decoration: none; padding: .65rem 0; border-bottom: 1px solid var(--lam-border); }
.tokoh-mini-item:last-child { border-bottom: none; }
.tokoh-mini-foto { width: 44px; height: 50px; border-radius: 6px; object-fit: cover; object-position: top; flex-shrink: 0; }
.tokoh-mini-foto-ph { background: linear-gradient(135deg,#4A148C,#2a0a52); }
.tokoh-mini-nama { font-size: .85rem; font-weight: 700; color: var(--lam-text); margin: 0 0 .15rem; }
.tokoh-mini-nama:hover { color: #7B1FA2; }
.tokoh-mini-jabatan { font-size: .72rem; color: var(--lam-text-l); margin: 0; }
@media (max-width: 480px) { .tokoh-detail-header { flex-direction: column; } }
</style>
@include('public.partials._page-styles')
@endsection
