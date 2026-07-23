@extends('layouts.app')
@section('title', $item->judul . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')
@include('public.partials._hero-mini', [
    'halaman'  => 'hukum-adat',
    'gradient' => '#BF360C 0%, #7f2000',
    'title'    => Str::limit($item->judul, 70),
    'crumbs'   => [['label'=>'Beranda','url'=>route('beranda')],['label'=>'Hukum Adat','url'=>route('hukum-adat.index')],['label'=>$item->label_jenis]],
])

<section class="section-pad">
  <div class="container">
    <div class="detail-layout">
      <main class="detail-layout__main">
        <div class="detail-card">
          <div class="detail-card__badges">
            <span class="badge" style="background:rgba(191,54,12,.1);color:#BF360C;">{{ $item->label_jenis }}</span>
            @if($item->tahun) <span class="badge badge--outline">{{ $item->tahun }}</span> @endif
          </div>
          <h2 style="font-size:1.4rem;margin:.5rem 0 1.5rem;line-height:1.4;color:var(--lam-text);">{{ $item->judul }}</h2>
          @if($item->nomor_dokumen)
            <p style="font-size:.83rem;color:var(--lam-text-l);margin-bottom:1rem;">No. Dokumen: <strong>{{ $item->nomor_dokumen }}</strong></p>
          @endif

          {{-- PDF Preview ── --}}
          @if($item->file_path)
            <div class="pdf-preview-wrap">
              <iframe src="{{ Storage::url($item->file_path) }}" title="{{ $item->judul }}" class="pdf-preview-iframe"></iframe>
            </div>
          @endif

          {{-- Konten HTML ── --}}
          @if($item->konten)
            <div class="prose" style="margin-top:1.5rem;">{!! $item->konten !!}</div>
          @endif
        </div>
      </main>

      <aside class="detail-layout__aside">
        <div class="info-box">
          <h3 class="info-box__title">Tindakan</h3>
          @if($item->file_path)
            <a href="{{ route('hukum-adat.unduh', $item) }}" class="btn-dl-block">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Unduh PDF
              @if($item->ukuran_file) <small>({{ $item->ukuran_human }})</small> @endif
            </a>
          @endif
          <p style="font-size:.75rem;color:var(--lam-text-l);margin-top:.75rem;">Telah diunduh {{ number_format($item->jumlah_unduh) }} kali.</p>
        </div>

        <div class="info-box" style="margin-top:1.25rem;">
          <h3 class="info-box__title">Info Dokumen</h3>
          <ul class="info-box__list">
            <li><span class="info-box__label">Jenis</span><span>{{ $item->label_jenis }}</span></li>
            @if($item->nomor_dokumen)<li><span class="info-box__label">Nomor</span><span>{{ $item->nomor_dokumen }}</span></li>@endif
            @if($item->tahun)<li><span class="info-box__label">Tahun</span><span>{{ $item->tahun }}</span></li>@endif
          </ul>
        </div>
        <a href="{{ route('hukum-adat.index') }}" class="btn-back">← Kembali ke Hukum Adat</a>
      </aside>
    </div>
  </div>
</section>

<style>
.pdf-preview-wrap { width: 100%; height: 520px; border: 1px solid var(--lam-border); border-radius: 8px; overflow: hidden; margin: 1rem 0; background: #eee; }
.pdf-preview-iframe { width: 100%; height: 100%; border: none; }
.btn-dl-block {
  display: flex; align-items: center; gap: .6rem; width: 100%;
  background: var(--lam-green); color: white; text-decoration: none;
  padding: .85rem 1rem; border-radius: 8px; font-weight: 700; font-size: .9rem;
  transition: background .2s;
}
.btn-dl-block:hover { background: var(--lam-gold); }
.btn-dl-block small { font-size: .72rem; opacity: .8; }
</style>
@include('public.partials._page-styles')
@endsection
