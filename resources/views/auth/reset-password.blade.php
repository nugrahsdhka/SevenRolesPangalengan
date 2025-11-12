<x-guest-layout>
    <x-slot name="title">
        Reset Password
    </x-slot>

    <div class="h-screen w-full bg-gradient-to-br from-red-50 via-white to-red-50 flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-red-100 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-red-200 rounded-full opacity-20 blur-3xl"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <div class="rounded-3xl bg-white shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-8 text-center flex-shrink-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Buat Password Baru</h1>
                    <p class="text-red-100 text-xs sm:text-sm max-w-sm mx-auto">
                        Silakan setel password baru untuk akun Anda.
                    </p>
                </div>

                <div class="px-6 pb-6 pt-5 flex-grow overflow-y-auto">
                    <x-auth-session-status class="mb-3" :status="session('status')" />

                    @if ($errors->any())
                        <div class="mb-3 text-xs text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Email
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
                                    value="{{ $email ?? old('email') }}"
                                    required
                                    class="block w-full pl-11 pr-4 py-2.75 rounded-xl border border-gray-300 bg-white text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        {{-- Password baru (dengan toggle) --}}
                        <div x-data="{ show: false }">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Password baru
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4 9 5.567 9 7.5 10.343 11 12 11zm0 0v1m-6 4h12a2 2 0 002-2v-1a7 7 0 10-14 0v1a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <input
                                    id="password"
                                    :type="show ? 'text' : 'password'"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="••••••••"
                                    class="block w-full pl-11 pr-11 py-2.75 rounded-xl border border-gray-300 bg-white text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                />

                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                    title="Tampilkan/Sembunyikan password"
                                >
                                    {{-- icon eye --}}
                                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0s-3-7-9-7-9 7-9 7 3 7 9 7 9-7 9-7z"/>
                                    </svg>
                                    {{-- icon eye-off --}}
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13.875 18.825A10.05 10.05 0 0112 19c-6 0-9-7-9-7a17.4 17.4 0 013.274-4.323M6.223 6.223C8.043 4.839 9.996 4 12 4c6 0 9 7 9 7a17.4 17.4 0 01-3.38 4.466M3 3l18 18M9.88 9.88A3 3 0 0014.12 14.12"/>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                        </div>

                        {{-- Konfirmasi password baru (dengan toggle) --}}
                        <div x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Konfirmasi password baru
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.567 3-3.5S13.657 4 12 4 9 5.567 9 7.5 10.343 11 12 11zm0 0v1m-6 4h12a2 2 0 002-2v-1a7 7 0 10-14 0v1a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <input
                                    id="password_confirmation"
                                    :type="show ? 'text' : 'password'"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="••••••••"
                                    class="block w-full pl-11 pr-11 py-2.75 rounded-xl border border-gray-300 bg-white text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                />

                                <button
                                    type="button"
                                    @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                    title="Tampilkan/Sembunyikan konfirmasi"
                                >
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
                        </div>

                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                            Simpan Password Baru
                        </button>

                        <a href="{{ route('login') }}"
                            class="w-full flex items-center justify-center gap-2 px-6 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali Ke Login
                        </a>
                    </form>
                </div>
            </div>

            <p class="mt-4 text-center text-[10px] text-gray-500 leading-tight">
                Setelah password diubah, gunakan password baru untuk login ke dashboard.
            </p>
        </div>
    </div>
</x-guest-layout>
