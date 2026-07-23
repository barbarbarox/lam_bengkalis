<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AgendaKegiatan;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AgendaKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $setting = SiteSetting::instance();
        $tab     = $request->get('tab', 'mendatang'); // mendatang | arsip
        $jenis   = $request->get('jenis');

        if ($tab === 'arsip') {
            $query = AgendaKegiatan::arsip();
        } else {
            $query = AgendaKegiatan::akanDatang();
        }

        if ($jenis && array_key_exists($jenis, AgendaKegiatan::JENIS)) {
            $query->where('jenis', $jenis);
        }

        $items = $query->paginate(12)->withQueryString();

        // Data Kalender: Ambil agenda aktif yang akan datang atau sedang berlangsung
        $calendarAgendas = AgendaKegiatan::aktif()
                            ->where('status', '!=', 'dibatalkan')
                            ->get(['judul', 'slug', 'tanggal_mulai', 'status']);
                            
        $calendarData = [];
        foreach ($calendarAgendas as $ag) {
            if ($ag->tanggal_mulai) {
                $dateStr = $ag->tanggal_mulai->format('Y-m-d');
                $calendarData[$dateStr][] = [
                    'title'  => $ag->judul,
                    'url'    => route('agenda.show', $ag->slug),
                    'status' => $ag->status
                ];
            }
        }
        $calendarDataJson = json_encode($calendarData);

        return view('public.agenda.index', compact('setting', 'items', 'tab', 'jenis', 'calendarDataJson'));
    }

    public function show(string $slug)
    {
        $setting = SiteSetting::instance();
        $item    = AgendaKegiatan::aktif()->where('slug', $slug)->firstOrFail();
        $lainnya = AgendaKegiatan::akanDatang()->where('id', '!=', $item->id)->limit(3)->get();

        return view('public.agenda.show', compact('setting', 'item', 'lainnya'));
    }
}
