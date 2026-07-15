<x-filament-panels::page>
    @if($isAlreadyEnabled)
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">2FA Aktif!</span> Akun Anda telah dilindungi dengan Two-Factor Authentication.
        </div>
    @else
        <div class="fi-fo-component-ctn grid gap-6">
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-950 dark:text-white">Langkah 1: Scan QR Code</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Gunakan aplikasi Google Authenticator, Authy, atau aplikasi 2FA lainnya untuk memindai QR Code di bawah ini.
                    </p>
                    
                    <div class="mt-4 flex items-center justify-center p-4 bg-white rounded-lg border">
                        @if($qrCodeSvg)
                            {!! $qrCodeSvg !!}
                        @else
                            <div class="p-4 text-center text-gray-500">
                                <i>QR Code tidak dapat ditampilkan.</i><br>
                                Masukkan secret key ini secara manual:<br>
                                <strong class="text-lg text-gray-950 tracking-widest mt-2 block">{{ $secret }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-950 dark:text-white">Langkah 2: Simpan Recovery Codes</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Simpan kode pemulihan ini di tempat yang aman. Kode ini dapat digunakan untuk login jika Anda kehilangan akses ke aplikasi authenticator.
                    </p>
                    
                    <div class="mt-4 grid grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border dark:border-gray-700">
                        @foreach($recoveryCodes as $code)
                            <div class="font-mono text-sm font-medium tracking-widest text-center">{{ $code }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-950 dark:text-white">Langkah 3: Konfirmasi 2FA</h3>
                    <p class="mt-2 mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Setelah memindai QR code, masukkan 6 digit angka yang muncul pada aplikasi authenticator Anda untuk memverifikasi dan mengaktifkan 2FA.
                    </p>
                    
                    <x-filament-panels::form wire:submit="confirm">
                        {{ $this->form }}

                        <div class="mt-4">
                            <x-filament::button type="submit">
                                Verifikasi & Aktifkan 2FA
                            </x-filament::button>
                        </div>
                    </x-filament-panels::form>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
