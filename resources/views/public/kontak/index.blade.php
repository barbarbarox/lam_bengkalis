@extends('layouts.app')

@section('title', 'Kontak — ' . ($setting->nama_lembaga ?? 'LAMR Bengkalis'))

@push('head_scripts')
{{-- reCAPTCHA v3 --}}
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
@endpush

@section('content')

{{-- Page Header --}}
@php $heroBg = $setting->heroUrl('kontak'); @endphp
<div class="page-hero" style="{{ $heroBg ? 'background-image:url('.$heroBg.')' : '' }}">
  <div class="page-hero__overlay"></div>
  @if($heroBg)<div class="page-hero__gold-edge"></div>@endif
  <div class="container" style="position:relative;z-index:2;text-align:center;">
    <p style="font-size:.75rem;letter-spacing:.25em;text-transform:uppercase;color:var(--lam-gold);font-weight:600;margin-bottom:.75rem;">Hubungi Kami</p>
    <h1 style="font-family:var(--font-head);font-size:clamp(1.75rem,4vw,2.75rem);color:white;">Kontak &amp; Pengaduan</h1>
    <p style="color:rgba(255,255,255,.7);margin-top:.75rem;max-width:520px;margin-left:auto;margin-right:auto;">
      Sampaikan pertanyaan, masukan, atau pengaduan Anda kepada kami melalui formulir di bawah ini.
    </p>
  </div>
</div>
<style>
  .page-hero{position:relative;padding:5rem 0 4rem;text-align:center;background-color:var(--lam-black);background-size:cover;background-position:center;background-repeat:no-repeat;}
  .page-hero__overlay{position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.55) 0%,rgba(0,0,0,.4) 60%,rgba(0,0,0,.7) 100%);z-index:1;}
  .page-hero__gold-edge{position:absolute;inset:0;background:linear-gradient(to right,rgba(249,149,34,.35) 0%,transparent 18%,transparent 82%,rgba(249,149,34,.35) 100%),linear-gradient(to bottom,rgba(249,149,34,.2) 0%,transparent 30%);z-index:1;pointer-events:none;}

  /* ── Kontak Layout ─────────────────────────── */
  .kontak-grid {
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    gap: 3rem;
    align-items: start;
  }

  /* ── Peta Responsif ────────────────────────── */
  .peta-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    overflow: hidden;
    border-radius: var(--radius);
    box-shadow: var(--lam-shadow);
  }
  .peta-wrapper iframe {
    position: absolute;
    inset: 0;
    width: 100% !important;
    height: 100% !important;
    border: 0;
  }

  /* ── Form Grid ─────────────────────────────── */
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
  .form-col-full { grid-column: 1 / -1; }

  /* ── Kontak Card ───────────────────────────── */
  .kontak-form-card {
    background: var(--lam-bg-alt);
    border-radius: var(--radius);
    padding: 2.5rem;
    box-shadow: var(--lam-shadow);
  }

  /* ── Responsive ────────────────────────────── */
  @media (max-width: 860px) {
    .kontak-grid {
      grid-template-columns: 1fr;
      gap: 2rem;
    }
  }
  @media (max-width: 600px) {
    .kontak-form-card {
      padding: 1.25rem 1rem;
      border-radius: var(--radius-sm);
    }
    .form-grid {
      grid-template-columns: 1fr;
    }
    .form-col-full { grid-column: 1; }
    .peta-wrapper { padding-bottom: 65%; }
  }
</style>



<section class="section-pad" style="background:var(--lam-cream);">
  <div class="container">
    <div class="kontak-grid">

      {{-- Info Kontak + Peta --}}
      <div>
        <h2 style="font-family:var(--font-head);font-size:1.4rem;color:var(--lam-green);margin-bottom:1.5rem;">Informasi Kontak</h2>

        <div style="display:flex;flex-direction:column;gap:1.25rem;margin-bottom:2rem;">

          @if($setting->alamat)
            <div style="display:flex;gap:1rem;align-items:flex-start;">
              <span style="flex-shrink:0;width:40px;height:40px;border-radius:50%;background:rgba(11,79,48,.08);
                           display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              </span>
              <div>
                <p style="font-size:.75rem;font-weight:700;color:var(--lam-gold);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.25rem;">Alamat</p>
                <address style="font-style:normal;color:var(--lam-text-m);font-size:.9rem;line-height:1.6;">{{ $setting->alamat }}</address>
              </div>
            </div>
          @endif

          @if($setting->email_kontak)
            <div style="display:flex;gap:1rem;align-items:center;">
              <span style="flex-shrink:0;width:40px;height:40px;border-radius:50%;background:rgba(11,79,48,.08);
                           display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </span>
              <div>
                <p style="font-size:.75rem;font-weight:700;color:var(--lam-gold);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.25rem;">Email</p>
                <a href="mailto:{{ $setting->email_kontak }}" style="color:var(--lam-green);font-size:.9rem;">{{ $setting->email_kontak }}</a>
              </div>
            </div>
          @endif

          @if($setting->no_telp)
            <div style="display:flex;gap:1rem;align-items:center;">
              <span style="flex-shrink:0;width:40px;height:40px;border-radius:50%;background:rgba(11,79,48,.08);
                           display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--lam-green)" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              </span>
              <div>
                <p style="font-size:.75rem;font-weight:700;color:var(--lam-gold);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.25rem;">Telepon</p>
                <a href="tel:{{ $setting->no_telp }}" style="color:var(--lam-green);font-size:.9rem;">{{ $setting->no_telp }}</a>
              </div>
            </div>
          @endif
        </div>

        {{-- Embed Peta --}}
        @if($setting->embed_peta && str_contains($setting->embed_peta, 'google.com/maps/embed'))
          <div class="peta-wrapper">
            {!! $setting->embed_peta !!}
          </div>
        @endif
      </div>

      {{-- Formulir Pengaduan --}}
      <div class="kontak-form-card">
        <h2 style="font-family:var(--font-head);font-size:1.35rem;color:var(--lam-gold);margin-bottom:.5rem;">Kirim Pesan / Pengaduan</h2>
        <p style="font-size:.875rem;color:var(--lam-gold);margin-bottom:1.75rem;">
          Semua kolom bertanda <span style="color:#c0392b;">*</span> wajib diisi.
        </p>

        {{-- Flash Messages --}}
        @if(session('success'))
          <div role="alert" style="background:#ecfdf5;border:1px solid #6ee7b7;border-radius:var(--radius-sm);
                                   padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;gap:.75rem;align-items:flex-start;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" style="flex-shrink:0;margin-top:2px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <p style="font-size:.9rem;color:#065f46;">{{ session('success') }}</p>
          </div>
        @endif

        @if($errors->any() || session('error'))
          <div role="alert" style="background:#fef2f2;border:1px solid #fca5a5;border-radius:var(--radius-sm);
                                   padding:1rem 1.25rem;margin-bottom:1.5rem;">
            <p style="font-size:.9rem;color:#991b1b;font-weight:600;margin-bottom:.5rem;">
              {{ session('error') ?? 'Mohon perbaiki kesalahan berikut:' }}
            </p>
            @if($errors->any())
              <ul style="padding-left:1.25rem;font-size:.85rem;color:#7f1d1d;">
                @foreach($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            @endif
          </div>
        @endif

        <form method="POST" action="{{ route('kontak.kirim') }}" id="formKontak" novalidate>
          @csrf

          {{-- Hidden reCAPTCHA token --}}
          <input type="hidden" name="g-recaptcha-response" id="recaptchaToken">

          <div class="form-grid">

            {{-- Nama --}}
            <div class="form-col-full">
              <label for="nama_pengadu" style="display:block;font-size:.82rem;font-weight:600;color:var(--lam-gold);margin-bottom:.35rem;">
                Nama Lengkap <span style="color:#c0392b;" aria-hidden="true">*</span>
              </label>
              <input type="text" id="nama_pengadu" name="nama_pengadu" required
                     value="{{ old('nama_pengadu') }}"
                     style="width:100%;padding:.7rem .875rem;border:1px solid {{ $errors->has('nama_pengadu') ? '#ef4444' : 'var(--lam-border)' }};
                            border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9rem;outline:none;
                            transition:border-color .2s;"
                     onfocus="this.style.borderColor='var(--lam-gold)'"
                     onblur="this.style.borderColor='{{ $errors->has('nama_pengadu') ? '#ef4444' : 'var(--lam-border)' }}'">
              @error('nama_pengadu')<p style="color:#ef4444;font-size:.78rem;margin-top:.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div>
              <label for="email" style="display:block;font-size:.82rem;font-weight:600;color:var(--lam-gold);margin-bottom:.35rem;">
                Email <span style="color:#c0392b;" aria-hidden="true">*</span>
              </label>
              <input type="email" id="email" name="email" required
                     value="{{ old('email') }}"
                     style="width:100%;padding:.7rem .875rem;border:1px solid {{ $errors->has('email') ? '#ef4444' : 'var(--lam-border)' }};
                            border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9rem;outline:none;"
                     onfocus="this.style.borderColor='var(--lam-gold)'"
                     onblur="this.style.borderColor='{{ $errors->has('email') ? '#ef4444' : 'var(--lam-border)' }}'">
              @error('email')<p style="color:#ef4444;font-size:.78rem;margin-top:.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Telepon --}}
            <div>
              <label for="no_telp" style="display:block;font-size:.82rem;font-weight:600;color:var(--lam-gold);margin-bottom:.35rem;">No. Telepon</label>
              <input type="tel" id="no_telp" name="no_telp"
                     value="{{ old('no_telp') }}"
                     placeholder="Opsional"
                     style="width:100%;padding:.7rem .875rem;border:1px solid var(--lam-border);
                            border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9rem;outline:none;"
                     onfocus="this.style.borderColor='var(--lam-gold)'"
                     onblur="this.style.borderColor='var(--lam-border)'">
            </div>

            {{-- Subjek --}}
            <div class="form-col-full">
              <label for="subjek" style="display:block;font-size:.82rem;font-weight:600;color:var(--lam-gold);margin-bottom:.35rem;">
                Subjek <span style="color:#c0392b;" aria-hidden="true">*</span>
              </label>
              <input type="text" id="subjek" name="subjek" required
                     value="{{ old('subjek') }}"
                     style="width:100%;padding:.7rem .875rem;border:1px solid {{ $errors->has('subjek') ? '#ef4444' : 'var(--lam-border)' }};
                            border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9rem;outline:none;"
                     onfocus="this.style.borderColor='var(--lam-gold)'"
                     onblur="this.style.borderColor='{{ $errors->has('subjek') ? '#ef4444' : 'var(--lam-border)' }}'">
              @error('subjek')<p style="color:#ef4444;font-size:.78rem;margin-top:.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- Isi --}}
            <div class="form-col-full">
              <label for="isi_aduan" style="display:block;font-size:.82rem;font-weight:600;color:var(--lam-gold);margin-bottom:.35rem;">
                Isi Pesan / Pengaduan <span style="color:#c0392b;" aria-hidden="true">*</span>
              </label>
              <textarea id="isi_aduan" name="isi_aduan" rows="6" required
                        style="width:100%;padding:.7rem .875rem;border:1px solid {{ $errors->has('isi_aduan') ? '#ef4444' : 'var(--lam-border)' }};
                               border-radius:var(--radius-sm);font-family:var(--font-body);font-size:.9rem;outline:none;resize:vertical;"
                        onfocus="this.style.borderColor='var(--lam-gold)'"
                        onblur="this.style.borderColor='{{ $errors->has('isi_aduan') ? '#ef4444' : 'var(--lam-border)' }}'">{{ old('isi_aduan') }}</textarea>
              @error('isi_aduan')<p style="color:#ef4444;font-size:.78rem;margin-top:.25rem;">{{ $message }}</p>@enderror
            </div>

            {{-- reCAPTCHA notice --}}
            <p class="form-col-full" style="font-size:.73rem;color:var(--lam-text-l);">
              Formulir ini dilindungi reCAPTCHA Google.
              <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" style="color:var(--lam-green);">Kebijakan Privasi</a> dan
              <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" style="color:var(--lam-green);">Ketentuan Layanan</a> berlaku.
            </p>

            {{-- Submit --}}
            <div class="form-col-full">
              <button type="submit" class="btn btn-primary" id="btnKirim" style="width:100%;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Kirim Pesan
              </button>
            </div>

          </div>
        </form>
      </div>

    </div>
  </div>
</section>



@push('body_scripts')
<script>
// reCAPTCHA v3 — execute saat form disubmit
document.getElementById('formKontak')?.addEventListener('submit', function(e) {
  e.preventDefault();
  var form = this;
  var siteKey = '{{ config('services.recaptcha.site_key') }}';
  if (!siteKey) { form.submit(); return; }

  var btn = document.getElementById('btnKirim');
  if (btn) { btn.disabled = true; btn.textContent = 'Mengirim...'; }

  try {
    grecaptcha.ready(function() {
      grecaptcha.execute(siteKey, { action: 'kontak_kirim' }).then(function(token) {
        document.getElementById('recaptchaToken').value = token;
        form.submit();
      }).catch(function() {
        // Fallback: kirim tanpa token (server akan menolak)
        form.submit();
      });
    });
  } catch(err) {
    form.submit();
  }
});
</script>
@endpush

@endsection
