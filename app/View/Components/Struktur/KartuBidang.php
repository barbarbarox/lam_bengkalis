<?php

namespace App\View\Components\Struktur;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class KartuBidang extends Component
{
    /**
     * @param  string                $namaBidang  Nama bidang, contoh: "Bidang Organisasi Dan Tata Laksana"
     * @param  mixed|null            $pimpinan    Instance StrukturOrganisasi pimpinan (Penyelaras), atau null
     * @param  Collection            $anggotaLain Koleksi anggota biasa (tidak termasuk pimpinan)
     */
    public function __construct(
        public string $namaBidang,
        public mixed $pimpinan,
        public Collection $anggotaLain,
    ) {}

    public function render()
    {
        return view('components.struktur.kartu-bidang');
    }
}
