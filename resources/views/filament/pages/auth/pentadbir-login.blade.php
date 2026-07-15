<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    {{-- reCAPTCHA v3 script --}}
    @if(config('services.recaptcha.site_key'))
    <script
        src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"
        async defer
        id="recaptchaScript"
    ></script>

    <script>
    (function () {
        var SITE_KEY = '{{ config('services.recaptcha.site_key') }}';
        var ACTION   = 'admin_login';

        function executeAndInject(callback) {
            if (typeof grecaptcha === 'undefined') {
                setTimeout(function () { executeAndInject(callback); }, 500);
                return;
            }

            grecaptcha.ready(function () {
                grecaptcha.execute(SITE_KEY, { action: ACTION })
                    .then(function (token) {
                        callback(token);
                    })
                    .catch(function () {
                        callback('');
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('submit', function (e) {
                var form = e.target;
                
                // Hanya intercept form di dalam panel login
                if (!form.closest('[wire\\:id]')) return;

                // MENCEGAH INFINITE LOOP
                if (form.dataset.recaptchaProcessed === 'true') {
                    // Reset flag agar submit berikutnya (misal karena error validasi)
                    // mengambil token baru yang fresh.
                    form.dataset.recaptchaProcessed = 'false';
                    return; // Biarkan form tersubmit ke Livewire
                }

                // Cegah submit sementara untuk mendapatkan token
                e.preventDefault();
                e.stopImmediatePropagation();

                executeAndInject(function (token) {
                    var wireEl = form.closest('[wire\\:id]') || form;
                    if (wireEl && window.Livewire) {
                        try {
                            Livewire.find(wireEl.getAttribute('wire:id'))
                                ?.set('recaptchaToken', token);
                        } catch (err) { /* silent */ }
                    }

                    var hiddenInput = form.querySelector('input[name="recaptcha_token"]');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'recaptcha_token';
                        form.appendChild(hiddenInput);
                    }
                    hiddenInput.value = token;

                    // Tandai bahwa form sudah diproses reCAPTCHA
                    form.dataset.recaptchaProcessed = 'true';

                    // Re-submit form (sekarang akan lolos dari pengecekan infinite loop di atas)
                    setTimeout(function () { 
                        form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true })); 
                    }, 100);
                });
            }, { capture: true, once: false });
        });
    })();
    </script>
    @endif
</x-filament-panels::page.simple>
