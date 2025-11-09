<x-guest-layout>
    <x-slot name="title">
        Lengkapi Registrasi
    </x-slot>

    <div class="h-screen w-full bg-gradient-to-br from-red-50 via-white to-red-50 flex items-center justify-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-red-100 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-red-200 rounded-full opacity-20 blur-3xl"></div>
        </div>

        {{-- Main Card (scrollable content only) --}}
        <div class="w-full max-w-md h-[90vh] bg-white rounded-3xl shadow-xl border border-gray-100 flex flex-col overflow-hidden relative z-10">
            {{-- Header --}}
            <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-8 text-center flex-shrink-0">
                <div class="relative">
                    <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Lengkapi Profil Anda</h1>
                    <p class="text-red-100 text-xs sm:text-sm max-w-sm mx-auto">
                        Beberapa langkah lagi untuk mengaktifkan akun Anda
                    </p>
                </div>
            </div>

            {{-- Scrollable Form --}}
            <div class="flex-grow overflow-y-auto px-6 py-6">
                <form method="POST" action="{{ route('register.complete.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input class="w-full rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm py-2.5 px-3 cursor-not-allowed" value="{{ $user->email }}" disabled />
                    </div>

                    {{-- Role --}}
                    @php
                        $roleLabel = $user->roles->pluck('name')->join(', ');
                    @endphp
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                        <input class="w-full rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm py-2.5 px-3 cursor-not-allowed" value="{{ $roleLabel ?: 'Belum memiliki role' }}" disabled />
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-gray-300 bg-white text-gray-900 text-sm py-2.5 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-600">*</span></label>
                        <input id="password" name="password" type="password" required placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-gray-300 bg-white text-gray-900 text-sm py-2.5 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password <span class="text-red-600">*</span></label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Ulangi password" class="w-full rounded-xl border border-gray-300 bg-white text-gray-900 text-sm py-2.5 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-3 border border-red-100">
                        <div class="flex gap-2">
                            <div class="flex-shrink-0">
                                <svg class="h-4 w-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-xs text-gray-600 leading-snug">
                                Setelah menyimpan data, token verifikasi akan dikirim ke email Anda.
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-3 space-y-2">
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-semibold shadow-md hover:from-red-700 hover:to-red-800 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Simpan & Kirim Token Verifikasi
                        </button>

                        <a href="{{ route('login') }}"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
