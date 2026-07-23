<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\Berita;
use App\Models\DokumenPeraturan;
use App\Models\PendidikanPelatihan;
use App\Models\SiteSetting;
use App\Models\TokohAdat;
use App\Models\HukumAdat;
use Illuminate\Http\Request;

class PencarianController extends Controller
{
    public function __invoke(Request $request)
    {
        $setting = SiteSetting::instance();
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return view('public.cari.index', [
                'q'       => $q,
                'setting' => $setting,
                'results' => collect(),
                'total'   => 0,
                'tabs'    => [],
            ]);
        }

        $like  = "%{$q}%";
        $limit = 8;

        // ── Berita ─────────────────────────────────────────────────────────────
        $berita = Berita::published()
            ->where(fn($b) => $b->where('judul', 'LIKE', $like)
                ->orWhere('excerpt', 'LIKE', $like))
            ->latest('tanggal_publish')
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'tipe'    => 'berita',
                'judul'   => $item->judul,
                'excerpt' => $item->excerpt,
                'url'     => route('berita.show', $item->slug),
                'tanggal' => $item->tanggal_publish?->translatedFormat('d M Y'),
                'badge'   => 'Berita',
                'warna'   => '#1B5E20',
            ]);

        // ── Agenda ─────────────────────────────────────────────────────────────
        $agenda = AgendaKegiatan::aktif()
            ->where(fn($q2) => $q2->where('judul', 'LIKE', $like)
                ->orWhere('lokasi', 'LIKE', $like)
                ->orWhere('deskripsi', 'LIKE', $like))
            ->orderBy('tanggal_mulai')
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'tipe'    => 'agenda',
                'judul'   => $item->judul,
                'excerpt' => $item->deskripsi,
                'url'     => route('agenda.show', $item->slug),
                'tanggal' => $item->tanggal_mulai?->translatedFormat('d M Y'),
                'badge'   => 'Agenda',
                'warna'   => '#1565C0',
            ]);

        // ── Tokoh Adat ─────────────────────────────────────────────────────────
        $tokoh = TokohAdat::aktif()
            ->where(fn($q2) => $q2->where('nama', 'LIKE', $like)
                ->orWhere('gelar_adat', 'LIKE', $like)
                ->orWhere('jabatan', 'LIKE', $like))
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'tipe'    => 'tokoh',
                'judul'   => ($item->gelar_adat ? $item->gelar_adat . ' ' : '') . $item->nama,
                'excerpt' => $item->ringkasan,
                'url'     => route('tokoh-adat.show', $item->slug),
                'tanggal' => null,
                'badge'   => 'Tokoh Adat',
                'warna'   => '#6A1B9A',
            ]);

        // ── Hukum Adat ─────────────────────────────────────────────────────────
        $hukum = HukumAdat::aktif()
            ->where(fn($q2) => $q2->where('judul', 'LIKE', $like)
                ->orWhere('ringkasan', 'LIKE', $like))
            ->latest('tahun')
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'tipe'    => 'hukum',
                'judul'   => $item->judul,
                'excerpt' => $item->ringkasan,
                'url'     => route('hukum-adat.show', $item->slug),
                'tanggal' => $item->tahun ? (string)$item->tahun : null,
                'badge'   => 'Hukum Adat',
                'warna'   => '#E65100',
            ]);

        // ── Dokumen ─────────────────────────────────────────────────────────────
        $dokumen = DokumenPeraturan::aktif()
            ->where(fn($q2) => $q2->where('judul', 'LIKE', $like)
                ->orWhere('deskripsi', 'LIKE', $like))
            ->latest('tahun')
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'tipe'    => 'dokumen',
                'judul'   => $item->judul,
                'excerpt' => $item->deskripsi,
                'url'     => route('dokumen.index') . '?q=' . urlencode($q),
                'tanggal' => $item->tahun ? (string)$item->tahun : null,
                'badge'   => 'Dokumen',
                'warna'   => '#B71C1C',
            ]);

        // ── Pendidikan ──────────────────────────────────────────────────────────
        $pendidikan = PendidikanPelatihan::aktif()
            ->where(fn($q2) => $q2->where('judul', 'LIKE', $like)
                ->orWhere('deskripsi', 'LIKE', $like))
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'tipe'    => 'pendidikan',
                'judul'   => $item->judul,
                'excerpt' => $item->deskripsi,
                'url'     => route('pendidikan.show', $item->slug),
                'tanggal' => $item->tanggal_mulai?->translatedFormat('d M Y'),
                'badge'   => 'Pendidikan',
                'warna'   => '#00695C',
            ]);

        // ── Merge & Tabs ──────────────────────────────────────────────────────
        $tabs = [
            'semua'     => ['label' => 'Semua',       'count' => 0, 'warna' => '#333'],
            'berita'    => ['label' => 'Berita',       'count' => $berita->count(),     'warna' => '#1B5E20'],
            'agenda'    => ['label' => 'Agenda',       'count' => $agenda->count(),     'warna' => '#1565C0'],
            'tokoh'     => ['label' => 'Tokoh Adat',   'count' => $tokoh->count(),      'warna' => '#6A1B9A'],
            'hukum'     => ['label' => 'Hukum Adat',   'count' => $hukum->count(),      'warna' => '#E65100'],
            'dokumen'   => ['label' => 'Dokumen',      'count' => $dokumen->count(),    'warna' => '#B71C1C'],
            'pendidikan'=> ['label' => 'Pendidikan',   'count' => $pendidikan->count(), 'warna' => '#00695C'],
        ];

        $results = [
            'berita'    => $berita,
            'agenda'    => $agenda,
            'tokoh'     => $tokoh,
            'hukum'     => $hukum,
            'dokumen'   => $dokumen,
            'pendidikan'=> $pendidikan,
        ];

        $total = collect($results)->sum(fn($r) => $r->count());
        $tabs['semua']['count'] = $total;

        return view('public.cari.index', compact('q', 'setting', 'results', 'total', 'tabs'));
    }
}
