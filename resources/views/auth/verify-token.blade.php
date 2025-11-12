<x-guest-layout>
    <x-slot name="title">
        Verifikasi Token
    </x-slot>

    <div
        class="h-screen w-full bg-gradient-to-br from-red-50 via-white to-red-50 flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-red-100 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-red-200 rounded-full opacity-20 blur-3xl"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            {{-- Main Card --}}
            <div class="rounded-3xl bg-white shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                {{-- Header --}}
                <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-8 text-center flex-shrink-0">
                    <div class="relative">
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Verifikasi Token</h1>
                        <p class="text-red-100 text-xs sm:text-sm max-w-sm mx-auto">
                            Kami telah mengirimkan token verifikasi ke email:
                        </p>
                        <p
                            class="mt-1 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-[11px] font-medium text-red-50 border border-white/20">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            <span class="font-semibold truncate max-w-[160px] sm:max-w-xs">{{ $email }}</span>
                        </p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="px-6 py-6 flex-grow overflow-y-auto">
                    @if (session('status'))
                        <div
                            class="mb-3 rounded-xl bg-green-50 border border-green-200 px-4 py-2 text-xs text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @php
                        $isExpired = $expired || session('expired');
                        // sisa detik (opsional dari controller), fallback 180 detik (3 menit)
                        $secondsRemaining = $secondsRemaining ?? 180;
                    @endphp

                    @if (!$isExpired)
                        {{-- Form Input Token --}}
                        <form id="tokenForm" method="POST" action="{{ route('verification.token.verify') }}"
                            class="space-y-4">
                            @csrf

                            <input type="hidden" name="email" value="{{ $email }}">

                            <div>
                                <label for="token" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Token Verifikasi
                                </label>
                                <input id="token" name="token" type="text" maxlength="6" required autofocus
                                    placeholder="••••••"
                                    class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 py-3 px-4 text-gray-900 text-center tracking-[0.4em] text-lg focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20 uppercase" />
                                <x-input-error :messages="$errors->get('token')" class="mt-2" />
                            </div>

                            {{-- Countdown --}}
                            <div id="countdown" data-seconds="{{ $secondsRemaining }}"
                                class="text-xs text-gray-500 mt-1">
                                {{-- akan diisi oleh JS --}}
                            </div>

                            {{-- Info --}}
                            <div
                                class="mt-3 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-3 border border-red-100">
                                <div class="flex gap-2">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <svg class="h-4 w-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-700 leading-snug">
                                        Token hanya berlaku dalam waktu singkat. Jika waktu habis atau token tidak
                                        valid, kirim ulang token verifikasi.
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="pt-4 space-y-3">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verifikasi
                                </button>

                                {{-- Form Kirim Ulang Token --}}
                                <form method="POST" action="{{ route('verification.token.resend') }}"
                                    class="flex items-center justify-between gap-3">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $email }}">

                                    <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-gray-700">
                                        ← Kembali ke Login
                                    </a>

                                    <button type="submit"
                                        class="text-xs font-medium text-red-600 hover:text-red-700 underline">
                                        Kirim ulang token
                                    </button>
                                </form>
                            </div>
                        </form>
                    @else
                        {{-- Token Expired --}}
                        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-xs text-red-700">
                            Token sudah kadaluarsa. Silakan kirim ulang token verifikasi.
                        </div>

                        <form method="POST" action="{{ route('verification.token.resend') }}" class="mt-4 space-y-3">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <a href="{{ route('login') }}" class="block text-xs text-gray-500 hover:text-gray-700">
                                ← Kembali ke Login
                            </a>

                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m0 0A6.002 6.002 0 0012 17h0a6.002 6.002 0 005.418-8H20V4m-9 9v-3m0 0V8m0 2h2m-2 0H9" />
                                </svg>
                                Kirim Ulang Token
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Footer Text --}}
            <p class="mt-4 text-center text-[10px] text-gray-500 leading-tight">
                Pastikan email yang digunakan adalah email yang aktif dan dapat diakses
            </p>
        </div>
    </div>

    @if (!($expired || session('expired')))
        {{-- Countdown Script --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('countdown');
                if (!el) return;

                let seconds = parseInt(el.dataset.seconds || '180', 10);
                const pad = (n) => n.toString().padStart(2, '0');

                const update = () => {
                    if (isNaN(seconds)) return;

                    if (seconds <= 0) {
                        el.textContent =
                        'Token mungkin sudah kadaluarsa. Jika verifikasi gagal, kirim ulang token.';
                        return;
                    }

                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    el.textContent = 'Token akan kadaluarsa dalam ' + pad(m) + ':' + pad(s);
                    seconds--;
                };

                update();
                setInterval(update, 1000);
            });
        </script>
    @endif
</x-guest-layout>
