@extends('layouts.app')

@section('title', $artikel->judul . ' — ' . ($setting->nama_lembaga ?? 'LAM Bengkalis'))
@section('meta_description', $artikel->excerpt ?? '')

@section('content')

{{-- Breadcrumb --}}
<div style="background:var(--lam-green-d);padding:.75rem 0;">
  <div class="container">
    <nav aria-label="Breadcrumb">
      <ol style="display:flex;gap:.5rem;align-items:center;list-style:none;font-size:.8rem;color:rgba(255,255,255,.6);">
        <li><a href="{{ route('beranda') }}" style="color:rgba(255,255,255,.7);">Beranda</a></li>
        <li aria-hidden="true" style="color:rgba(255,255,255,.35);">/</li>
        <li><a href="{{ route('berita.index') }}" style="color:rgba(255,255,255,.7);">Berita</a></li>
        <li aria-hidden="true" style="color:rgba(255,255,255,.35);">/</li>
        @if($artikel->kategori)
          <li>
            <a href="{{ route('berita.index', ['kategori' => $artikel->kategori->slug]) }}"
               style="color:var(--lam-gold);">{{ $artikel->kategori->nama }}</a>
          </li>
          <li aria-hidden="true" style="color:rgba(255,255,255,.35);">/</li>
        @endif
        <li style="color:rgba(255,255,255,.5);" aria-current="page">
          {{ Str::limit($artikel->judul, 40) }}
        </li>
      </ol>
    </nav>
  </div>
</div>

{{-- Artikel --}}
<main class="section-pad" style="background:var(--lam-cream);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:3rem;align-items:start;" class="artikel-grid">

      {{-- Konten Utama --}}
      <article style="background:white;border-radius:var(--radius);overflow:hidden;box-shadow:var(--lam-shadow);">
        {{-- Thumbnail --}}
        @if($artikel->thumbnail)
          <img src="{{ Storage::url($artikel->thumbnail) }}"
               alt="{{ $artikel->judul }}"
               style="width:100%;height:clamp(220px,40vw,420px);object-fit:cover;display:block;">
        @endif

        <div style="padding:2rem 2.5rem;">
          {{-- Meta atas --}}
          <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1rem;margin-bottom:1.25rem;">
            @if($artikel->kategori)
              <a href="{{ route('berita.index', ['kategori' => $artikel->kategori->slug]) }}"
                 style="font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;
                        color:var(--lam-gold);background:rgba(212,175,55,.1);padding:.25rem .75rem;
                        border-radius:99px;border:1px solid rgba(212,175,55,.3);">
                {{ $artikel->kategori->nama }}
              </a>
            @endif
            <time datetime="{{ $artikel->tanggal_publish?->toISOString() }}"
                  style="font-size:.8rem;color:var(--lam-text-l);">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="display:inline;vertical-align:-2px;margin-right:3px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $artikel->tanggal_publish?->translatedFormat('d F Y') ?? '—' }}
            </time>
            @if($artikel->penulis)
              <span style="font-size:.8rem;color:var(--lam-text-l);">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="display:inline;vertical-align:-2px;margin-right:3px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $artikel->penulis->name }}
              </span>
            @endif
            <span style="font-size:.8rem;color:var(--lam-text-l);">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="display:inline;vertical-align:-2px;margin-right:3px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              {{ number_format($artikel->jumlah_dilihat) }} kali dilihat
            </span>
          </div>

          {{-- Judul --}}
          <h1 style="font-family:var(--font-head);font-size:clamp(1.5rem,3vw,2.25rem);
                     color:var(--lam-text);line-height:1.3;margin-bottom:1.5rem;">
            {{ $artikel->judul }}
          </h1>

          {{-- Divider --}}
          <div style="height:3px;width:60px;background:var(--lam-gold);border-radius:2px;margin-bottom:2rem;"></div>

          {{-- Konten --}}
          <div class="prose-konten artikel-body">
            {!! $artikel->konten !!}
          </div>

          {{-- Share --}}
          <div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--lam-border);
                      display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
            <span style="font-size:.8rem;color:var(--lam-text-l);font-weight:600;">Bagikan:</span>
            <a href="https://wa.me/?text={{ urlencode($artikel->judul . ' — ' . request()->url()) }}"
               target="_blank" rel="noopener noreferrer"
               style="padding:.4rem .875rem;border-radius:var(--radius-sm);background:#25D366;
                      color:white;font-size:.8rem;font-weight:600;display:flex;align-items:center;gap:.35rem;">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
              WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
               target="_blank" rel="noopener noreferrer"
               style="padding:.4rem .875rem;border-radius:var(--radius-sm);background:#1877F2;
                      color:white;font-size:.8rem;font-weight:600;display:flex;align-items:center;gap:.35rem;">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
              Facebook
            </a>
          </div>
        </div>
      </article>

      {{-- Sidebar --}}
      <aside>
        {{-- Berita Terkait --}}
        @if($terkait->count() > 0)
          <div style="background:white;border-radius:var(--radius);padding:1.5rem;box-shadow:var(--lam-shadow);margin-bottom:1.5rem;">
            <h2 style="font-family:var(--font-head);font-size:1.1rem;color:var(--lam-green);margin-bottom:1.25rem;
                        padding-bottom:.75rem;border-bottom:2px solid var(--lam-gold);">
              Berita Terkait
            </h2>
            <div style="display:flex;flex-direction:column;gap:1rem;">
              @foreach($terkait as $b)
                <article style="display:flex;gap:.875rem;align-items:flex-start;">
                  @if($b->thumbnail)
                    <img src="{{ Storage::url($b->thumbnail) }}" alt="{{ $b->judul }}"
                         style="width:72px;height:52px;object-fit:cover;border-radius:var(--radius-sm);flex-shrink:0;" loading="lazy">
                  @else
                    <div style="width:72px;height:52px;background:var(--lam-cream);border-radius:var(--radius-sm);flex-shrink:0;
                                 display:flex;align-items:center;justify-content:center;" aria-hidden="true">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--lam-green)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                  @endif
                  <div style="flex:1;min-width:0;">
                    <a href="{{ route('berita.show', $b->slug) }}"
                       style="font-size:.82rem;font-weight:600;color:var(--lam-text);
                              display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
                              line-height:1.4;">
                      {{ $b->judul }}
                    </a>
                    <time style="font-size:.73rem;color:var(--lam-text-l);">
                      {{ $b->tanggal_publish?->translatedFormat('d M Y') }}
                    </time>
                  </div>
                </article>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Kembali ke list --}}
        <a href="{{ route('berita.index') }}"
           style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;font-weight:600;
                  color:var(--lam-green);padding:1rem 1.25rem;background:white;
                  border-radius:var(--radius);box-shadow:var(--lam-shadow);">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Kembali ke Daftar Berita
        </a>
      </aside>

    </div>
  </div>
</main>

<style>
  .artikel-body { color: var(--lam-text-m); line-height: 1.9; font-size: .975rem; }
  .artikel-body p { margin-bottom: 1.25rem; }
  .artikel-body h2 { font-family:var(--font-head); color:var(--lam-green); font-size:1.4rem; margin:2rem 0 1rem; }
  .artikel-body h3 { font-family:var(--font-head); color:var(--lam-text); font-size:1.15rem; margin:1.5rem 0 .75rem; }
  .artikel-body ul, .artikel-body ol { margin-left:1.5rem; margin-bottom:1.25rem; }
  .artikel-body li { margin-bottom:.5rem; }
  .artikel-body blockquote {
    border-left:4px solid var(--lam-gold); padding:1rem 1.5rem;
    background:rgba(212,175,55,.06); border-radius:0 var(--radius-sm) var(--radius-sm) 0;
    margin:1.5rem 0; color:var(--lam-text-m); font-style:italic;
  }
  .artikel-body img { border-radius:var(--radius-sm); margin:1.5rem 0; max-width:100%; }
  .artikel-body a { color:var(--lam-green); text-decoration:underline; }

  @media (max-width:900px) {
    .artikel-grid { grid-template-columns:1fr !important; }
    aside { order:-1; }
  }
  @media (max-width:600px) {
    article div[style*="padding:2rem"] { padding:1.25rem !important; }
  }
</style>

@endsection
