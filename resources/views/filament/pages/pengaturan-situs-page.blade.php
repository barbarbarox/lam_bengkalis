<x-filament-panels::page>
    {{-- Identitas Lembaga --}}
    <x-filament::section>
        <x-slot name="heading">Identitas Lembaga</x-slot>
        <x-slot name="description">Nama resmi, singkatan, logo, dan favicon situs.</x-slot>

        {{ $this->identitasForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveIdentitas" icon="heroicon-o-check" color="primary">
                Simpan Identitas
            </x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- SEO Global --}}
    <x-filament::section>
        <x-slot name="heading">SEO Global</x-slot>
        <x-slot name="description">Meta description default yang ditampilkan di hasil pencarian.</x-slot>

        {{ $this->seoForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveSeo" icon="heroicon-o-check" color="primary">
                Simpan SEO
            </x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Media Sosial & URL Eksternal --}}
    <x-filament::section>
        <x-slot name="heading">Media Sosial dan Tautan Eksternal</x-slot>
        <x-slot name="description">Tautan media sosial resmi dan URL Jejak Layar (Museum Digital).</x-slot>

        {{ $this->sosialMediaForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveSosialMedia" icon="heroicon-o-check" color="primary">
                Simpan Media Sosial
            </x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Background Hero Per Halaman --}}
    <x-filament::section>
        <x-slot name="heading">Background Hero Per Halaman</x-slot>
        <x-slot name="description">Gambar latar untuk header halaman Profil, Berita, dan Kontak — dengan gradasi emas di tepi.</x-slot>

        {{ $this->heroForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveHero" icon="heroicon-o-check" color="primary">
                Simpan Hero Background
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>

