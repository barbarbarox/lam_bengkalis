@extends('layouts.app')

@section('title', $artikel->judul . ' — ' . ($setting->nama_lembaga ?? 'LAMR Bengkalis'))
@section('meta_description', Str::limit(strip_tags($artikel->excerpt ?? $artikel->konten ?? ''), 155))
@section('og_type', 'article')
@section('og_image', $artikel->thumbnail ? Storage::url($artikel->thumbnail) : asset('images/icon-512x512.png'))

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
      <article style="background:var(--lam-bg-alt);border-radius:var(--radius);overflow:hidden;box-shadow:var(--lam-shadow);">
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
            {!! $currentKonten !!}
          </div>

          {{-- Navigasi Halaman Konten (jika artikel panjang) --}}
          @if($paginator->hasPages())
          <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px dashed var(--lam-border);">
            <div style="font-size:0.9rem;color:var(--lam-text-l);margin-bottom:1rem;font-weight:600;">
              Lanjut ke halaman:
            </div>
            {{ $paginator->links('vendor.pagination.lam') }}
          </div>
          @endif

          {{-- Share --}}
          <div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--lam-border);
                      display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
            <span style="font-size:.9rem;color:var(--lam-text);font-weight:700;font-family:var(--font-head);">Bagikan Artikel:</span>
            
            <div class="social-buttons">
              <a href="https://wa.me/?text={{ urlencode($artikel->judul . ' — ' . request()->url()) }}" target="_blank" class="social-button github" title="Bagikan ke WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
              </a>
              <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="social-button linkedin" title="Bagikan ke LinkedIn">
                <svg viewBox="0 -2 44 44" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Icons" stroke="none" stroke-width="1">
                    <g transform="translate(-702.000000, -265.000000)">
                        <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"></path>
                    </g>
                </g>
                </svg>
              </a>
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="social-button facebook" title="Bagikan ke Facebook">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 310 310" xml:space="preserve">
                  <g id="XMLID_834_">
                    <path id="XMLID_835_" d="M81.703,165.106h33.981V305c0,2.762,2.238,5,5,5h57.616c2.762,0,5-2.238,5-5V165.765h39.064
                      c2.54,0,4.677-1.906,4.967-4.429l5.933-51.502c0.163-1.417-0.286-2.836-1.234-3.899c-0.949-1.064-2.307-1.673-3.732-1.673h-44.996
                      V71.978c0-9.732,5.24-14.667,15.576-14.667c1.473,0,29.42,0,29.42,0c2.762,0,5-2.239,5-5V5.037c0-2.762-2.238-5-5-5h-40.545
                      C187.467,0.023,186.832,0,185.896,0c-7.035,0-31.488,1.381-50.804,19.151c-21.402,19.692-18.427,43.27-17.716,47.358v37.752H81.703
                      c-2.762,0-5,2.238-5,5v50.844C76.703,162.867,78.941,165.106,81.703,165.106z"></path>
                  </g>
                </svg>
              </a>
              <a href="#" class="social-button instagram" title="Bagikan">
                <svg width="800px" height="800px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <g id="Page-1" stroke="none" stroke-width="1">
                      <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -7439.000000)">
                          <g id="icons" transform="translate(56.000000, 160.000000)">
                              <path d="M289.869652,7279.12273 C288.241769,7279.19618 286.830805,7279.5942 285.691486,7280.72871 C284.548187,7281.86918 284.155147,7283.28558 284.081514,7284.89653 C284.035742,7285.90201 283.768077,7293.49818 284.544207,7295.49028 C285.067597,7296.83422 286.098457,7297.86749 287.454694,7298.39256 C288.087538,7298.63872 288.809936,7298.80547 289.869652,7298.85411 C298.730467,7299.25511 302.015089,7299.03674 303.400182,7295.49028 C303.645956,7294.859 303.815113,7294.1374 303.86188,7293.08031 C304.26686,7284.19677 303.796207,7282.27117 302.251908,7280.72871 C301.027016,7279.50685 299.5862,7278.67508 289.869652,7279.12273 M289.951245,7297.06748 C288.981083,7297.0238 288.454707,7296.86201 288.103459,7296.72603 C287.219865,7296.3826 286.556174,7295.72155 286.214876,7294.84312 C285.623823,7293.32944 285.819846,7286.14023 285.872583,7284.97693 C285.924325,7283.83745 286.155174,7282.79624 286.959165,7281.99226 C287.954203,7280.99968 289.239792,7280.51332 297.993144,7280.90837 C299.135448,7280.95998 300.179243,7281.19026 300.985224,7281.99226 C301.980262,7282.98483 302.473801,7284.28014 302.071806,7292.99991 C302.028024,7293.96767 301.865833,7294.49274 301.729513,7294.84312 C300.829003,7297.15085 298.757333,7297.47145 289.951245,7297.06748 M298.089663,7283.68956 C298.089663,7284.34665 298.623998,7284.88065 299.283709,7284.88065 C299.943419,7284.88065 300.47875,7284.34665 300.47875,7283.68956 C300.47875,7283.03248 299.943419,7282.49847 299.283709,7282.49847 C298.623998,7282.49847 298.089663,7283.03248 298.089663,7283.68956 M288.862673,7288.98792 C288.862673,7291.80286 291.150266,7294.08479 293.972194,7294.08479 C296.794123,7294.08479 299.081716,7291.80286 299.081716,7288.98792 C299.081716,7286.17298 296.794123,7283.89205 293.972194,7283.89205 C291.150266,7283.89205 288.862673,7286.17298 288.862673,7288.98792 M290.655732,7288.98792 C290.655732,7287.16159 292.140329,7285.67967 293.972194,7285.67967 C295.80406,7285.67967 297.288657,7287.16159 297.288657,7288.98792 C297.288657,7290.81525 295.80406,7292.29716 293.972194,7292.29716 C292.140329,7292.29716 290.655732,7290.81525 290.655732,7288.98792" id="instagram-[#167]"></path>
                          </g>
                      </g>
                  </g>
                </svg>
              </a>
            </div>
          </div>
        </div>
      </article>

      {{-- Fitur Rekomendasi Artikel Lainnya --}}
      @if(isset($rekomendasi) && $rekomendasi->count() > 0)
      <div style="margin-top: 3rem;">
        <h2 style="font-family:var(--font-head);font-size:1.6rem;color:var(--lam-text);margin-bottom:1.5rem;display:inline-block;border-bottom:3px solid var(--lam-gold);padding-bottom:.5rem;">
          Rekomendasi Artikel Lainnya
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;">
          @foreach($rekomendasi as $rek)
            <a href="{{ route('berita.show', $rek->slug) }}" style="display:flex;flex-direction:column;background:var(--lam-bg-alt);border-radius:var(--radius);overflow:hidden;box-shadow:var(--lam-shadow);transition:transform var(--transition);text-decoration:none;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
              @if($rek->thumbnail)
                <img src="{{ Storage::url($rek->thumbnail) }}" alt="{{ $rek->judul }}" style="width:100%;height:140px;object-fit:cover;" loading="lazy">
              @else
                <div style="width:100%;height:140px;background:var(--lam-cream);display:flex;align-items:center;justify-content:center;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="var(--lam-green)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
              @endif
              <div style="padding:1rem;">
                <h3 style="font-size:.95rem;color:var(--lam-text);line-height:1.4;margin-bottom:.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $rek->judul }}</h3>
                <time style="font-size:.75rem;color:var(--lam-text-l);">{{ $rek->tanggal_publish?->translatedFormat('d M Y') }}</time>
              </div>
            </a>
          @endforeach
        </div>
      </div>
      @endif
      
      </div>
      
      <div>

      {{-- Sidebar --}}
      <aside>
        {{-- Berita Terkait --}}
        @if($terkait->count() > 0)
          <div style="background:var(--lam-bg-alt);border-radius:var(--radius);padding:1.5rem;box-shadow:var(--lam-shadow);margin-bottom:1.5rem;">
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
                  color:var(--lam-green);padding:1rem 1.25rem;background:var(--lam-bg-alt);
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
  .artikel-body h1 { font-family:var(--font-head); color:var(--lam-text); font-size:1.75rem; margin:2.5rem 0 1.25rem; font-weight:700; line-height:1.3; }
  .artikel-body h2 { font-family:var(--font-head); color:var(--lam-green); font-size:1.5rem; margin:2rem 0 1rem; font-weight:700; }
  .artikel-body h3 { font-family:var(--font-head); color:var(--lam-text); font-size:1.25rem; margin:1.5rem 0 .75rem; font-weight:600; }
  .artikel-body h4 { font-family:var(--font-head); color:var(--lam-text); font-size:1.15rem; margin:1.25rem 0 .5rem; font-weight:600; }
  .artikel-body ul, .artikel-body ol { margin-left:1.5rem; margin-bottom:1.25rem; }
  .artikel-body ul { list-style-type: disc; }
  .artikel-body ol { list-style-type: decimal; }
  .artikel-body li { margin-bottom:.5rem; }
  .artikel-body blockquote {
    border-left:4px solid var(--lam-gold); padding:1rem 1.5rem;
    background:rgba(212,175,55,.06); border-radius:0 var(--radius-sm) var(--radius-sm) 0;
    margin:1.5rem 0; color:var(--lam-text-m); font-style:italic;
  }
  .artikel-body img { border-radius:var(--radius-sm); margin:1.5rem auto; max-width:100%; display:block; height:auto; }
  .artikel-body a { color:var(--lam-green); text-decoration:underline; transition:color 0.2s; }
  .artikel-body a:hover { color:var(--lam-gold); }
  .artikel-body hr { border: none; border-top: 2px solid var(--lam-border); margin: 2rem 0; }
  
  /* Tables */
  .artikel-body table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; font-size: 0.9rem; overflow-x: auto; display: block; }
  @media (min-width: 600px) { .artikel-body table { display: table; } }
  .artikel-body table th, .artikel-body table td { border: 1px solid var(--lam-border); padding: 0.75rem 1rem; text-align: left; }
  .artikel-body table th { background: rgba(11, 79, 48, 0.05); font-weight: 600; color: var(--lam-green); }
  .artikel-body table tbody tr:nth-child(even) { background: rgba(0,0,0,0.015); }
  
  /* Code Blocks */
  .artikel-body code { background: rgba(0,0,0,0.05); padding: 0.2em 0.4em; border-radius: 4px; font-size: 0.875em; font-family: monospace; color: #e83e8c; }
  .artikel-body pre { background: #1e1e1e; color: #d4d4d4; padding: 1.25rem; border-radius: var(--radius-sm); margin: 1.5rem 0; overflow-x: auto; }
  .artikel-body pre code { background: transparent; padding: 0; color: inherit; font-size: 0.85em; }
  
  /* Alignment utilities from TipTap */
  .artikel-body [style*="text-align: right"] { text-align: right; }
  .artikel-body [style*="text-align: center"] { text-align: center; }
  .artikel-body [style*="text-align: justify"] { text-align: justify; }

  @media (max-width:900px) {
    .artikel-grid { grid-template-columns:1fr !important; }
    aside { order:-1; }
  }
  @media (max-width:600px) {
    article div[style*="padding:2rem"] { padding:1.25rem !important; }
  }

  /* Share buttons animation from Uiverse */
  .social-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: transparent;
    padding: 10px 0;
  }
  .social-button {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin: 0 8px;
    background-color: var(--lam-bg);
    box-shadow: 0px 0px 4px rgba(0,0,0,0.15);
    transition: 0.3s;
  }
  .social-button:hover {
    background-color: var(--lam-bg-alt);
    box-shadow: 0px 0px 8px 3px rgba(0,0,0,0.15);
    transform: translateY(-3px);
  }
  .social-buttons svg {
    transition: 0.3s;
    height: 20px;
    width: 20px;
  }
  .facebook { background-color: #3b5998; }
  .facebook svg { fill: #f2f2f2; }
  .facebook:hover svg { fill: #3b5998; }
  
  .github { background-color: #25D366; /* Adapted github color to Whatsapp for context */ }
  .github svg { fill: #f2f2f2; }
  .github:hover svg { fill: #25D366; }
  
  .linkedin { background-color: #0077b5; }
  .linkedin svg { fill: #f2f2f2; }
  .linkedin:hover svg { fill: #0077b5; }
  
  .instagram { background-color: #c13584; }
  .instagram svg { fill: #f2f2f2; }
  .instagram:hover svg { fill: #c13584; }

  /* Ensure correct hover icon colors based on theme if needed, but Uiverse sets fill explicitly */
</style>

@endsection

@push('body_scripts')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ addslashes($artikel->judul) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($artikel->excerpt ?? ''), 155)) }}",
  "datePublished": "{{ $artikel->tanggal_publish?->toISOString() }}",
  "dateModified": "{{ $artikel->updated_at->toISOString() }}",
  "image": "{{ $artikel->thumbnail ? Storage::url($artikel->thumbnail) : asset('images/icon-512x512.png') }}",
  "url": "{{ url()->current() }}",
  "inLanguage": "id-ID",
  @if($artikel->penulis)
  "author": {
    "@type": "Person",
    "name": "{{ addslashes($artikel->penulis->name ?? '') }}"
  },
  @endif
  "publisher": {
    "@type": "Organization",
    "name": "{{ addslashes($setting->nama_lembaga ?? 'LAMR Bengkalis') }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/icon-192x192.png') }}"
    }
  }
}
</script>
@endpush
