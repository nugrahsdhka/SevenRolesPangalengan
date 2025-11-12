<x-guest-layout>
    <x-slot name="title">
        Login
    </x-slot>

    <div class="h-screen w-full bg-gradient-to-br from-red-50 via-white to-red-50 flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-red-100 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-red-200 rounded-full opacity-20 blur-3xl"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            {{-- Main Card --}}
            <div class="rounded-3xl bg-white shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                {{-- Header Section (mirip complete-registration) --}}
                <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-8 text-center flex-shrink-0">
                    <div class="relative">
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Login Access</h1>
                        <p class="text-red-100 text-xs sm:text-sm max-w-sm mx-auto">
                            Rumah Stunting Pangalengan
                        </p>
                        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-[11px] font-medium text-red-50 border border-white/20">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Akses Terbatas • Admin Only
                        </div>
                    </div>
                </div>

                {{-- Form Section (scrollable kalau layar kecil) --}}
                <div class="px-6 pb-6 flex-grow overflow-y-auto">
                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-2" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="admin@example.com"
                                    class="block w-full pl-11 pr-4 py-2.75 rounded-xl border border-gray-300 bg-white text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        {{-- Password --}}
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Password <span class="text-red-600">*</span>
                            </label>

                            <div class="relative">
                                {{-- Icon kiri --}}
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>

                                {{-- Input password, type dinamis --}}
                                <input
                                    id="password"
                                    :type="show ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="block w-full pl-11 pr-11 py-2.75 rounded-xl border border-gray-300 bg-white text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                />

                                {{-- Tombol show/hide (kanan) --}}
                                <button
                                    type="button"
                                    @click="show = !show"
                                    @keydown.enter.prevent="show = !show"
                                    :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                    title="Tampilkan/Sembunyikan password"
                                >
                                    {{-- eye / eye-off --}}
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0s-3-7-9-7-9 7-9 7 3 7 9 7 9-7 9-7z"/>
                                    </svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-6 0-9-7-9-7a17.4 17.4 0 013.274-4.323M6.223 6.223C8.043 4.839 9.996 4 12 4c6 0 9 7 9 7a17.4 17.4 0 01-3.38 4.466M3 3l18 18M9.88 9.88A3 3 0 0014.12 14.12"/>
                                    </svg>
                                </button>
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        {{-- Remember Me + Lupa Password + Verifikasi Email --}}
                        <div class="flex items-center justify-between text-xs">
                            <label class="inline-flex items-center gap-2 text-gray-600">
                                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500">
                                <span>Ingat saya</span>
                            </label>

                            <div class="flex items-center gap-3">
                                @if (Route::has('password.request'))
                                    <a class="text-red-600 hover:text-red-700 font-medium" href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                @endif

                                @if (Route::has('verification.entry.show'))
                                    <span class="text-gray-300">|</span>
                                    <a class="text-blue-600 hover:text-blue-700 font-medium" href="{{ route('verification.entry.show') }}">
                                        Verifikasi email
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Login Button --}}
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Log in
                        </button>

                        {{-- Back to Home --}}
                        <a href="{{ route('welcome') }}"
                            class="w-full flex items-center justify-center gap-2 px-6 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </form>
                </div>
            </div>

            {{-- Footer Text --}}
            <p class="mt-4 text-center text-[10px] text-gray-500 leading-tight">
                Akses hanya untuk akun yang telah diberikan hak oleh pengelola Rumah Stunting Pangalengan
            </p>
        </div>
    </div>
</x-guest-layout>
