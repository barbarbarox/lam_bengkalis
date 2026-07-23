@extends('layouts.app')
@section('title', __('ui.permohonan_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'permohonan',
    'gradient' => '#00695C 0%, #004d40',
    'title'    => __('ui.permohonan_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Permohonan Informasi'],
    ]
])

<section class="section-pad">
  <div class="container" style="max-width: 900px;">

    {{-- Tiket Sukses ── --}}
    @if(session('sukses_tiket'))
      <div class="tiket-sukses" id="tiket-sukses">
        <div class="tiket-sukses__icon">✓</div>
        <div>
          <h3>{{ __('ui.tiket_berhasil') }}</h3>
          <p>{{ __('ui.nomor_tiket') }}: <strong class="tiket-sukses__no">{{ session('sukses_tiket') }}</strong></p>
          <p class="tiket-sukses__hint">{{ __('ui.simpan_tiket') }}</p>
        </div>
      </div>
    @endif

    {{-- Tabs: Form | Cek Status ─── --}}
    <div class="permohonan-tabs">
      <button class="permohonan-tab {{ !$item ? 'is-active' : '' }}" onclick="switchTab('form')" id="tab-form">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Ajukan Permohonan
      </button>
      <button class="permohonan-tab {{ $item ? 'is-active' : '' }}" onclick="switchTab('status')" id="tab-status">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Cek Status Permohonan
      </button>
    </div>

    {{-- Form Permohonan ─── --}}
    <div id="panel-form" class="{{ $item ? 'hidden' : '' }}">
      <div class="form-card">
        <div class="form-card__info">
          <p>{{ __('ui.permohonan_desc') }}</p>
        </div>

        @if($errors->any())
          <div class="alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          </div>
        @endif

        <form method="POST" action="{{ route('permohonan-informasi.kirim') }}" id="form-permohonan">
          @csrf
          <div class="form-grid">
            <div class="form-group">
              <label for="nama_pemohon" class="form-label">{{ __('ui.nama_pemohon') }} <span class="required">*</span></label>
              <input type="text" id="nama_pemohon" name="nama_pemohon"
                     value="{{ old('nama_pemohon') }}" required maxlength="200"
                     class="form-input @error('nama_pemohon') is-error @enderror"
                     placeholder="Nama lengkap sesuai identitas">
              @error('nama_pemohon') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
              <label for="email" class="form-label">Email <span class="required">*</span></label>
              <input type="email" id="email" name="email"
                     value="{{ old('email') }}" required maxlength="200"
                     class="form-input @error('email') is-error @enderror"
                     placeholder="email@contoh.com">
              @error('email') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
              <label for="no_hp" class="form-label">No. HP / WhatsApp</label>
              <input type="tel" id="no_hp" name="no_hp"
                     value="{{ old('no_hp') }}" maxlength="30"
                     class="form-input" placeholder="08xx-xxxx-xxxx">
            </div>
            <div class="form-group">
              <label for="instansi" class="form-label">{{ __('ui.instansi') }}</label>
              <input type="text" id="instansi" name="instansi"
                     value="{{ old('instansi') }}" maxlength="200"
                     class="form-input" placeholder="Nama instansi/lembaga (opsional)">
            </div>
            <div class="form-group form-group--full">
              <label for="jenis_informasi" class="form-label">{{ __('ui.jenis_informasi') }} <span class="required">*</span></label>
              <input type="text" id="jenis_informasi" name="jenis_informasi"
                     value="{{ old('jenis_informasi') }}" required maxlength="200"
                     class="form-input @error('jenis_informasi') is-error @enderror"
                     placeholder="Contoh: Data Kepengurusan, Anggaran, Kegiatan, dll.">
              @error('jenis_informasi') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group form-group--full">
              <label for="uraian_permohonan" class="form-label">{{ __('ui.uraian_permohonan') }} <span class="required">*</span></label>
              <textarea id="uraian_permohonan" name="uraian_permohonan" required
                        minlength="20" maxlength="5000" rows="6"
                        class="form-input form-textarea @error('uraian_permohonan') is-error @enderror"
                        placeholder="Jelaskan secara rinci informasi yang Anda mohon...">{{ old('uraian_permohonan') }}</textarea>
              @error('uraian_permohonan') <p class="form-error">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="form-footer">
            <p class="form-disclaimer">
              Dengan mengirimkan formulir ini, Anda menyetujui bahwa data yang diberikan akan digunakan
              untuk keperluan pelayanan informasi publik sesuai UU No. 14 Tahun 2008.
            </p>
            <button type="submit" class="btn-kirim" id="btn-kirim">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              {{ __('ui.kirim') }} Permohonan
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Cek Status ─── --}}
    <div id="panel-status" class="{{ !$item ? 'hidden' : '' }}">
      <div class="form-card">
        <form method="GET" action="{{ route('permohonan-informasi.status') }}" class="cek-form">
          <label class="form-label">Masukkan Nomor Tiket Anda</label>
          <div class="cek-form__row">
            <input type="text" name="tiket" value="{{ $nomor ?? '' }}"
                   class="form-input" placeholder="Contoh: PI-20240722-AB12" maxlength="30" style="text-transform:uppercase;">
            <button type="submit" class="filter-btn">Cek</button>
          </div>
        </form>

        @if(isset($nomor) && $nomor)
          @if($item)
            <div class="status-result" style="border-left: 4px solid {{ $item->warna_badge }};">
              <div class="status-result__header">
                <span class="status-result__tiket">{{ $item->nomor_tiket }}</span>
                <span class="badge" style="background:{{ $item->warna_badge }};color:white;padding:.3rem .75rem;">{{ $item->label_status }}</span>
              </div>
              <p><strong>Pemohon:</strong> {{ $item->nama_pemohon }}</p>
              <p><strong>Jenis Informasi:</strong> {{ $item->jenis_informasi }}</p>
              <p><strong>Dikirim:</strong> {{ $item->created_at->translatedFormat('d M Y H:i') }}</p>
              @if($item->catatan_admin)
                <div class="status-catatan">
                  <p class="status-catatan__label">Balasan / Catatan Admin:</p>
                  <p>{{ $item->catatan_admin }}</p>
                </div>
              @endif
            </div>
          @else
            <div class="alert-error" style="margin-top:1rem;">Nomor tiket "{{ $nomor }}" tidak ditemukan.</div>
          @endif
        @endif
      </div>
    </div>
  </div>
</section>

<style>
.tiket-sukses { display: flex; gap: 1rem; align-items: flex-start; background: #E8F5E9; border: 1.5px solid #4CAF50; border-radius: var(--radius); padding: 1.5rem; margin-bottom: 2rem; }
.tiket-sukses__icon { width: 40px; height: 40px; background: #4CAF50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 700; flex-shrink: 0; }
.tiket-sukses h3 { margin: 0 0 .35rem; color: #1B5E20; font-size: 1rem; }
.tiket-sukses p { margin: 0 0 .25rem; color: #2E7D32; font-size: .88rem; }
.tiket-sukses__no { font-size: 1.1rem; color: #1B5E20; letter-spacing: .05em; font-family: monospace; }
.tiket-sukses__hint { font-size: .78rem; color: #388E3C; }

.permohonan-tabs { display: flex; gap: .75rem; margin-bottom: 1.75rem; flex-wrap: wrap; }
.permohonan-tab { display: flex; align-items: center; gap: .5rem; padding: .7rem 1.35rem; border: 1.5px solid var(--lam-border); border-radius: 8px; background: transparent; cursor: pointer; font-size: .88rem; font-weight: 600; color: var(--lam-text-m); transition: all .2s; }
.permohonan-tab.is-active { background: var(--lam-green); border-color: var(--lam-green); color: white; }
.permohonan-tab:hover:not(.is-active) { border-color: var(--lam-green); color: var(--lam-green); }

.form-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); padding: 2rem; }
.form-card__info { background: rgba(27,94,32,.06); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1.75rem; font-size: .85rem; color: var(--lam-text-m); line-height: 1.7; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.form-group { display: flex; flex-direction: column; gap: .4rem; }
.form-group--full { grid-column: 1 / -1; }
.form-label { font-size: .82rem; font-weight: 700; color: var(--lam-text); }
.required { color: #C62828; }
.form-input { padding: .7rem 1rem; border: 1.5px solid var(--lam-border); border-radius: 8px; font-size: .9rem; background: var(--lam-bg); color: var(--lam-text); outline: none; transition: border-color .2s; }
.form-input:focus { border-color: var(--lam-green); }
.form-input.is-error { border-color: #C62828; }
.form-textarea { resize: vertical; min-height: 140px; font-family: inherit; }
.form-error { font-size: .75rem; color: #C62828; margin: 0; }
.form-footer { margin-top: 1.75rem; display: flex; flex-direction: column; gap: 1rem; }
.form-disclaimer { font-size: .75rem; color: var(--lam-text-l); line-height: 1.6; margin: 0; }
.btn-kirim { display: flex; align-items: center; justify-content: center; gap: .65rem; padding: .9rem 2rem; background: var(--lam-green); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background .2s; align-self: flex-start; }
.btn-kirim:hover { background: var(--lam-gold); }

.cek-form { margin-bottom: 1.5rem; }
.cek-form__row { display: flex; gap: .75rem; margin-top: .5rem; }
.cek-form__row .form-input { flex: 1; }

.status-result { background: var(--lam-bg); border-radius: 8px; padding: 1.25rem; margin-top: 1rem; display: flex; flex-direction: column; gap: .75rem; }
.status-result__header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .5rem; }
.status-result__tiket { font-family: monospace; font-size: .95rem; font-weight: 700; color: var(--lam-text); }
.status-result p { margin: 0; font-size: .88rem; color: var(--lam-text-m); }
.status-catatan { background: rgba(27,94,32,.06); border-radius: 6px; padding: .85rem 1rem; }
.status-catatan__label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--lam-green); margin: 0 0 .35rem; }

.alert-error { background: #ffebee; border: 1px solid #ef9a9a; border-radius: 8px; padding: 1rem 1.25rem; color: #c62828; }
.alert-error ul { margin: .5rem 0 0; padding-left: 1.25rem; }
.alert-error li { font-size: .85rem; margin-bottom: .25rem; }

.hidden { display: none; }
@media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } }
</style>

<script>
function switchTab(t) {
  ['form','status'].forEach(function(id) {
    document.getElementById('panel-' + id).classList.toggle('hidden', id !== t);
    document.getElementById('tab-' + id).classList.toggle('is-active', id === t);
  });
}
</script>
@include('public.partials._page-styles')
@endsection
