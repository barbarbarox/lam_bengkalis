<x-filament-panels::page>
    {{-- ── Selayang Pandang ───────────────────────────────────────────────────────── --}}
    <x-filament::section>
        <x-slot name="heading">Selayang Pandang & Balai Adat</x-slot>
        <x-slot name="description">Tentang LAMR Bengkalis dan foto hero.</x-slot>

        {{ $this->selayangPandangForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveSelayangPandang" icon="heroicon-o-check" color="primary">
                Simpan Selayang Pandang
            </x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- ── Background Carousel ────────────────────────────────────────────────── --}}
    <x-filament::section>
        <x-slot name="heading">Background Carousel</x-slot>
        <x-slot name="description">Gambar latar berputar. Drag untuk mengubah urutan.</x-slot>

        {{ $this->slidesForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveSlides" icon="heroicon-o-check" color="primary">
                Simpan Slides
            </x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- ── Banner Iklan ────────────────────────────────────────────────────────── --}}
    <x-filament::section>
        <x-slot name="heading">Banner Iklan</x-slot>
        <x-slot name="description">Banner dengan jadwal tayang opsional.</x-slot>

        {{ $this->bannersForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveBanners" icon="heroicon-o-check" color="primary">
                Simpan Banner
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>
