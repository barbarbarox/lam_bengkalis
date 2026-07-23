<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Pemeliharaan Cache Sistem</x-slot>
        <x-slot name="description">
            Gunakan tombol-tombol di bawah ini untuk membersihkan cache aplikasi jika Anda melakukan perubahan yang tidak langsung muncul (misalnya perubahan tampilan, pengaturan, atau konfigurasi). Hal ini sangat berguna pada hosting yang tidak memiliki akses terminal (SSH).
        </x-slot>

        @if($commandError)
            <div class="mt-4 p-4 border border-danger-500 bg-danger-50 dark:bg-danger-900/30 rounded-lg">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-danger-600 dark:text-danger-400 flex-shrink-0" />
                    <div>
                        <h3 class="font-bold text-danger-600 dark:text-danger-400 mb-1">Terjadi Kesalahan</h3>
                        <pre class="text-sm text-danger-700 dark:text-danger-300 whitespace-pre-wrap font-mono">{{ $commandError }}</pre>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            
            <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4">
                <div class="font-bold text-lg">Cache Aplikasi</div>
                <p class="text-sm text-gray-500 mb-4">Membersihkan cache data umum aplikasi.</p>
                <x-filament::button wire:click="clearCache" color="warning" icon="heroicon-o-trash">
                    Bersihkan Cache Aplikasi
                </x-filament::button>
            </x-filament::section>

            <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4">
                <div class="font-bold text-lg">Cache View</div>
                <p class="text-sm text-gray-500 mb-4">Membersihkan cache file tampilan/blade HTML.</p>
                <x-filament::button wire:click="clearView" color="warning" icon="heroicon-o-eye-slash">
                    Bersihkan Cache View
                </x-filament::button>
            </x-filament::section>

            <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4">
                <div class="font-bold text-lg">Cache Konfigurasi</div>
                <p class="text-sm text-gray-500 mb-4">Membersihkan cache file config dan env.</p>
                <x-filament::button wire:click="clearConfig" color="warning" icon="heroicon-o-cog">
                    Bersihkan Cache Config
                </x-filament::button>
            </x-filament::section>

            <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4">
                <div class="font-bold text-lg">Cache Route</div>
                <p class="text-sm text-gray-500 mb-4">Membersihkan cache rute URL.</p>
                <x-filament::button wire:click="clearRoute" color="warning" icon="heroicon-o-link">
                    Bersihkan Cache Route
                </x-filament::button>
            </x-filament::section>

            <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4 md:col-span-2 lg:col-span-2 border-danger-500">
                <div class="font-bold text-lg text-danger-600">Pembersihan Mendalam (Deep Clear)</div>
                <p class="text-sm text-gray-500 mb-4">Fitur ini dirancang khusus untuk mengatasi masalah di mana perubahan (menu, tampilan, kode) tidak muncul di server hosting. Fitur ini akan mereset PHP Opcache, cache Livewire, dan seluruh cache aplikasi sekaligus.</p>
                {{ $this->deepClearAction }}
            </x-filament::section>

        </div>

        <x-filament::section class="mt-8">
            <x-slot name="heading">Operasi Database</x-slot>
            <x-slot name="description">
                Perhatian: Tombol di bawah ini akan mengubah struktur dan data di dalam database. Gunakan hanya jika Anda tahu apa yang Anda lakukan atau setelah ada pembaruan fitur (update aplikasi).
            </x-slot>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4">
                    <div class="font-bold text-lg">Migrasi Database</div>
                    <p class="text-sm text-gray-500 mb-4">Menjalankan <code>php artisan migrate</code>. Berguna untuk menambahkan tabel atau kolom baru jika ada pembaruan.</p>
                    {{ $this->migrateAction }}
                </x-filament::section>

                <x-filament::section compact class="text-center flex flex-col items-center justify-center space-y-4 py-4">
                    <div class="font-bold text-lg">Seeder Database</div>
                    <p class="text-sm text-gray-500 mb-4">Menjalankan <code>php artisan db:seed</code>. Berguna untuk mengisi data awal (dummy data) atau data pengaturan default.</p>
                    {{ $this->seederAction }}
                </x-filament::section>
            </div>
        </x-filament::section>

        <x-filament::section class="mt-8">
            <x-slot name="heading">Informasi Langganan Berita (Newsletter)</x-slot>
            <x-slot name="description">
                Daftar email yang berlangganan berita dari situs web. Total pendaftar: <strong>{{ $totalSubscribers }}</strong> email.
            </x-slot>

            <div class="mt-4">
                @if($totalSubscribers > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($subscribers as $subscriber)
                            <div class="p-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                                <span>{{ $subscriber->email }}</span>
                                <span class="text-xs text-gray-500">{{ $subscriber->created_at->format('d M Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if($totalSubscribers > 50)
                        <p class="text-sm text-gray-500 mt-4 italic">* Menampilkan 50 email terbaru.</p>
                    @endif
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada yang berlangganan berita.</p>
                @endif
            </div>
        </x-filament::section>

    </x-filament::section>
</x-filament-panels::page>
