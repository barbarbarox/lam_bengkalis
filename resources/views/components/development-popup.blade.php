<div id="dev-popup-overlay" class="dev-popup-overlay" style="display: none;">
    <div class="cookie-card">
        <span class="title">🚧 Website Dalam Pengembangan</span>
        <p class="description">
            Website ini masih dalam tahap pengembangan. Beberapa fitur mungkin belum berfungsi dengan sempurna dan konten yang ditampilkan saat ini sebagian besar menggunakan <strong>data dummy (percobaan)</strong>.
        </p>
        <div class="actions">
            <button id="dev-popup-accept" class="accept">
                Tidak Masalah, Lanjut
            </button>
        </div>
    </div>
</div>

<style>
/* Background overlay that blurs everything behind it */
.dev-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.4s ease;
}

.dev-popup-overlay.show {
    opacity: 1;
}

/* Prevent scrolling when popup is open */
body.dev-popup-open {
    overflow: hidden;
}

/* From Uiverse.io by Yaya12085 */ 
.cookie-card {
  max-width: 360px;
  padding: 1.5rem;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 20px 20px 30px rgba(0, 0, 0, .05);
  transform: translateY(20px) scale(0.95);
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  margin: 1rem;
}

.dev-popup-overlay.show .cookie-card {
  transform: translateY(0) scale(1);
}

/* Dark mode adjustments to match theme */
[data-theme="dark"] .cookie-card {
    background-color: var(--lam-black-l);
    box-shadow: 20px 20px 30px rgba(0, 0, 0, .5);
}

.cookie-card .title {
  font-weight: 600;
  color: rgb(31 41 55);
  font-size: 1.15rem;
  display: block;
}

[data-theme="dark"] .cookie-card .title {
    color: var(--lam-text);
}

.cookie-card .description {
  margin-top: 1rem;
  font-size: 0.9rem;
  line-height: 1.5rem;
  color: rgb(75 85 99);
}

[data-theme="dark"] .cookie-card .description {
    color: var(--lam-text-m);
}

.cookie-card .description strong {
    color: rgb(31 41 55);
}

[data-theme="dark"] .cookie-card .description strong {
    color: var(--lam-text);
}

.cookie-card .actions {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 1.5rem;
  flex-shrink: 0;
}

.cookie-card .accept {
  font-size: 0.9rem;
  line-height: 1rem;
  background-color: rgb(17 24 39);
  font-weight: 600;
  border-radius: 0.5rem;
  color: #fff;
  padding: 0.8rem 1.5rem;
  border: none;
  transition: all .15s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  width: 100%;
}

.cookie-card .accept:hover {
  background-color: rgb(55 65 81);
}

.cookie-card .accept:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
}

[data-theme="dark"] .cookie-card .accept {
    background-color: var(--lam-gold);
    color: var(--lam-black);
}
[data-theme="dark"] .cookie-card .accept:hover {
    background-color: var(--lam-gold-d);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('dev-popup-overlay');
    const acceptBtn = document.getElementById('dev-popup-accept');
    
    if (overlay && acceptBtn) {
        // Tampilkan popup setiap kali halaman dimuat
        overlay.style.display = 'flex';
        document.body.classList.add('dev-popup-open');
        
        // Trigger reflow for animation
        void overlay.offsetWidth;
        
        overlay.classList.add('show');
        
        // Focus on the button for accessibility
        setTimeout(() => {
            acceptBtn.focus();
        }, 100);
        
        acceptBtn.addEventListener('click', function() {
            // Hide popup with animation
            overlay.classList.remove('show');
            
            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.classList.remove('dev-popup-open');
            }, 400); // match transition duration
        });
    }
});
</script>
