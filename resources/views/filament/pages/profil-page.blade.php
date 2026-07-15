<x-filament-panels::page>
    {{-- Sejarah --}}
    <x-filament::section>
        <x-slot name="heading">Sejarah LAM Bengkalis</x-slot>
        {{ $this->sejarahForm }}
        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveSejarah" icon="heroicon-o-check" color="primary">Simpan Sejarah</x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Visi & Misi --}}
    <x-filament::section>
        <x-slot name="heading">Visi dan Misi</x-slot>
        {{ $this->visiMisiForm }}
        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveVisiMisi" icon="heroicon-o-check" color="primary">Simpan Visi dan Misi</x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Tugas & Fungsi --}}
    <x-filament::section>
        <x-slot name="heading">Tugas dan Fungsi</x-slot>
        {{ $this->tugasFungsiForm }}
        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveTugasFungsi" icon="heroicon-o-check" color="primary">Simpan Tugas dan Fungsi</x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Dasar Hukum --}}
    <x-filament::section>
        <x-slot name="heading">Dasar Hukum</x-slot>
        {{ $this->dasarHukumForm }}
        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveDasarHukum" icon="heroicon-o-check" color="primary">Simpan Dasar Hukum</x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Link ke Struktur Organisasi Resource --}}
    <x-filament::section>
        <x-slot name="heading">Struktur Organisasi</x-slot>
        <x-slot name="description">Kelola data anggota MKA dan DPH LAM Bengkalis.</x-slot>
        <x-filament::button
            tag="a"
            href="{{ route('filament.pentadbir.resources.struktur-organisasis.index') }}"
            icon="heroicon-o-user-group"
            color="gray"
        >
            Kelola Struktur Organisasi
        </x-filament::button>
    </x-filament::section>
</x-filament-panels::page>
