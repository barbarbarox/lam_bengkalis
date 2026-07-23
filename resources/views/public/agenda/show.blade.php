@extends('layouts.app')
@section('title', $item->judul . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'agenda',
    'gradient' => '#1565C0 0%, #0d47a1',
    'title'    => Str::limit($item->judul, 80),
    'crumbs'   => [['label'=>'Beranda','url'=>route('beranda')],['label'=>'Agenda','url'=>route('agenda.index')],['label'=>Str::limit($item->judul,40)]],
])

<section class="section-pad">
  <div class="container">
    <div class="detail-layout">
      {{-- Konten Utama ─────────────────────────────────────────────────────── --}}
      <main class="detail-layout__main">
        <div class="detail-card">
          {{-- Status + Jenis ── --}}
          <div class="detail-card__badges">
            <span class="badge" style="background:{{ $item->warna_badge }};color:white;">{{ $item->label_status }}</span>
            <span class="badge badge--outline">{{ $item->label_jenis }}</span>
          </div>

          {{-- Thumbnail ── --}}
          @if($item->thumbnail)
            <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->judul }}"
                 class="detail-card__banner" loading="lazy">
          @endif

          {{-- Konten HTML ── --}}
          @if($item->konten)
            <div class="prose">{!! $item->konten !!}</div>
          @elseif($item->deskripsi)
            <p class="section-lead">{{ $item->deskripsi }}</p>
          @endif
        </div>
      </main>

      {{-- Sidebar ─────────────────────────────────────────────────────────── --}}
      <aside class="detail-layout__aside">
        {{-- Info Box ── --}}
        <div class="info-box">
          <h3 class="info-box__title">Informasi Kegiatan</h3>
          <ul class="info-box__list">
            <li>
              <span class="info-box__label">Tanggal</span>
              <span>{{ $item->rentang_tanggal }}</span>
            </li>
            @if($item->waktu_mulai)
              <li>
                <span class="info-box__label">Waktu</span>
                <span>{{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }}
                  @if($item->waktu_selesai) – {{ \Carbon\Carbon::parse($item->waktu_selesai)->format('H:i') }} @endif WIB</span>
              </li>
            @endif
            @if($item->lokasi)
              <li>
                <span class="info-box__label">Lokasi</span>
                <span>{{ $item->lokasi }}</span>
              </li>
            @endif
            @if($item->penyelenggara)
              <li>
                <span class="info-box__label">Penyelenggara</span>
                <span>{{ $item->penyelenggara }}</span>
              </li>
            @endif
            @if($item->kuota)
              <li>
                <span class="info-box__label">Kuota</span>
                <span>{{ number_format($item->kuota) }} peserta</span>
              </li>
            @endif
          </ul>
        </div>

        {{-- Agenda Lainnya ── --}}
        @if($lainnya->count())
          <div class="info-box" style="margin-top:1.5rem;">
            <h3 class="info-box__title">Agenda Lainnya</h3>
            <div class="agenda-mini-list">
              @foreach($lainnya as $ag)
                <a href="{{ route('agenda.show', $ag->slug) }}" class="agenda-mini-item">
                  <div class="agenda-mini-item__date">
                    <span>{{ $ag->tanggal_mulai->format('d') }}</span>
                    <span>{{ strtoupper($ag->tanggal_mulai->translatedFormat('M')) }}</span>
                  </div>
                  <div class="agenda-mini-item__title">{{ Str::limit($ag->judul, 50) }}</div>
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <a href="{{ route('agenda.index') }}" class="btn-back">
          ← Kembali ke Agenda
        </a>
      </aside>
    </div>
  </div>
</section>

<style>
.detail-layout { display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start; }
.detail-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); padding: 2rem; }
.detail-card__badges { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1rem; }
.detail-card__banner { width: 100%; height: 280px; object-fit: cover; border-radius: 8px; margin-bottom: 1.5rem; }
.prose { line-height: 1.9; color: var(--lam-text); font-size: .95rem; }
.prose h2, .prose h3 { margin: 1.5rem 0 .75rem; color: var(--lam-text); font-family: var(--font-head); }
.prose p { margin-bottom: 1rem; }
.prose ul, .prose ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.prose li { margin-bottom: .35rem; }

.info-box { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); padding: 1.5rem; }
.info-box__title { font-size: 1rem; font-weight: 700; color: var(--lam-text); margin: 0 0 1rem; padding-bottom: .75rem; border-bottom: 2px solid var(--lam-green); }
.info-box__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: .85rem; }
.info-box__list li { display: flex; flex-direction: column; gap: .2rem; font-size: .85rem; }
.info-box__label { font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--lam-text-l); }
.info-box__list li span:last-child { color: var(--lam-text); }

.agenda-mini-list { display: flex; flex-direction: column; gap: .75rem; }
.agenda-mini-item { display: flex; align-items: center; gap: .75rem; text-decoration: none; transition: color .2s; }
.agenda-mini-item:hover .agenda-mini-item__title { color: var(--lam-green); }
.agenda-mini-item__date { display: flex; flex-direction: column; align-items: center; background: var(--lam-green); color: white; border-radius: 8px; padding: .35rem .55rem; font-size: .72rem; font-weight: 700; flex-shrink: 0; min-width: 40px; text-align: center; }
.agenda-mini-item__date span:first-child { font-size: 1.1rem; line-height: 1; }
.agenda-mini-item__title { font-size: .82rem; color: var(--lam-text-m); line-height: 1.4; }

.btn-back { display: inline-block; margin-top: 1.5rem; font-size: .85rem; color: var(--lam-green); text-decoration: none; font-weight: 600; transition: color .2s; }
.btn-back:hover { color: var(--lam-gold); }

@media (max-width: 768px) {
  .detail-layout { grid-template-columns: 1fr; }
  .detail-layout__aside { order: -1; }
  .detail-card { padding: 1.25rem; }
}
</style>
@endsection
