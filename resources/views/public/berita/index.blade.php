@extends('layouts.app')

@section('title', 'Berita — ' . ($setting->nama_lembaga ?? 'LAM Bengkalis'))

@section('content')

{{-- Page Header --}}
@php $heroBg = $setting->heroUrl('berita'); @endphp
<div class="page-hero" style="{{ $heroBg ? 'background-image:url('.$heroBg.')' : '' }}">
  <div class="page-hero__overlay"></div>
  @if($heroBg)<div class="page-hero__gold-edge"></div>@endif
  <div class="container" style="position:relative;z-index:2;text-align:center;">
    <p style="font-size:.75rem;letter-spacing:.25em;text-transform:uppercase;color:var(--lam-gold);font-weight:600;margin-bottom:.75rem;">Informasi Terkini</p>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.75rem,4vw,2.75rem);color:white;">Berita &amp; Pengumuman</h1>
  </div>
</div>

<style>
  .page-hero{position:relative;padding:5rem 0 4rem;text-align:center;background-color:var(--lam-black);background-size:cover;background-position:center;background-repeat:no-repeat;}
  .page-hero__overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.55) 0%,rgba(0,0,0,.4) 60%,rgba(0,0,0,.7) 100%);z-index:1;}
  .page-hero__gold-edge{position:absolute;inset:0;background:linear-gradient(to right,rgba(249,149,34,.35) 0%,transparent 18%,transparent 82%,rgba(249,149,34,.35) 100%),linear-gradient(to bottom,rgba(249,149,34,.2) 0%,transparent 30%);z-index:1;pointer-events:none;}

  /* ── View Toggle ─────────────────────────────── */
  .view-toggle { display:flex; align-items:center; gap:.4rem; }
  .view-toggle-btn {
    display:flex; align-items:center; justify-content:center;
    width:36px; height:36px;
    border:1px solid var(--lam-border);
    border-radius:var(--radius-sm);
    background:var(--lam-bg-alt);
    color:var(--lam-text-l);
    cursor:pointer; transition:all .2s;
  }
  .view-toggle-btn:hover { color:var(--lam-green); border-color:var(--lam-green); }
  .view-toggle-btn.is-active {
    background:var(--lam-green);
    border-color:var(--lam-green);
    color:#fff;
  }

  /* ── GRID view ───────────────────────────────── */
  .berita-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:1.5rem;
    margin-bottom:3rem;
  }
  .berita-grid .card-berita { height:100%; }

  /* ── LIST view ───────────────────────────────── */
  .berita-list {
    display:flex; flex-direction:column;
    gap:1rem;
    margin-bottom:3rem;
  }
  .card-berita-list {
    display:flex; align-items:stretch;
    background:var(--lam-bg-alt);
    border:1px solid var(--lam-border);
    border-radius:var(--radius);
    overflow:hidden;
    cursor:pointer;
    transition:box-shadow .2s, transform .2s, border-color .2s;
  }
  .card-berita-list:hover {
    box-shadow:0 8px 28px rgba(0,0,0,.1);
    transform:translateY(-2px);
    border-color:var(--lam-green);
  }
  .card-berita-list__thumb {
    width:200px; min-width:200px;
    overflow:hidden; flex-shrink:0;
    position:relative;
  }
  .card-berita-list__thumb img {
    width:100%; height:100%;
    object-fit:cover;
    transition:transform .35s ease;
    display:block;
  }
  .card-berita-list:hover .card-berita-list__thumb img { transform:scale(1.05); }
  .card-berita-list__thumb-placeholder {
    width:100%; height:100%; min-height:140px;
    background:var(--lam-cream);
    display:flex; align-items:center; justify-content:center;
  }
  .card-berita-list__body {
    flex:1; padding:1.25rem 1.5rem;
    display:flex; flex-direction:column; justify-content:space-between;
    gap:.6rem;
  }
  .card-berita-list__cat {
    font-size:.7rem; font-weight:700;
    letter-spacing:.12em; text-transform:uppercase;
    color:var(--lam-gold);
  }
  .card-berita-list__title {
    font-family:var(--font-head);
    font-size:1.05rem; font-weight:700;
    color:var(--lam-text); line-height:1.4;
    margin:0;
  }
  .card-berita-list__title a { color:inherit; }
  .card-berita-list__title a:hover { color:var(--lam-green); }
  .card-berita-list__excerpt {
    font-size:.875rem; color:var(--lam-text-l);
    line-height:1.6; margin:0;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
  }
  .card-berita-list__meta {
    display:flex; align-items:center; justify-content:space-between;
    font-size:.8rem; color:var(--lam-text-l);
    flex-wrap:wrap; gap:.5rem;
    margin-top:.25rem;
  }
  .card-berita-list__read {
    color:var(--lam-green); font-weight:600; font-size:.8rem;
    display:flex; align-items:center; gap:.25rem;
    white-space:nowrap;
  }
  .card-berita-list__read:hover { text-decoration:underline; }

  @media (max-width:640px) {
    /* ── List: lebih persegi panjang di mobile ─── */
    .card-berita-list {
      min-height: 140px;
    }
    .card-berita-list__thumb {
      width: 130px; min-width: 130px;
    }
    .card-berita-list__thumb img,
    .card-berita-list__thumb-placeholder {
      min-height: 140px;
    }
    .card-berita-list__body {
      padding: .875rem 1rem;
      gap: .35rem;
    }
    .card-berita-list__title { font-size: .95rem; }
    .card-berita-list__excerpt {
      -webkit-line-clamp: 2;
      font-size: .8rem;
    }
    .card-berita-list__meta { font-size: .72rem; }

    /* ── Grid: 2 kolom di mobile ─────────────────── */
    .berita-grid {
      grid-template-columns: repeat(2, 1fr);
      gap: .875rem;
    }
    .berita-grid .card-berita__img,
    .berita-grid .card-berita__img-placeholder {
      height: 130px;
    }
    .berita-grid .card-berita__body {
      padding: .75rem;
    }
    .berita-grid .card-berita__title {
      font-size: .875rem;
      -webkit-line-clamp: 2;
    }
    .berita-grid .card-berita__excerpt {
      display: none;
    }
    .berita-grid .card-berita__meta {
      font-size: .7rem;
      flex-direction: column;
      align-items: flex-start;
      gap: .25rem;
    }
  }
</style>

{{-- Filter + Search + View Toggle --}}
<div style="background:var(--lam-bg-alt);border-bottom:1px solid var(--lam-border);padding:1rem 0;">
  <div class="container" style="display:flex;flex-wrap:wrap;align-items:center;gap:1rem;">

    {{-- Search --}}
    <form method="GET" action="{{ route('berita.index') }}" style="flex:1;min-width:200px;display:flex;gap:.5rem;" role="search">
      @if(request('kategori'))
        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
      @endif
      <div style="position:relative;flex:1;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="var(--lam-text-l)" stroke-width="2" viewBox="0 0 24 24"
             style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);" aria-hidden="true">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berita..."
               style="width:100%;padding:.6rem .75rem .6rem 2.25rem;border:1px solid var(--lam-border);border-radius:var(--radius-sm);
                      font-family:var(--font-body);font-size:.875rem;outline:none;background:var(--lam-bg-alt);color:var(--lam-text);"
               aria-label="Cari berita">
      </div>
      <button type="submit" class="btn btn-primary" style="padding:.6rem 1.25rem;">Cari</button>
    </form>

    {{-- Kategori filter pills --}}
    <div style="display:flex;flex-wrap:wrap;gap:.5rem;" role="group" aria-label="Filter kategori">
      <a href="{{ route('berita.index', request()->except('kategori')) }}"
         class="btn {{ !request('kategori') ? 'btn-primary' : 'btn-outline' }}"
         style="padding:.4rem .875rem;font-size:.8rem;">
        Semua
      </a>
      @foreach($kategori as $kat)
        <a href="{{ route('berita.index', array_merge(request()->except('kategori','page'), ['kategori' => $kat->slug])) }}"
           class="btn {{ request('kategori') === $kat->slug ? 'btn-primary' : 'btn-outline' }}"
           style="padding:.4rem .875rem;font-size:.8rem;">
          {{ $kat->nama }}
        </a>
      @endforeach
    </div>

    {{-- View Toggle --}}
    <div class="view-toggle" role="group" aria-label="Pilih tampilan" id="viewToggleGroup">
      <button id="btnList" class="view-toggle-btn is-active" onclick="setView('list')" title="Tampilan List" aria-pressed="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
          <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
      </button>
      <button id="btnGrid" class="view-toggle-btn" onclick="setView('grid')" title="Tampilan Grid" aria-pressed="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
        </svg>
      </button>
    </div>

  </div>
</div>

{{-- Berita Container --}}
<section class="section-pad" style="background:var(--lam-cream);">
  <div class="container">

    @if($berita->count() > 0)

      {{-- ── LIST VIEW ── --}}
      <div id="viewList" class="berita-list">
        @foreach($berita as $b)
          <article class="card-berita-list" onclick="window.location.href='{{ route('berita.show', $b->slug) }}'">
            {{-- Thumbnail --}}
            <div class="card-berita-list__thumb">
              @if($b->thumbnail)
                <img src="{{ Storage::url($b->thumbnail) }}" alt="{{ $b->judul }}" loading="lazy">
              @else
                <div class="card-berita-list__thumb-placeholder" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="var(--lam-green)" stroke-width="1" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                  </svg>
                </div>
              @endif
            </div>
            {{-- Body --}}
            <div class="card-berita-list__body">
              <div>
                <p class="card-berita-list__cat">{{ $b->kategori?->nama ?? 'Umum' }}</p>
                <h2 class="card-berita-list__title">
                  <a href="{{ route('berita.show', $b->slug) }}">{{ $b->judul }}</a>
                </h2>
                @if($b->excerpt)
                  <p class="card-berita-list__excerpt">{{ $b->excerpt }}</p>
                @endif
              </div>
              <div class="card-berita-list__meta">
                <time datetime="{{ $b->tanggal_publish?->toISOString() }}">
                  {{ $b->tanggal_publish?->translatedFormat('d M Y') ?? '—' }}
                </time>
                <a href="{{ route('berita.show', $b->slug) }}" class="card-berita-list__read">
                  Baca Selengkapnya
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      {{-- ── GRID VIEW ── --}}
      <div id="viewGrid" class="berita-grid" style="display:none;">
        @foreach($berita as $b)
          <article class="card-berita" style="cursor:pointer;" onclick="window.location.href='{{ route('berita.show', $b->slug) }}'">
            @if($b->thumbnail)
              <img src="{{ Storage::url($b->thumbnail) }}" alt="{{ $b->judul }}"
                   class="card-berita__img" loading="lazy">
            @else
              <div class="card-berita__img-placeholder" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="var(--lam-green)" stroke-width="1" viewBox="0 0 24 24">
                  <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                </svg>
              </div>
            @endif
            <div class="card-berita__body">
              <p class="card-berita__cat">{{ $b->kategori?->nama ?? 'Umum' }}</p>
              <h2 class="card-berita__title" style="font-size:.975rem;">
                <a href="{{ route('berita.show', $b->slug) }}" style="color:inherit;">{{ $b->judul }}</a>
              </h2>
              <p class="card-berita__excerpt">{{ $b->excerpt }}</p>
              <div class="card-berita__meta">
                <time datetime="{{ $b->tanggal_publish?->toISOString() }}">
                  {{ $b->tanggal_publish?->translatedFormat('d M Y') ?? '—' }}
                </time>
                <a href="{{ route('berita.show', $b->slug) }}"
                   style="color:var(--lam-green);font-weight:600;font-size:.8rem;display:flex;align-items:center;gap:.25rem;">
                  Baca
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($berita->hasPages())
        <div style="display:flex;justify-content:center;gap:.5rem;flex-wrap:wrap;">
          {{-- Prev --}}
          @if($berita->onFirstPage())
            <span style="padding:.5rem .875rem;border-radius:var(--radius-sm);background:var(--lam-bg-alt);color:var(--lam-text-l);border:1px solid var(--lam-border);">Sebelumnya</span>
          @else
            <a href="{{ $berita->previousPageUrl() }}" class="btn btn-outline" style="padding:.5rem .875rem;">Sebelumnya</a>
          @endif

          {{-- Pages --}}
          @foreach($berita->getUrlRange(max(1,$berita->currentPage()-2), min($berita->lastPage(),$berita->currentPage()+2)) as $page => $url)
            @if($page == $berita->currentPage())
              <span style="padding:.5rem .875rem;border-radius:var(--radius-sm);background:var(--lam-green);color:white;font-weight:600;min-width:40px;text-align:center;">{{ $page }}</span>
            @else
              <a href="{{ $url }}" style="padding:.5rem .875rem;border-radius:var(--radius-sm);border:1px solid var(--lam-border);background:var(--lam-bg-alt);color:var(--lam-text);min-width:40px;text-align:center;">{{ $page }}</a>
            @endif
          @endforeach

          {{-- Next --}}
          @if($berita->hasMorePages())
            <a href="{{ $berita->nextPageUrl() }}" class="btn btn-outline" style="padding:.5rem .875rem;">Berikutnya</a>
          @else
            <span style="padding:.5rem .875rem;border-radius:var(--radius-sm);background:var(--lam-bg-alt);color:var(--lam-text-l);border:1px solid var(--lam-border);">Berikutnya</span>
          @endif
        </div>
      @endif

    @else
      <div style="text-align:center;padding:5rem 0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="none" stroke="var(--lam-text-l)" stroke-width="1" viewBox="0 0 24 24" aria-hidden="true" style="margin:0 auto 1.5rem;"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
        <h2 style="color:var(--lam-text-l);font-size:1.1rem;font-weight:500;">Belum ada berita yang diterbitkan.</h2>
        @if(request('q') || request('kategori'))
          <p style="color:var(--lam-text-l);margin-top:.5rem;font-size:.9rem;">Coba ubah kata kunci atau pilih kategori yang berbeda.</p>
          <a href="{{ route('berita.index') }}" class="btn btn-outline" style="margin-top:1.5rem;">Lihat Semua Berita</a>
        @endif
      </div>
    @endif

  </div>
</section>

<script>
  // ── View Toggle Logic ───────────────────────────────────────
  const STORAGE_KEY = 'berita_view_pref';

  function setView(mode) {
    const listEl  = document.getElementById('viewList');
    const gridEl  = document.getElementById('viewGrid');
    const btnList = document.getElementById('btnList');
    const btnGrid = document.getElementById('btnGrid');

    if (mode === 'grid') {
      listEl.style.display  = 'none';
      gridEl.style.display  = 'grid';
      btnGrid.classList.add('is-active');
      btnList.classList.remove('is-active');
      btnGrid.setAttribute('aria-pressed', 'true');
      btnList.setAttribute('aria-pressed', 'false');
    } else {
      gridEl.style.display  = 'none';
      listEl.style.display  = 'flex';
      btnList.classList.add('is-active');
      btnGrid.classList.remove('is-active');
      btnList.setAttribute('aria-pressed', 'true');
      btnGrid.setAttribute('aria-pressed', 'false');
    }

    try { localStorage.setItem(STORAGE_KEY, mode); } catch(e) {}
  }

  // Restore saved preference on page load
  document.addEventListener('DOMContentLoaded', function () {
    let saved = 'list';
    try { saved = localStorage.getItem(STORAGE_KEY) || 'list'; } catch(e) {}
    setView(saved);
  });
</script>

@endsection
