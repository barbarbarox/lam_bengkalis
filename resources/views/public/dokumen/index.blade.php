@extends('layouts.app')
@section('title', __('ui.dokumen_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'dokumen',
    'gradient' => '#1A237E 0%, #0d1561',
    'title'    => __('ui.dokumen_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Dokumen &amp; Peraturan'],
    ]
])

<section class="section-pad">
  <div class="container">
    <p class="section-lead">{{ __('ui.dokumen_desc') }}</p>

    {{-- Filter & Search ─── --}}
    <form method="GET" action="{{ route('dokumen.index') }}" class="dokumen-filter">
      <input type="text" name="q" value="{{ $q }}" placeholder="Cari judul atau nomor dokumen..."
             class="filter-search" id="dokumen-search">
      <select name="jenis" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Jenis</option>
        @foreach(\App\Models\DokumenPeraturan::JENIS as $key => $label)
          <option value="{{ $key }}" {{ $jenis === $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
      <select name="tahun" class="filter-select" onchange="this.form.submit()">
        <option value="">Semua Tahun</option>
        @foreach($tahuns as $t)
          <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
      </select>
      <button type="submit" class="filter-btn">Cari</button>
      @if($q || $jenis || $tahun)
        <a href="{{ route('dokumen.index') }}" class="filter-reset">Reset</a>
      @endif
    </form>

    @if($items->count())
      <div class="dokumen-table-wrap">
        <table class="dokumen-table">
          <thead>
            <tr>
              <th>No.</th>
              <th>Judul Dokumen</th>
              <th>Jenis</th>
              <th>Nomor</th>
              <th>Tahun</th>
              <th>File</th>
              <th>Unduh</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $i => $dok)
              <tr>
                <td class="center">{{ $items->firstItem() + $i }}</td>
                <td>
                  <strong>{{ $dok->judul }}</strong>
                  @if($dok->deskripsi)
                    <p class="table-desc">{{ Str::limit($dok->deskripsi, 80) }}</p>
                  @endif
                </td>
                <td><span class="badge" style="background:rgba(26,35,126,.1);color:#1A237E;white-space:nowrap;">{{ $dok->label_jenis }}</span></td>
                <td>{{ $dok->nomor ?? '-' }}</td>
                <td>{{ $dok->tahun ?? '-' }}</td>
                <td class="center">
                  @if($dok->file_path)
                    @if($dok->is_pdf)
                      <span class="file-badge pdf">PDF</span>
                    @else
                      <span class="file-badge doc">DOC</span>
                    @endif
                    <span class="file-size">{{ $dok->ukuran_human }}</span>
                  @else
                    <span style="color:var(--lam-text-l);font-size:.78rem;">-</span>
                  @endif
                </td>
                <td class="center">
                  @if($dok->file_path)
                    <a href="{{ route('dokumen.unduh', $dok) }}" class="btn-unduh" title="Unduh {{ $dok->judul }}" target="_blank">
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    </a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="pagination-wrap">{{ $items->links() }}</div>
    @else
      <div class="empty-state" style="padding:4rem 0;text-align:center;color:var(--lam-text-l);">Belum ada dokumen yang tersedia{{ $q ? ' untuk pencarian "' . $q . '"' : '' }}.</div>
    @endif
  </div>
</section>

<style>
.dokumen-filter { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; margin-bottom: 2rem; }
.filter-search { flex: 1; min-width: 200px; padding: .65rem 1rem; border: 1.5px solid var(--lam-border); border-radius: 8px; font-size: .88rem; background: var(--lam-bg-alt); color: var(--lam-text); outline: none; transition: border-color .2s; }
.filter-search:focus { border-color: var(--lam-green); }
.filter-select { padding: .65rem 1rem; border: 1.5px solid var(--lam-border); border-radius: 8px; font-size: .85rem; background: var(--lam-bg-alt); color: var(--lam-text); cursor: pointer; }
.filter-btn { padding: .65rem 1.25rem; background: var(--lam-green); color: white; border: none; border-radius: 8px; font-weight: 700; font-size: .85rem; cursor: pointer; transition: background .2s; }
.filter-btn:hover { background: var(--lam-gold); }
.filter-reset { font-size: .82rem; color: var(--lam-text-l); text-decoration: none; padding: .5rem; }
.filter-reset:hover { color: var(--lam-green); }

.dokumen-table-wrap { overflow-x: auto; border: 1px solid var(--lam-border); border-radius: var(--radius); }
.dokumen-table { width: 100%; border-collapse: collapse; font-size: .85rem; }
.dokumen-table thead th {
  background: var(--lam-bg-alt); color: var(--lam-text-l); font-size: .72rem;
  font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
  padding: .85rem 1rem; text-align: left; border-bottom: 1px solid var(--lam-border);
}
.dokumen-table tbody tr { border-bottom: 1px solid var(--lam-border); transition: background .15s; }
.dokumen-table tbody tr:last-child { border-bottom: none; }
.dokumen-table tbody tr:hover { background: rgba(27,94,32,.04); }
.dokumen-table td { padding: .85rem 1rem; color: var(--lam-text); vertical-align: top; }
.dokumen-table td.center { text-align: center; vertical-align: middle; }
.table-desc { font-size: .75rem; color: var(--lam-text-l); margin: .25rem 0 0; }
.file-badge { display: inline-block; padding: .15rem .45rem; border-radius: 3px; font-size: .65rem; font-weight: 700; letter-spacing: .05em; }
.file-badge.pdf { background: #ffebee; color: #c62828; }
.file-badge.doc { background: #e3f2fd; color: #1565C0; }
.file-size { font-size: .7rem; color: var(--lam-text-l); display: block; margin-top: .15rem; }
.btn-unduh { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; background: var(--lam-green); color: white; border-radius: 8px; text-decoration: none; transition: background .2s; }
.btn-unduh:hover { background: var(--lam-gold); }
</style>
@include('public.partials._page-styles')
@endsection
