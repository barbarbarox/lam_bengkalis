{{--
    Loading Screen — Komponen LAM Bengkalis
    ==========================================
    - Animasi kapal tradisional dengan gelombang SVG
    - Progress bar simulasi dengan caption berganti
    - Hilang otomatis via window.onload + fallback 3 detik
    - sessionStorage: hanya tampil sekali per sesi
    - prefers-reduced-motion: langsung dilewati jika terdeteksi
    - ARIA role="status" untuk aksesibilitas
--}}

<style>
  :root {
    --lk-green:      #0B4F30;
    --lk-gold:       #D4AF37;
    --lk-gold-deep:  #B8941F;
    --lk-red:        #7A1E1E;
    --lk-cream:      #F5EEDD;
    --lk-wave-back:  rgba(11,79,48,.35);
    --lk-wave-mid:   rgba(11,79,48,.55);
    --lk-wave-front: rgba(11,79,48,.8);
  }

  .lam-loader {
    position:fixed; inset:0; z-index:9999;
    background:var(--lk-green);
    display:flex; flex-direction:column;
    align-items:center; justify-content:center;
    transition:opacity .7s ease, visibility .7s ease;
  }
  .lam-loader.is-done {
    opacity:0; visibility:hidden; pointer-events:none;
  }

  .lam-loader__stars {
    position:absolute; inset:0; pointer-events:none;
  }
  .lam-loader__stars span {
    position:absolute;
    width:4px; height:4px; border-radius:50%;
    background:var(--lk-gold);
    animation:lk-twinkle 2s infinite ease-in-out;
  }

  .lam-loader__header {
    text-align:center; color:var(--lk-cream); margin-bottom:12px; z-index:1;
  }
  .lam-loader__eyebrow {
    display:block; font-family:'Inter',sans-serif;
    font-size:clamp(.65rem,.9vw,.75rem);
    letter-spacing:.3em; text-transform:uppercase;
    opacity:.7; margin-bottom:4px;
  }
  .lam-loader__title {
    font-family:'Playfair Display',serif;
    font-size:clamp(1.1rem,2.5vw,1.6rem);
    font-weight:700; margin:0; color:var(--lk-gold);
  }
  .lam-loader__divider {
    display:flex; align-items:center; justify-content:center;
    gap:6px; margin-top:8px;
  }
  .lam-loader__divider span {
    display:block; height:1px; width:40px;
    background:var(--lk-gold); opacity:.4;
  }
  .lam-loader__divider i {
    display:block; width:6px; height:6px; border-radius:50%;
    background:var(--lk-gold); opacity:.8; font-style:normal;
  }

  .lam-loader__scene {
    position:relative; width:min(320px,90vw); height:180px; z-index:1;
  }
  .lam-loader__boat-anchor {
    position:absolute; bottom:55px; left:50%; transform:translateX(-50%);
  }
  .lam-loader__boat {
    width:180px; height:auto;
    animation:lk-boatBob 3.5s ease-in-out infinite;
    transform-origin:center bottom;
    filter:drop-shadow(0 6px 12px rgba(0,0,0,.4));
  }
  .lam-loader__sail {
    animation:lk-sailFlutter 2.8s ease-in-out infinite;
    transform-origin:left center;
  }
  .lam-loader__flag {
    animation:lk-flagWave 1.8s ease-in-out infinite;
    transform-origin:left center;
  }

  .lam-loader__waves-wrap {
    position:absolute; bottom:0; left:0; right:0; height:80px; overflow:hidden;
  }
  .lam-wave-layer {
    position:absolute; bottom:0; left:0; width:200%; height:100%;
  }
  .lam-wave-layer svg { width:100%; height:100%; }
  .lam-wave-layer--back  { animation:lk-waveScroll 5s linear infinite; }
  .lam-wave-layer--mid   { animation:lk-waveScroll 4s linear infinite; }
  .lam-wave-layer--front { animation:lk-waveScroll 3s linear infinite; }

  .lam-loader__footer {
    text-align:center; z-index:1; margin-top:8px; width:min(280px,80vw);
  }
  .lam-loader__caption {
    font-family:'Inter',sans-serif; font-size:.8rem;
    color:var(--lk-cream); opacity:.8; margin-bottom:8px;
    transition:opacity .22s ease; letter-spacing:.06em;
  }
  .lam-loader__progress {
    height:4px; background:rgba(255,255,255,.15); border-radius:4px;
    overflow:hidden; margin-bottom:4px;
  }
  .lam-loader__progress-bar {
    height:100%; width:0%;
    background:linear-gradient(90deg,var(--lk-gold-deep),var(--lk-gold));
    border-radius:4px; transition:width .16s ease-out;
  }
  .lam-loader__percent {
    font-size:11px; letter-spacing:.14em;
    color:var(--lk-gold); opacity:.9;
    font-family:'Inter',sans-serif;
  }

  @keyframes lk-boatBob {
    0%,100%{ transform:translateY(0) rotate(-2.5deg); }
    50%{ transform:translateY(-9px) rotate(2.5deg); }
  }
  @keyframes lk-flagWave {
    0%,100%{ transform:skewY(-10deg); }
    50%{ transform:skewY(10deg); }
  }
  @keyframes lk-sailFlutter {
    0%,100%{ transform:scaleX(1) skewY(0deg); }
    50%{ transform:scaleX(1.025) skewY(-1.2deg); }
  }
  @keyframes lk-waveScroll {
    from{ transform:translateX(0); }
    to{ transform:translateX(-50%); }
  }
  @keyframes lk-twinkle {
    0%,100%{ opacity:.15; }
    50%{ opacity:.8; }
  }

  @media (prefers-reduced-motion:reduce){
    .lam-loader__boat,.lam-loader__flag,.lam-loader__sail,
    .lam-wave-layer,.lam-loader__stars span {
      animation:none !important;
    }
  }
</style>

<div class="lam-loader" id="lamLoader" role="status"
     aria-label="Memuat halaman Lembaga Adat Melayu Kabupaten Bengkalis">

  <div class="lam-loader__stars" aria-hidden="true">
    <span style="top:12%;left:18%;animation-delay:.2s;"></span>
    <span style="top:20%;left:76%;animation-delay:1.1s;"></span>
    <span style="top:9%;left:50%;animation-delay:.6s;"></span>
    <span style="top:26%;left:32%;animation-delay:1.8s;"></span>
    <span style="top:15%;left:88%;animation-delay:.9s;"></span>
  </div>

  <div class="lam-loader__header">
    <span class="lam-loader__eyebrow">Lembaga Adat Melayu</span>
    <h2 class="lam-loader__title">Kabupaten Bengkalis</h2>
    <div class="lam-loader__divider" aria-hidden="true"><span></span><i></i><span></span></div>
  </div>

  <div class="lam-loader__scene" aria-hidden="true">
    <div class="lam-loader__boat-anchor">
      <svg class="lam-loader__boat" viewBox="0 0 240 190" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <ellipse cx="126" cy="152" rx="92" ry="9" fill="rgba(0,0,0,.22)"></ellipse>
        <path d="M46,150 C90,168 190,168 206,150 C214,130 218,90 222,50 C216,64 210,70 205,72 C170,84 130,88 110,90 C90,92 66,93 50,96 C36,86 28,78 28,68 C36,92 40,120 46,150 Z"
              fill="var(--lk-gold)" stroke="var(--lk-gold-deep)" stroke-width="2"></path>
        <path d="M50,96 C66,93 90,92 110,90 C130,88 170,84 205,72"
              fill="none" stroke="var(--lk-red)" stroke-width="3" stroke-linecap="round"></path>
        <path d="M222,50 C228,44 230,36 224,32" fill="none" stroke="var(--lk-gold)" stroke-width="2.5" stroke-linecap="round"></path>
        <circle cx="88" cy="140" r="3" fill="var(--lk-red)" opacity=".55"></circle>
        <circle cx="118" cy="141" r="3" fill="var(--lk-red)" opacity=".55"></circle>
        <circle cx="148" cy="140" r="3" fill="var(--lk-red)" opacity=".55"></circle>
        <line x1="130" y1="90" x2="130" y2="12" stroke="#5C3A21" stroke-width="4" stroke-linecap="round"></line>
        <g class="lam-loader__sail">
          <path d="M130,15 C158,20 176,34 170,54 C176,66 162,78 130,84 Z"
                fill="var(--lk-cream)" stroke="var(--lk-gold)" stroke-width="1.5"></path>
          <line x1="134" y1="32" x2="167" y2="38" stroke="var(--lk-gold-deep)" stroke-width="1" opacity=".45"></line>
          <line x1="134" y1="58" x2="162" y2="63" stroke="var(--lk-gold-deep)" stroke-width="1" opacity=".45"></line>
        </g>
        <g class="lam-loader__flag">
          <circle cx="130" cy="11" r="2.4" fill="var(--lk-gold)"></circle>
          <polygon points="130,8 154,4 130,18" fill="var(--lk-red)"></polygon>
        </g>
      </svg>
    </div>
    <div class="lam-loader__waves-wrap" aria-hidden="true">
      <div class="lam-wave-layer lam-wave-layer--back">
        <svg viewBox="0 0 2400 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0,80 Q75,40 150,80 T300,80 T450,80 T600,80 T750,80 T900,80 T1050,80 T1200,80 L1200,200 L0,200 Z" fill="var(--lk-wave-back)"></path>
          <path d="M0,80 Q75,40 150,80 T300,80 T450,80 T600,80 T750,80 T900,80 T1050,80 T1200,80 L1200,200 L0,200 Z" fill="var(--lk-wave-back)" transform="translate(1200,0)"></path>
        </svg>
      </div>
      <div class="lam-wave-layer lam-wave-layer--mid">
        <svg viewBox="0 0 2400 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0,90 Q75,55 150,90 T300,90 T450,90 T600,90 T750,90 T900,90 T1050,90 T1200,90 L1200,200 L0,200 Z" fill="var(--lk-wave-mid)"></path>
          <path d="M0,90 Q75,55 150,90 T300,90 T450,90 T600,90 T750,90 T900,90 T1050,90 T1200,90 L1200,200 L0,200 Z" fill="var(--lk-wave-mid)" transform="translate(1200,0)"></path>
        </svg>
      </div>
      <div class="lam-wave-layer lam-wave-layer--front">
        <svg viewBox="0 0 2400 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0,70 Q75,105 150,70 T300,70 T450,70 T600,70 T750,70 T900,70 T1050,70 T1200,70 L1200,200 L0,200 Z" fill="var(--lk-wave-front)"></path>
          <path d="M0,70 Q75,105 150,70 T300,70 T450,70 T600,70 T750,70 T900,70 T1050,70 T1200,70 L1200,200 L0,200 Z" fill="var(--lk-wave-front)" transform="translate(1200,0)"></path>
        </svg>
      </div>
    </div>
  </div>

  <div class="lam-loader__footer">
    <p class="lam-loader__caption" id="lamCaption">Menyusun sauh...</p>
    <div class="lam-loader__progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
      <div class="lam-loader__progress-bar" id="lamProgressBar"></div>
    </div>
    <span class="lam-loader__percent" id="lamPercent" aria-live="polite">0%</span>
  </div>

</div>

<script>
(function(){
  var loaderEl = document.getElementById('lamLoader');
  if (!loaderEl) return;

  // 1. prefers-reduced-motion → skip langsung
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    loaderEl.classList.add('is-done');
    return;
  }

  // 2. sessionStorage → tampil hanya sekali per sesi
  if (window.sessionStorage && sessionStorage.getItem('lam_loaded') === '1') {
    loaderEl.classList.add('is-done');
    return;
  }

  var captionEl  = document.getElementById('lamCaption');
  var percentEl  = document.getElementById('lamPercent');
  var barEl      = document.getElementById('lamProgressBar');
  var progressEl = loaderEl.querySelector('[role="progressbar"]');

  var progress = 0, captionIndex = 0;
  var captions = [
    'Menyusun sauh...',
    'Mengangkat layar...',
    'Menyusuri Selat Bengkalis...',
    'Merapat ke pelabuhan...'
  ];

  function tick() {
    var remaining = 100 - progress;
    progress += Math.max(0.5, remaining * 0.05) * (0.7 + Math.random() * 0.6);
    if (progress >= 100) progress = 100;

    var rounded = Math.floor(progress);
    if (percentEl) percentEl.textContent = rounded + '%';
    if (barEl) barEl.style.width = progress + '%';
    if (progressEl) progressEl.setAttribute('aria-valuenow', rounded);

    var nextIndex = Math.min(captions.length - 1, Math.floor((progress / 100) * captions.length));
    if (nextIndex !== captionIndex && captionEl) {
      captionIndex = nextIndex;
      captionEl.style.opacity = 0;
      setTimeout(function(){
        captionEl.textContent = captions[captionIndex];
        captionEl.style.opacity = 1;
      }, 220);
    }

    if (progress < 100) {
      setTimeout(tick, 160);
    } else {
      finish();
    }
  }

  function finish() {
    if (captionEl) {
      captionEl.style.opacity = 0;
      setTimeout(function(){
        captionEl.textContent = 'Selamat datang!';
        captionEl.style.opacity = 1;
      }, 220);
    }
    setTimeout(function(){
      loaderEl.classList.add('is-done');
      if (window.sessionStorage) sessionStorage.setItem('lam_loaded', '1');
    }, 900);
  }

  // Mulai animasi setelah DOM siap
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', tick);
  } else {
    tick();
  }

  // Percepat saat halaman benar-benar selesai
  window.addEventListener('load', function(){
    if (progress < 85) progress = 85;
  });

  // Fallback: paksa hilang setelah 3 detik
  setTimeout(function(){
    if (!loaderEl.classList.contains('is-done')) {
      loaderEl.classList.add('is-done');
      if (window.sessionStorage) sessionStorage.setItem('lam_loaded', '1');
    }
  }, 3000);
})();
</script>
