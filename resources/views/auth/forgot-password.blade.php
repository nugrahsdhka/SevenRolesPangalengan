<x-guest-layout>
    <x-slot name="title">Lupa Password</x-slot>

    <div class="h-screen w-full bg-gradient-to-br from-red-50 via-white to-red-50 flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="w-full max-w-md relative z-10">
            <div class="rounded-3xl bg-white shadow-xl border border-gray-100 overflow-hidden flex flex-col">
                <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-8 text-center flex-shrink-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Lupa Password</h1>
                    <p class="text-red-100 text-xs max-w-sm mx-auto">
                        Masukkan email terdaftar untuk menerima link reset password.
                    </p>
                </div>

                <div class="px-6 pb-6 pt-5 flex-grow overflow-y-auto">
                    <x-auth-session-status class="mb-3" :status="session('status')" />

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                        @csrf

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

                        <button type="submit"
                            class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-lg shadow-red-500/30 hover:from-red-700 hover:to-red-800 transition-all">
                            Kirim Link Reset
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
        </div>
    </div>
</x-guest-layout>
