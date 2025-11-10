<x-app-layout>
    <x-slot name="title">
        Edit Profil
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
             <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Profil & Password') }}
            </h2>
             <a href="{{ route('profile.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12 bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-1 sm:px-6 lg:px-8"> {{-- Max width sedikit lebih kecil --}}

            {{-- Tampilkan pesan sukses/info --}}
             @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                     {{-- Bedakan warna notifikasi --}}
                     class="mb-4 flex items-center gap-2 px-4 py-2 rounded-lg border text-sm font-medium
                            {{ session('status') === 'no-changes' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-green-50 text-green-700 border-green-200' }}">
                     {{-- Icon sesuai status --}}
                     @if(session('status') === 'no-changes')
                        <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /> </svg>
                     @else
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg>
                     @endif
                    <span>{{ session('statusMessage') }}</span> {{-- Gunakan statusMessage --}}
                </div>
            @endif

            {{-- Card Form Edit Gabungan --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                 <div class="bg-gradient-to-r from-red-700 to-red-800 px-6 py-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        {{ __('Edit Profil & Password') }}
                    </h2>
                    <p class="text-sm text-red-200 mt-1">
                        {{ __("Ubah nama atau password Anda. Kosongkan password jika tidak ingin diubah.") }}
                    </p>
                </div>

                {{-- FORM GABUNGAN --}}
                <form method="post" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('patch')

                    {{-- Input Nama --}}
                    <div class="group">
                        <x-input-label for="update_name" :value="__('Nama Lengkap')" class="text-red-700 font-medium" />
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"> <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg> </div>
                            <x-text-input id="update_name" name="name" type="text" class="pl-10 block w-full focus:ring-2 focus:ring-red-500 focus:border-red-500" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" /> {{-- Error bag dihapus --}}
                    </div>

                    {{-- Separator --}}
                    <hr class="border-red-200 my-6">

                    <p class="text-sm text-red-600 -mb-2">{{ __('Isi bagian ini hanya jika Anda ingin mengubah password.') }}</p>

                    {{-- Input Password Saat Ini --}}
                    <div class="group">
                        <x-input-label for="current_password" :value="__('Password Saat Ini (Wajib jika ubah password)')" class="text-red-700 font-medium" />
                        <div class="mt-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"> <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /> </svg> </div>
                            <x-text-input id="current_password" name="current_password" type="password" class="pl-10 block w-full focus:ring-2 focus:ring-red-500 focus:border-red-500" autocomplete="current-password" /> {{-- Hapus required --}}
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    {{-- Input Password Baru --}}
                    <div class="group">
                        <x-input-label for="new_password" :value="__('Password Baru')" class="text-red-700 font-medium" />
                        <div class="mt-2 relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"> <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /> </svg> </div>
                            <x-text-input id="new_password" name="new_password" type="password" class="pl-10 block w-full focus:ring-2 focus:ring-red-500 focus:border-red-500" autocomplete="new-password" /> {{-- Hapus required --}}
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('new_password')" class="mt-2" />
                    </div>

                    {{-- Input Konfirmasi Password Baru --}}
                    <div class="group">
                        <x-input-label for="new_password_confirmation" :value="__('Konfirmasi Password Baru')" class="text-red-700 font-medium" />
                        <div class="mt-2 relative">
                             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"> <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg> </div>
                            <x-text-input id="new_password_confirmation" name="new_password_confirmation" type="password" class="pl-10 block w-full focus:ring-2 focus:ring-red-500 focus:border-red-500" autocomplete="new-password" /> {{-- Hapus required --}}
                        </div>
                        <x-input-error :messages="$errors->updatePassword->get('new_password_confirmation')" class="mt-2" />
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex items-center gap-4 pt-4 border-t border-red-200">
                        <x-primary-button class="justify-center w-full sm:w-auto">
                             <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /> </svg>
                            {{ __('Simpan Semua Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>