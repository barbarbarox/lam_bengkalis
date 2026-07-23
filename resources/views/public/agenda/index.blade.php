@extends('layouts.app')
@section('title', __('ui.agenda_title') . ' — ' . ($setting->singkatan ?? 'LAMR Bengkalis'))
@section('content')

@include('public.partials._hero-mini', [
    'halaman'  => 'agenda',
    'gradient' => '#1565C0 0%, #0d47a1',
    'title'    => __('ui.agenda_title'),
    'crumbs'   => [
        ['label' => 'Beranda', 'url' => route('beranda')],
        ['label' => 'Agenda Kegiatan'],
    ]
])

<section class="section-pad">
  <div class="container">

    {{-- Tabs: Mendatang / Arsip ─── --}}
    <div class="agenda-layout">
      
      <div class="agenda-main">
        <div class="agenda-tabs">
      <a href="{{ route('agenda.index') }}" class="agenda-tab {{ $tab !== 'arsip' ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Akan Datang & Berlangsung
      </a>
      <a href="{{ route('agenda.index') }}?tab=arsip" class="agenda-tab {{ $tab === 'arsip' ? 'is-active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
        Arsip Kegiatan
      </a>
    </div>

    @if($items->count() > 0)
      <div class="agenda-list">
        @foreach($items as $item)
          <a href="{{ route('agenda.show', $item->slug) }}" class="agenda-item-card">
            {{-- Tanggal ── --}}
            <div class="agenda-item-card__date">
              <span class="agenda-item-card__day">{{ $item->tanggal_mulai->format('d') }}</span>
              <span class="agenda-item-card__month">{{ strtoupper($item->tanggal_mulai->translatedFormat('M Y')) }}</span>
            </div>

            {{-- Thumbnail ── --}}
            <div class="agenda-item-card__thumb">
              @if($item->thumbnail)
                <img src="{{ Storage::url($item->thumbnail) }}" alt="{{ $item->judul }}" loading="lazy">
              @else
                <div class="agenda-item-card__thumb-ph" style="background: {{ $item->warna_badge }};"></div>
              @endif
            </div>

            {{-- Info ── --}}
            <div class="agenda-item-card__body">
              <div class="agenda-item-card__badges">
                <span class="badge" style="background: {{ $item->warna_badge }}; color: white;">{{ $item->label_status }}</span>
                <span class="badge badge--outline">{{ $item->label_jenis }}</span>
              </div>
              <h2 class="agenda-item-card__title">{{ $item->judul }}</h2>
              <div class="agenda-item-card__meta">
                @if($item->lokasi)
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ $item->lokasi }}
                  </span>
                @endif
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  {{ $item->rentang_tanggal }}
                  @if($item->waktu_mulai) · {{ \Carbon\Carbon::parse($item->waktu_mulai)->format('H:i') }} WIB @endif
                </span>
              </div>
              @if($item->deskripsi)
                <p class="agenda-item-card__desc">{{ Str::limit($item->deskripsi, 130) }}</p>
              @endif
            </div>
            <div class="agenda-item-card__arrow">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </div>
          </a>
        @endforeach
      </div>

      <div class="pagination-wrap">
        {{ $items->links() }}
      </div>
    @else
      <div class="empty-state" style="padding: 5rem 0; text-align:center; color: var(--lam-text-l);">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" stroke="var(--lam-border)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <p style="margin-top:1rem;">{{ $tab === 'arsip' ? 'Belum ada arsip kegiatan.' : 'Belum ada agenda kegiatan mendatang.' }}</p>
      </div>
        @endif
      </div>
      
      <div class="agenda-sidebar">
        <div class="calendar-widget">
          <div class="calendar-header">
            <button id="cal-prev" title="Bulan Sebelumnya">&lsaquo;</button>
            <h3 id="cal-month-year">Bulan Tahun</h3>
            <button id="cal-next" title="Bulan Berikutnya">&rsaquo;</button>
          </div>
          <div class="calendar-days">
            <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
          </div>
          <div class="calendar-grid" id="cal-grid"></div>
        </div>
      </div>
      
    </div>
  </div>
</section>

<style>
.agenda-tabs {
  display: flex; gap: .75rem; margin-bottom: 2rem; flex-wrap: wrap;
}
.agenda-tab {
  display: inline-flex; align-items: center; gap: .5rem;
  padding: .65rem 1.25rem;
  border: 1.5px solid var(--lam-border);
  border-radius: 50px;
  font-size: .85rem; font-weight: 600;
  color: var(--lam-text-m);
  text-decoration: none;
  transition: all .2s;
}
.agenda-tab.is-active, .agenda-tab:hover {
  background: var(--lam-green);
  border-color: var(--lam-green);
  color: white;
}

.agenda-list { display: flex; flex-direction: column; gap: 1rem; }

.agenda-item-card {
  display: grid;
  grid-template-columns: 80px 140px 1fr 30px;
  align-items: center;
  gap: 1.25rem;
  padding: 1.25rem;
  background: var(--lam-bg-alt);
  border: 1px solid var(--lam-border);
  border-radius: var(--radius);
  text-decoration: none;
  transition: border-color .2s, box-shadow .2s, transform .15s;
}
.agenda-item-card:hover {
  border-color: var(--lam-green);
  box-shadow: 0 8px 24px rgba(27,94,32,.1);
  transform: translateX(4px);
}
.agenda-item-card__date {
  display: flex; flex-direction: column; align-items: center;
  background: var(--lam-green);
  border-radius: 10px;
  padding: .75rem .5rem;
  flex-shrink: 0;
}
.agenda-item-card__day {
  font-size: 2rem; font-weight: 800; color: white; line-height: 1;
  font-family: var(--font-head);
}
.agenda-item-card__month {
  font-size: .62rem; font-weight: 700; color: rgba(255,255,255,.8);
  letter-spacing: .05em; text-align: center; line-height: 1.3;
}
.agenda-item-card__thumb {
  width: 140px; height: 85px; border-radius: 8px; overflow: hidden; flex-shrink: 0;
}
.agenda-item-card__thumb img { width: 100%; height: 100%; object-fit: cover; }
.agenda-item-card__thumb-ph { width: 100%; height: 100%; opacity: .7; }

.agenda-item-card__body { flex: 1; min-width: 0; }
.agenda-item-card__badges { display: flex; gap: .4rem; flex-wrap: wrap; margin-bottom: .5rem; }
.badge {
  display: inline-block; font-size: .68rem; font-weight: 700;
  padding: .2rem .55rem; border-radius: 4px;
}
.badge--outline { background: transparent; border: 1px solid var(--lam-border); color: var(--lam-text-m); }
.agenda-item-card__title {
  font-size: 1rem; font-weight: 700; color: var(--lam-text);
  line-height: 1.35; margin: 0 0 .5rem;
  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  transition: color .2s;
}
.agenda-item-card:hover .agenda-item-card__title { color: var(--lam-green); }
.agenda-item-card__meta {
  display: flex; flex-wrap: wrap; gap: .5rem .85rem; font-size: .78rem; color: var(--lam-text-l);
  margin-bottom: .35rem;
}
.agenda-item-card__meta span { display: flex; align-items: center; gap: .3rem; }
.agenda-item-card__desc { font-size: .82rem; color: var(--lam-text-m); line-height: 1.55; margin: 0; }
.agenda-item-card__arrow { color: var(--lam-green); opacity: 0; transition: opacity .2s; flex-shrink: 0; }
.agenda-item-card:hover .agenda-item-card__arrow { opacity: 1; }

.pagination-wrap { margin-top: 2rem; }

@media (max-width: 768px) {
  .agenda-item-card { grid-template-columns: 60px 1fr 20px; }
  .agenda-item-card__thumb { display: none; }
  .agenda-item-card__day { font-size: 1.5rem; }
}
@media (max-width: 480px) {
  .agenda-item-card { grid-template-columns: 1fr; gap: .75rem; }
  .agenda-item-card__date { flex-direction: row; gap: .5rem; padding: .5rem .75rem; width: fit-content; }
  .agenda-item-card__arrow { display: none; }
}

/* Kalender Widget */
.agenda-layout { display: flex; gap: 2.5rem; align-items: flex-start; }
.agenda-main { flex: 1; min-width: 0; }
.agenda-sidebar { width: 320px; flex-shrink: 0; position: sticky; top: 100px; }

.calendar-widget {
  background: white; border: 1px solid var(--lam-border); border-radius: 12px; padding: 1.5rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.04);
}
.calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; }
.calendar-header h3 { margin: 0; font-size: 1.05rem; font-weight: 800; color: var(--lam-text); font-family: var(--font-head); letter-spacing: .02em; }
.calendar-header button { 
  background: none; border: 1px solid var(--lam-border); border-radius: 50%;
  width: 32px; height: 32px; cursor: pointer; font-size: 1.2rem;
  display: flex; align-items: center; justify-content: center;
  transition: .2s; color: var(--lam-text-m);
}
.calendar-header button:hover { background: var(--lam-green); color: white; border-color: var(--lam-green); }
.calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: .75rem; font-weight: 700; color: var(--lam-text-l); margin-bottom: .5rem; }
.calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
.cal-cell { 
  aspect-ratio: 1; display: flex; align-items: center; justify-content: center; 
  font-size: .85rem; font-weight: 600; border-radius: 50%; color: var(--lam-text);
  position: relative; cursor: pointer; transition: .2s; border: 1px solid transparent; text-decoration: none;
}
.cal-cell:hover { background: var(--lam-bg-alt); }
.cal-cell.is-empty { visibility: hidden; }
.cal-cell.is-today { background: #E8F5E9; color: var(--lam-green); font-weight: 700; }
.cal-cell.has-event { border-color: var(--lam-green); color: var(--lam-green); font-weight: 700; }
.cal-cell.has-event::after {
  content: ""; position: absolute; bottom: 4px; left: 50%; transform: translateX(-50%);
  width: 4px; height: 4px; border-radius: 50%; background: var(--lam-green);
}
.cal-cell.has-event:hover { background: var(--lam-green); color: white; }
.cal-cell.has-event:hover::after { background: white; }

@media (max-width: 992px) {
  .agenda-layout { flex-direction: column-reverse; }
  .agenda-sidebar { width: 100%; position: static; }
  .calendar-widget { max-width: 400px; margin: 0 auto; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const eventData = {!! $calendarDataJson ?? '{}' !!};
  const grid = document.getElementById('cal-grid');
  const monthYearLabel = document.getElementById('cal-month-year');
  const btnPrev = document.getElementById('cal-prev');
  const btnNext = document.getElementById('cal-next');

  let currentDate = new Date();
  
  function renderCalendar(date) {
    grid.innerHTML = '';
    const year = date.getFullYear();
    const month = date.getMonth();
    
    const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    monthYearLabel.textContent = `${monthNames[month]} ${year}`;
    
    const firstDayIndex = new Date(year, month, 1).getDay(); 
    const lastDay = new Date(year, month + 1, 0).getDate();
    const today = new Date();
    
    // cell kosong awal
    for (let i = 0; i < firstDayIndex; i++) {
      let empty = document.createElement('div');
      empty.className = 'cal-cell is-empty';
      grid.appendChild(empty);
    }
    
    for (let i = 1; i <= lastDay; i++) {
      let cell = document.createElement('a');
      cell.className = 'cal-cell';
      cell.textContent = i;
      
      let dateStr = `${year}-${String(month+1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
      
      if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
        cell.classList.add('is-today');
      }
      
      if (eventData[dateStr]) {
        cell.classList.add('has-event');
        cell.title = eventData[dateStr].map(e => e.title).join(' | ');
        cell.href = eventData[dateStr][0].url; 
      } else {
        cell.href = "javascript:void(0)";
        cell.style.cursor = "default";
      }
      
      grid.appendChild(cell);
    }
  }
  
  renderCalendar(currentDate);
  
  btnPrev.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  });
  
  btnNext.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  });
});
</script>
@endsection
