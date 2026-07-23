@extends('layouts.app')
@section('title', ($q ? 'Hasil Pencarian "' . $q . '"' : 'Pencarian') . ' — ' . ($setting->nama_lembaga ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'cari',
    'gradient' => '#37474F 0%, #1a2529',
    'title'    => 'Pencarian',
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Pencarian'],
    ]
])

<section class="section-pad">
  <div class="container">

    {{-- Search Bar ─── --}}
    <form method="GET" action="{{ route('cari') }}" class="cari-bar">
      <div class="cari-bar__inner">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-text-l)" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="q" value="{{ $q }}" class="cari-bar__input"
               placeholder="Ketik kata kunci pencarian..." autofocus id="cari-global">
        @if($q)
          <a href="{{ route('cari') }}" class="cari-bar__clear">✕</a>
        @endif
        <button type="submit" class="cari-bar__btn">Cari</button>
      </div>
    </form>

    @if($q)
      {{-- Ringkasan ── --}}
      <p class="cari-summary">
        @if($total > 0)
          Ditemukan <strong>{{ $total }}</strong> hasil untuk kata kunci "<em>{{ $q }}</em>"
        @else
          Tidak ada hasil untuk "<em>{{ $q }}</em>". Coba kata kunci lain.
        @endif
      </p>

      @if($total > 0)
        {{-- Tab Filter ── --}}
        <div class="cari-tabs" id="cari-tabs">
          @foreach($tabs as $key => $tab)
            @if($tab['count'] > 0 || $key === 'semua')
              <button class="cari-tab {{ $key === 'semua' ? 'is-active' : '' }}"
                      data-target="{{ $key }}"
                      onclick="filterCari('{{ $key }}', this)">
                {{ $tab['label'] }}
                <span class="cari-tab__count">{{ $tab['count'] }}</span>
              </button>
            @endif
          @endforeach
        </div>

        {{-- Results ── --}}
        <div class="cari-results">
          @foreach($results as $tipe => $items)
            @foreach($items as $result)
              <div class="cari-result-item" data-tipe="{{ $tipe }}">
                <div class="cari-result-item__badge" style="background:{{ $result['warna'] }}20;color:{{ $result['warna'] }};">
                  {{ $result['badge'] }}
                </div>
                <div class="cari-result-item__body">
                  <a href="{{ $result['url'] }}" class="cari-result-item__title">
                    {!! str_ireplace($q, '<mark>' . $q . '</mark>', e($result['judul'])) !!}
                  </a>
                  @if($result['excerpt'])
                    <p class="cari-result-item__excerpt">
                      {!! str_ireplace($q, '<mark>' . $q . '</mark>', e(Str::limit($result['excerpt'], 160))) !!}
                    </p>
                  @endif
                  @if($result['tanggal'])
                    <span class="cari-result-item__date">{{ $result['tanggal'] }}</span>
                  @endif
                </div>
                <a href="{{ $result['url'] }}" class="cari-result-item__arrow" aria-label="Buka">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
              </div>
            @endforeach
          @endforeach
        </div>
      @endif
    @else
      {{-- Empty / Suggestions ── --}}
      <div class="cari-empty">
        <p class="cari-empty__hint">Masukkan kata kunci untuk mencari di seluruh konten situs.</p>
        <div class="cari-empty__suggestions">
          <p>Saran pencarian populer:</p>
          <div class="cari-tags">
            @foreach(['Struktur', 'Hukum Adat', 'Agenda', 'Tokoh', 'Dokumen', 'Pelatihan'] as $tag)
              <a href="{{ route('cari', ['q' => $tag]) }}" class="cari-tag">{{ $tag }}</a>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</section>

<style>
.cari-bar { margin-bottom: 1.75rem; }
.cari-bar__inner { display: flex; align-items: center; gap: .75rem; background: var(--lam-bg-alt); border: 2px solid var(--lam-border); border-radius: 50px; padding: .65rem 1rem .65rem 1.25rem; transition: border-color .2s; }
.cari-bar__inner:focus-within { border-color: var(--lam-green); }
.cari-bar__input { flex: 1; border: none; background: transparent; font-size: 1rem; color: var(--lam-text); outline: none; }
.cari-bar__clear { text-decoration: none; color: var(--lam-text-l); font-size: .9rem; padding: .15rem .4rem; transition: color .2s; }
.cari-bar__clear:hover { color: var(--lam-green); }
.cari-bar__btn { padding: .55rem 1.25rem; background: var(--lam-green); color: white; border: none; border-radius: 50px; font-weight: 700; font-size: .88rem; cursor: pointer; transition: background .2s; white-space: nowrap; }
.cari-bar__btn:hover { background: var(--lam-gold); }

.cari-summary { font-size: .9rem; color: var(--lam-text-m); margin-bottom: 1.5rem; }
.cari-summary mark { background: rgba(255,193,7,.3); padding: 0 .15rem; border-radius: 2px; }
em { font-style: italic; }

.cari-tabs { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1.75rem; }
.cari-tab { display: flex; align-items: center; gap: .45rem; padding: .55rem 1rem; border: 1.5px solid var(--lam-border); border-radius: 50px; background: transparent; cursor: pointer; font-size: .82rem; font-weight: 600; color: var(--lam-text-m); transition: all .15s; }
.cari-tab.is-active, .cari-tab:hover { background: var(--lam-green); border-color: var(--lam-green); color: white; }
.cari-tab__count { background: rgba(255,255,255,.3); border-radius: 50px; padding: .05rem .45rem; font-size: .72rem; font-weight: 700; }
.cari-tab:not(.is-active) .cari-tab__count { background: var(--lam-bg); color: var(--lam-text-l); }

.cari-results { display: flex; flex-direction: column; gap: .75rem; }
.cari-result-item { display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 1.25rem; background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); transition: border-color .2s, box-shadow .2s; }
.cari-result-item:hover { border-color: var(--lam-green); box-shadow: 0 4px 16px rgba(27,94,32,.08); }
.cari-result-item[style*="display:none"] { display: none !important; }
.cari-result-item__badge { flex-shrink: 0; font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; padding: .3rem .65rem; border-radius: 4px; white-space: nowrap; }
.cari-result-item__body { flex: 1; min-width: 0; }
.cari-result-item__title { font-size: .95rem; font-weight: 700; color: var(--lam-text); text-decoration: none; line-height: 1.35; display: block; margin-bottom: .35rem; transition: color .2s; }
.cari-result-item:hover .cari-result-item__title { color: var(--lam-green); }
.cari-result-item__title mark { background: rgba(255,193,7,.35); border-radius: 2px; font-style: normal; }
.cari-result-item__excerpt { font-size: .83rem; color: var(--lam-text-m); line-height: 1.6; margin: 0 0 .35rem; }
.cari-result-item__excerpt mark { background: rgba(255,193,7,.35); font-style: normal; border-radius: 2px; }
.cari-result-item__date { font-size: .72rem; color: var(--lam-text-l); }
.cari-result-item__arrow { flex-shrink: 0; color: var(--lam-green); opacity: 0; transition: opacity .2s; align-self: center; text-decoration: none; }
.cari-result-item:hover .cari-result-item__arrow { opacity: 1; }

.cari-empty { padding: 3rem 0; text-align: center; }
.cari-empty__hint { font-size: 1rem; color: var(--lam-text-m); margin-bottom: 2rem; }
.cari-empty__suggestions p { font-size: .85rem; color: var(--lam-text-l); margin-bottom: .75rem; }
.cari-tags { display: flex; justify-content: center; flex-wrap: wrap; gap: .5rem; }
.cari-tag { padding: .45rem 1rem; border: 1.5px solid var(--lam-border); border-radius: 50px; font-size: .85rem; color: var(--lam-text-m); text-decoration: none; transition: all .15s; }
.cari-tag:hover { background: var(--lam-green); border-color: var(--lam-green); color: white; }
</style>

<script>
function filterCari(tipe, btn) {
  document.querySelectorAll('.cari-tab').forEach(function(t) { t.classList.remove('is-active'); });
  btn.classList.add('is-active');
  document.querySelectorAll('.cari-result-item').forEach(function(el) {
    if (tipe === 'semua') {
      el.style.display = '';
    } else {
      el.style.display = el.dataset.tipe === tipe ? '' : 'none';
    }
  });
}
</script>
@endsection
