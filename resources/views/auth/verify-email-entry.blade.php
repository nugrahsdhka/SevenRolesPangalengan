<x-guest-layout>
    <x-slot name="title">
        Verifikasi Email
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
                {{-- Header --}}
                <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-8 text-center flex-shrink-0">
                    <div class="relative">
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Verifikasi Email</h1>
                        <p class="text-red-100 text-xs sm:text-sm max-w-sm mx-auto">
                            Rumah Stunting Pangalengan
                        </p>
                        <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-[11px] font-medium text-red-50 border border-white/20">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                            Tahap Verifikasi Akun
                        </div>
                    </div>
                </div>

                {{-- Form Section --}}
                <div class="px-6 pb-6 flex-grow overflow-y-auto">
                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-2" :status="session('status')" />

                    <form method="POST" action="{{ route('verification.entry.store') }}" class="space-y-4">
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
                                    autocomplete="email"
                                    placeholder="email@example.com"
                                    class="block w-full pl-11 pr-4 py-2.75 rounded-xl border border-gray-300 bg-white text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"
                                />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </div>

                        {{-- Info Box --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-3.5 border border-blue-100">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-4.5 w-4.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-700 leading-snug">
                                    Masukkan email yang Anda gunakan saat registrasi. Sistem akan mengarahkan Anda ke halaman yang sesuai untuk verifikasi token atau melengkapi registrasi.
                                </p>
                            </div>
                        </div>

                        {{-- Button --}}
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Lanjutkan Verifikasi
                        </button>

                        {{-- Back to Login --}}
                        <a href="{{ route('login') }}"
                            class="w-full flex items-center justify-center gap-2 px-6 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Login
                        </a>
                    </form>
                </div>
            </div>

            <p class="mt-4 text-center text-[10px] text-gray-500 leading-tight">
                Tahap verifikasi diperlukan untuk mengaktifkan akun Rumah Stunting Pangalengan Anda.
            </p>
        </div>
    </div>
</x-guest-layout>
