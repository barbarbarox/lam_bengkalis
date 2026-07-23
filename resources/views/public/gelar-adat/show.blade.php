@extends('layouts.app')
@section('title', $item->nama_gelar . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')
@include('public.partials._hero-mini', [
    'halaman'  => 'gelar-adat',
    'gradient' => '#E65100 0%, #9c3500',
    'title'    => 'Gelar & Kehormatan Adat',
    'crumbs'   => [['label'=>'Beranda','url'=>route('beranda')],['label'=>'Gelar Adat','url'=>route('gelar-adat.index')],['label'=>$item->nama_gelar]],
])

<section class="section-pad">
  <div class="container">
    <div class="detail-layout">
      <main class="detail-layout__main">
        <div class="detail-card">
          <div class="detail-card__badges">
            <span class="badge" style="background:rgba(230,81,0,.1);color:#E65100;">{{ $item->label_jenis }}</span>
            @if($item->tingkatan)<span class="badge badge--outline">{{ $item->tingkatan }}</span>@endif
          </div>
          <h2 style="font-size:1.6rem;margin:.5rem 0 1.5rem;color:var(--lam-text);font-family:var(--font-head);">{{ $item->nama_gelar }}</h2>

          @if($item->makna)
            <h3 style="font-size:1rem;font-weight:700;color:var(--lam-green);margin:0 0 .75rem;">{{ __('ui.makna_gelar') }}</h3>
            <div class="prose">{!! $item->makna !!}</div>
          @endif

          @if($item->syarat_pemberian)
            <h3 style="font-size:1rem;font-weight:700;color:var(--lam-green);margin:1.5rem 0 .75rem;">{{ __('ui.syarat_pemberian') }}</h3>
            <div class="prose">{!! $item->syarat_pemberian !!}</div>
          @endif

          @if($item->penerima_terkini)
            <div style="background:rgba(230,81,0,.06);border-radius:8px;padding:1rem 1.25rem;margin-top:1.5rem;">
              <p style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#E65100;margin:0 0 .35rem;">{{ __('ui.penerima_terkini') }}</p>
              <p style="font-size:.9rem;color:var(--lam-text);margin:0;">{{ $item->penerima_terkini }}</p>
            </div>
          @endif
        </div>
      </main>

      <aside class="detail-layout__aside">
        @if($lainnya->count())
          <div class="info-box">
            <h3 class="info-box__title">Gelar Lainnya</h3>
            @foreach($lainnya as $gl)
              <a href="{{ route('gelar-adat.show', $gl->slug) }}"
                 style="display:block;text-decoration:none;padding:.65rem 0;border-bottom:1px solid var(--lam-border);">
                <span class="badge" style="background:rgba(230,81,0,.08);color:#E65100;margin-bottom:.25rem;display:inline-block;font-size:.65rem;">{{ $gl->label_jenis }}</span>
                <p style="font-size:.88rem;font-weight:700;color:var(--lam-text);margin:0;line-height:1.35;">{{ $gl->nama_gelar }}</p>
                @if($gl->tingkatan)<p style="font-size:.72rem;color:var(--lam-text-l);margin:.15rem 0 0;">{{ $gl->tingkatan }}</p>@endif
              </a>
            @endforeach
          </div>
        @endif
        <a href="{{ route('gelar-adat.index') }}" class="btn-back">← Kembali ke Gelar Adat</a>
      </aside>
    </div>
  </div>
</section>
@include('public.partials._page-styles')
@endsection
