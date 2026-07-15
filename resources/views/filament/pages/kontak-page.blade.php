<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Informasi Kontak Situs</x-slot>
        <x-slot name="description">Data alamat, email, dan telepon yang tampil di halaman publik.</x-slot>

        {{ $this->kontakForm }}

        <div class="mt-4 flex justify-end">
            <x-filament::button wire:click="saveKontak" icon="heroicon-o-check" color="primary">
                Simpan Kontak
            </x-filament::button>
        </div>
    </x-filament::section>

    <div class="mt-6"></div>

    {{-- Link ke Resource Aduan Masuk --}}
    <x-filament::section>
        <x-slot name="heading">Aduan Masuk</x-slot>
        <x-slot name="description">Kelola pesan dan pengaduan yang dikirim oleh masyarakat melalui formulir publik.</x-slot>
        <x-filament::button
            tag="a"
            href="{{ route('filament.pentadbir.resources.kontak-aduans.index') }}"
            icon="heroicon-o-inbox-stack"
            color="gray"
        >
            Buka Daftar Aduan Masuk
        </x-filament::button>
    </x-filament::section>
</x-filament-panels::page>
