<?php

namespace App\Observers;

use App\Models\StrukturOrganisasi;
use Illuminate\Support\Facades\Cache;

/**
 * Observer untuk model StrukturOrganisasi.
 *
 * Tugasnya: invalidasi cache query publik setiap kali ada perubahan data
 * (simpan/hapus) melalui panel Filament maupun kode lainnya.
 *
 * Cache key yang dipakai di ProfilController: 'publik.struktur_organisasi'
 * TTL: 1 jam (3600 detik) — didefinisikan di ProfilController.
 */
class StrukturOrganisasiObserver
{
    /**
     * Handle the StrukturOrganisasi "created" event.
     */
    public function created(StrukturOrganisasi $model): void
    {
        $this->clearCache();
    }

    /**
     * Handle the StrukturOrganisasi "updated" event.
     */
    public function updated(StrukturOrganisasi $model): void
    {
        $this->clearCache();
    }

    /**
     * Handle the StrukturOrganisasi "deleted" event.
     */
    public function deleted(StrukturOrganisasi $model): void
    {
        $this->clearCache();
    }

    /**
     * Handle the StrukturOrganisasi "restored" event (jika SoftDelete dipakai di masa depan).
     */
    public function restored(StrukturOrganisasi $model): void
    {
        $this->clearCache();
    }

    /**
     * Hapus semua cache key yang berhubungan dengan data struktur publik.
     */
    private function clearCache(): void
    {
        Cache::forget('publik.struktur.mka');
        Cache::forget('publik.struktur.dph');
        Cache::forget('publik.struktur.bidang');
        Cache::forget('publik.struktur.dka');
        // Legacy key (jika masih ada)
        Cache::forget('publik.struktur_organisasi');
    }
}
