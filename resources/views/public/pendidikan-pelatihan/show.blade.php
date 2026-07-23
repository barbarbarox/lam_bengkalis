@extends('layouts.app')
@section('title', $item->judul . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')
@include('public.partials._hero-mini', [
    'halaman'  => 'pendidikan',
    'gradient' => '#004D40 0%, #002b24',
    'title'    => Str::limit($item->judul, 70),
    'crumbs'   => [['label'=>'Beranda','url'=>route('beranda')],['label'=>'Pendidikan & Pelatihan','url'=>route('pendidikan.index')],['label'=>$item->label_jenis]],
])

<section class="section-pad">
  <div class="container">
    <div class="detail-layout">
      <main class="detail-layout__main">
        <div class="detail-card">
          <div class="detail-card__badges">
            <span class="badge" style="background:rgba(0,77,64,.1);color:#004D40;">{{ $item->label_jenis }}</span>
            <span class="badge" style="background:{{ $item->biaya_human === 'Gratis' ? 'rgba(27,94,32,.1);color:#1B5E20' : 'rgba(230,81,0,.1);color:#E65100' }}">{{ $item->biaya_human }}</span>
          </div>
          @if($item->thumbnail)
            <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->judul }}" class="detail-card__banner" loading="lazy">
          @endif
          @if($item->konten)
            <div class="prose">{!! $item->konten !!}</div>
          @elseif($item->deskripsi)
            <p class="section-lead">{{ $item->deskripsi }}</p>
          @endif
        </div>
      </main>

      <aside class="detail-layout__aside">
        <div class="info-box">
          <h3 class="info-box__title">Detail Program</h3>
          <ul class="info-box__list">
            <li><span class="info-box__label">Jenis</span><span>{{ $item->label_jenis }}</span></li>
            @if($item->penyelenggara)<li><span class="info-box__label">Penyelenggara</span><span>{{ $item->penyelenggara }}</span></li>@endif
            @if($item->tanggal_mulai)<li><span class="info-box__label">Mulai</span><span>{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</span></li>@endif
            @if($item->tanggal_selesai)<li><span class="info-box__label">Selesai</span><span>{{ $item->tanggal_selesai->translatedFormat('d M Y') }}</span></li>@endif
            @if($item->lokasi)<li><span class="info-box__label">Lokasi</span><span>{{ $item->lokasi }}</span></li>@endif
            @if($item->kuota)<li><span class="info-box__label">Kuota</span><span>{{ number_format($item->kuota) }} peserta</span></li>@endif
            <li><span class="info-box__label">Biaya</span><span style="font-weight:700;color:#004D40;">{{ $item->biaya_human }}</span></li>
          </ul>
          @if($item->link_pendaftaran)
            <a href="{{ $item->link_pendaftaran }}" target="_blank" rel="noopener"
               class="btn-kirim" style="margin-top:1.25rem;width:100%;justify-content:center;background:#004D40;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
              Daftar Sekarang
            </a>
          @endif
        </div>

        @if($lainnya->count())
          <div class="info-box" style="margin-top:1.25rem;">
            <h3 class="info-box__title">Program Lainnya</h3>
            @foreach($lainnya as $prog)
              <a href="{{ route('pendidikan.show', $prog->slug) }}" style="display:block;text-decoration:none;padding:.65rem 0;border-bottom:1px solid var(--lam-border);">
                <span class="badge" style="background:rgba(0,77,64,.08);color:#004D40;margin-bottom:.3rem;display:inline-block;">{{ $prog->label_jenis }}</span>
                <p style="font-size:.85rem;font-weight:600;color:var(--lam-text);margin:0;line-height:1.35;">{{ Str::limit($prog->judul, 55) }}</p>
              </a>
            @endforeach
          </div>
        @endif
        <a href="{{ route('pendidikan.index') }}" class="btn-back">← Kembali ke Pendidikan & Pelatihan</a>
      </aside>
    </div>
  </div>
</section>
@include('public.partials._page-styles')
@endsection
