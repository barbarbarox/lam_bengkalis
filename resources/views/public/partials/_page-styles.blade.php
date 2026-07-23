{{-- Shared CSS untuk semua halaman modul baru --}}
<style>
/* ─── Shared Layout ─────────────────────────────────────────── */
.section-pad { padding: 3.5rem 0; }
.hero-mini { padding: 3.5rem 0 2.5rem; }
.hero-mini__inner { display: flex; flex-direction: column; gap: .6rem; }
.hero-mini__title { font-size: clamp(1.6rem,3.5vw,2.4rem); color: #fff; font-weight: 700; margin: 0; font-family: var(--font-head); }
.hero-mini__crumb { display: flex; gap: .5rem; font-size: .78rem; color: rgba(255,255,255,.65); flex-wrap: wrap; }
.hero-mini__crumb a { color: var(--lam-gold); text-decoration: none; }
.hero-mini__crumb a:hover { text-decoration: underline; }

.section-lead { color: var(--lam-text-m); line-height: 1.8; font-size: .95rem; margin-bottom: 2rem; max-width: 700px; }

/* ─── Filter Pills ───────────────────────────────────────────── */
.filter-pills { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 2rem; }
.filter-pill { display: inline-block; padding: .45rem 1rem; border: 1.5px solid var(--lam-border); border-radius: 50px; font-size: .82rem; font-weight: 600; color: var(--lam-text-m); text-decoration: none; transition: all .15s; }
.filter-pill.is-active, .filter-pill:hover { background: var(--lam-green); border-color: var(--lam-green); color: white; }

/* ─── Dokumen Cards ─────────────────────────────────────────── */
.dokumen-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
.dokumen-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); padding: 1.25rem; display: flex; gap: 1rem; align-items: flex-start; transition: border-color .2s, box-shadow .2s; }
.dokumen-card:hover { border-color: var(--lam-green); box-shadow: 0 6px 20px rgba(0,0,0,.08); }
.dokumen-card__icon { flex-shrink: 0; width: 52px; height: 52px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.dokumen-card__body { flex: 1; min-width: 0; }
.dokumen-card__meta { display: flex; align-items: center; gap: .5rem; margin-bottom: .5rem; flex-wrap: wrap; }
.dokumen-card__tahun { font-size: .72rem; color: var(--lam-text-l); font-weight: 600; }
.dokumen-card__title { font-size: .9rem; font-weight: 700; color: var(--lam-text); line-height: 1.35; margin: 0 0 .35rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.dokumen-card__nomor { font-size: .75rem; color: var(--lam-text-l); margin: 0 0 .35rem; }
.dokumen-card__desc { font-size: .8rem; color: var(--lam-text-m); line-height: 1.5; margin: 0 0 .75rem; }
.dokumen-card__actions { display: flex; gap: .5rem; flex-wrap: wrap; }
.btn-action { display: inline-flex; align-items: center; gap: .35rem; padding: .4rem .85rem; border: 1.5px solid var(--lam-green); border-radius: 6px; font-size: .75rem; font-weight: 700; color: var(--lam-green); text-decoration: none; transition: all .15s; }
.btn-action:hover { background: var(--lam-green); color: white; }
.btn-action--dl { border-color: var(--lam-gold); color: #92681a; }
.btn-action--dl:hover { background: var(--lam-gold); color: white; }
.btn-action--dl small { opacity: .8; }

/* ─── Detail Layout ─────────────────────────────────────────── */
.detail-layout { display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start; }
.detail-card { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); padding: 2rem; }
.detail-card__badges { display: flex; gap: .5rem; flex-wrap: wrap; margin-bottom: 1rem; }
.detail-card__banner { width: 100%; height: 280px; object-fit: cover; border-radius: 8px; margin-bottom: 1.5rem; display: block; }
.prose { line-height: 1.9; color: var(--lam-text); font-size: .95rem; }
.prose h2, .prose h3 { margin: 1.5rem 0 .75rem; color: var(--lam-text); font-family: var(--font-head); }
.prose p { margin-bottom: 1rem; }
.prose ul, .prose ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.prose li { margin-bottom: .35rem; }
.prose img { max-width: 100%; border-radius: 8px; }

/* ─── Info Box ──────────────────────────────────────────────── */
.info-box { background: var(--lam-bg-alt); border: 1px solid var(--lam-border); border-radius: var(--radius); padding: 1.5rem; }
.info-box__title { font-size: .95rem; font-weight: 700; color: var(--lam-text); margin: 0 0 1rem; padding-bottom: .75rem; border-bottom: 2px solid var(--lam-green); }
.info-box__list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: .85rem; }
.info-box__list li { display: flex; flex-direction: column; gap: .2rem; font-size: .85rem; }
.info-box__label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--lam-text-l); }
.info-box__list li span:last-child { color: var(--lam-text); }

/* ─── Badge shared ──────────────────────────────────────────── */
.badge { display: inline-block; font-size: .68rem; font-weight: 700; padding: .2rem .55rem; border-radius: 4px; }
.badge--outline { background: transparent; border: 1px solid var(--lam-border); color: var(--lam-text-m); }

/* ─── Misc ──────────────────────────────────────────────────── */
.btn-back { display: inline-block; margin-top: 1.5rem; font-size: .85rem; color: var(--lam-green); text-decoration: none; font-weight: 600; transition: color .2s; }
.btn-back:hover { color: var(--lam-gold); }
.empty-state { text-align: center; padding: 4rem 0; color: var(--lam-text-l); font-size: .95rem; }
.pagination-wrap { margin-top: 2rem; }

/* ─── Responsive ────────────────────────────────────────────── */
@media (max-width: 900px) {
  .detail-layout { grid-template-columns: 1fr; }
  .detail-layout__aside { order: -1; }
}
@media (max-width: 640px) {
  .section-pad { padding: 2.5rem 0; }
  .hero-mini { padding: 2.5rem 0 2rem; }
  .detail-card { padding: 1.25rem; }
}
</style>
