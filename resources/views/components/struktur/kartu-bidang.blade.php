{{--
  Komponen: <x-struktur.kartu-bidang>

  Props:
    - $namaBidang  : string — nama lengkap bidang
    - $pimpinan    : StrukturOrganisasi|null — pimpinan (Penyelaras)
    - $anggotaLain : Collection<StrukturOrganisasi> — anggota biasa

  Fitur:
    - Kartu compact menampilkan foto/avatar + nama + label jabatan pimpinan
    - Tombol "Lihat X Anggota Lainnya" (hanya jika ada anggota lain)
    - Modal Alpine.js: fokus otomatis, trap keyboard Escape, klik backdrop
    - Aksesibel: role="dialog", aria-modal, aria-labelledby
    - Responsive: modal max-height + overflow-y-auto
    - Compatible dark/light mode via variabel CSS --lam-*

  Keamanan (OWASP A03):
    - Semua output data via {{ }} (auto-escape Blade) — TIDAK ada {!! !!}
    - Foto via Storage::url() yang sudah di-encode oleh Laravel
--}}

@php
  $modalId = 'modal-bidang-' . Str::slug($namaBidang);
  $judulId  = 'judul-' . $modalId;
  $jumlahAnggota = $anggotaLain->count();
@endphp

<div
  x-data="{ open: false }"
  @keydown.escape.window="open = false"
  class="kartu-bidang"
>
  {{-- ── Kartu Compact ──────────────────────────────────────────────────── --}}
  <div class="kartu-bidang__card">

    {{-- Nama Bidang (header) --}}
    <div class="kartu-bidang__header">
      <div class="kartu-bidang__header-icon" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
        </svg>
      </div>
      <h4 class="kartu-bidang__nama-bidang">{{ $namaBidang }}</h4>
    </div>

    {{-- Foto + Identitas Pimpinan --}}
    <div class="kartu-bidang__pimpinan">
      @if($pimpinan)
        @if($pimpinan->foto)
          <img
            src="{{ Storage::url($pimpinan->foto) }}"
            alt="Foto {{ $pimpinan->nama }}"
            class="kartu-bidang__avatar kartu-bidang__avatar--foto"
            loading="lazy"
            width="56" height="56"
          >
        @else
          <div class="kartu-bidang__avatar kartu-bidang__avatar--inisial" aria-hidden="true">
            <span>{{ $pimpinan->inisial }}</span>
          </div>
        @endif
        <div class="kartu-bidang__pimpinan-info">
          <span class="kartu-bidang__pimpinan-label">Penyelaras</span>
          <p class="kartu-bidang__pimpinan-nama">{{ $pimpinan->nama }}</p>
          <p class="kartu-bidang__pimpinan-jabatan">{{ $pimpinan->jabatan }}</p>
        </div>
      @else
        <div class="kartu-bidang__avatar kartu-bidang__avatar--inisial" aria-hidden="true">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        <div class="kartu-bidang__pimpinan-info">
          <span class="kartu-bidang__pimpinan-label">Penyelaras</span>
          <p class="kartu-bidang__pimpinan-nama" style="color:var(--lam-text-l);font-style:italic;">Belum ditetapkan</p>
        </div>
      @endif
    </div>

    {{-- Footer: counter + tombol --}}
    <div class="kartu-bidang__footer">
      @if($jumlahAnggota > 0)
        <span class="kartu-bidang__badge">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          {{ $jumlahAnggota }} anggota
        </span>
        <button
          type="button"
          class="kartu-bidang__btn-lihat"
          @click="open = true; $nextTick(() => $refs.btnClose?.focus())"
          aria-haspopup="dialog"
          aria-controls="{{ $modalId }}"
          id="trigger-{{ $modalId }}"
        >
          Lihat Anggota
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
        </button>
      @else
        <span class="kartu-bidang__badge kartu-bidang__badge--empty">Belum ada anggota</span>
      @endif
    </div>
  </div>

  {{-- ── Modal / Dialog ─────────────────────────────────────────────────── --}}
  @if($jumlahAnggota > 0)
    {{-- Backdrop --}}
    <div
      x-show="open"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      class="kartu-bidang__backdrop"
      @click="open = false"
      aria-hidden="true"
      style="display:none;"
    ></div>

    {{-- Panel Modal --}}
    <div
      x-show="open"
      x-transition:enter="transition ease-out duration-250"
      x-transition:enter-start="opacity-0 translate-y-4 scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 scale-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100 translate-y-0 scale-100"
      x-transition:leave-end="opacity-0 translate-y-4 scale-95"
      id="{{ $modalId }}"
      class="kartu-bidang__modal-wrap"
      role="dialog"
      aria-modal="true"
      aria-labelledby="{{ $judulId }}"
      style="display:none;"
    >
      <div class="kartu-bidang__modal-panel">

        {{-- ── Header Modal ── --}}
        <div class="kartu-bidang__modal-header">
          <div class="kartu-bidang__modal-header-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
              <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
          </div>
          <div class="kartu-bidang__modal-header-text">
            <p class="kartu-bidang__modal-eyebrow">Daftar Anggota</p>
            <h3 id="{{ $judulId }}" class="kartu-bidang__modal-judul">{{ $namaBidang }}</h3>
          </div>
          <button
            type="button"
            class="kartu-bidang__modal-close"
            @click="open = false; $nextTick(() => document.getElementById('trigger-{{ $modalId }}')?.focus())"
            aria-label="Tutup dialog anggota {{ $namaBidang }}"
            x-ref="btnClose"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
              <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>

        {{-- ── Pimpinan Summary di Modal ── --}}
        @if($pimpinan)
        <div class="kartu-bidang__modal-pimpinan">
          <div class="kartu-bidang__modal-pimpinan-badge">Penyelaras</div>
          <div class="kartu-bidang__modal-pimpinan-row">
            @if($pimpinan->foto)
              <img src="{{ Storage::url($pimpinan->foto) }}" alt="Foto {{ $pimpinan->nama }}"
                class="kartu-bidang__modal-pimpinan-avatar" loading="lazy" width="48" height="48">
            @else
              <div class="kartu-bidang__modal-pimpinan-avatar kartu-bidang__modal-pimpinan-avatar--inisial">
                <span>{{ $pimpinan->inisial }}</span>
              </div>
            @endif
            <div>
              <p class="kartu-bidang__modal-pimpinan-nama">{{ $pimpinan->nama }}</p>
              <p class="kartu-bidang__modal-pimpinan-jabatan">{{ $pimpinan->jabatan }}</p>
            </div>
          </div>
        </div>
        @endif

        {{-- ── Daftar Anggota ── --}}
        <div class="kartu-bidang__modal-body">
          <p class="kartu-bidang__modal-section-label">{{ $jumlahAnggota }} Anggota</p>
          <ul class="kartu-bidang__anggota-list" role="list">
            @foreach($anggotaLain as $i => $anggota)
              <li class="kartu-bidang__anggota-item">
                <span class="kartu-bidang__anggota-no">{{ $i + 1 }}</span>
                @if($anggota->foto)
                  <img
                    src="{{ Storage::url($anggota->foto) }}"
                    alt="Foto {{ $anggota->nama }}"
                    class="kartu-bidang__anggota-avatar kartu-bidang__anggota-avatar--foto"
                    loading="lazy" width="40" height="40"
                  >
                @else
                  <div class="kartu-bidang__anggota-avatar kartu-bidang__anggota-avatar--inisial" aria-hidden="true">
                    <span>{{ $anggota->inisial }}</span>
                  </div>
                @endif
                <div class="kartu-bidang__anggota-info">
                  <p class="kartu-bidang__anggota-nama">{{ $anggota->nama }}</p>
                  @if($anggota->jabatan && strtolower(trim($anggota->jabatan)) !== 'anggota')
                    <p class="kartu-bidang__anggota-jabatan">{{ $anggota->jabatan }}</p>
                  @else
                    <p class="kartu-bidang__anggota-jabatan">Anggota</p>
                  @endif
                </div>
              </li>
            @endforeach
          </ul>
        </div>

        {{-- ── Footer Modal ── --}}
        <div class="kartu-bidang__modal-footer">
          <p class="kartu-bidang__modal-counter">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            Total {{ $jumlahAnggota + ($pimpinan ? 1 : 0) }} orang
          </p>
          <button
            type="button"
            class="kartu-bidang__modal-btn-tutup"
            @click="open = false; $nextTick(() => document.getElementById('trigger-{{ $modalId }}')?.focus())"
          >
            Tutup
          </button>
        </div>

      </div>{{-- end panel --}}
    </div>{{-- end modal-wrap --}}
  @endif
</div>
