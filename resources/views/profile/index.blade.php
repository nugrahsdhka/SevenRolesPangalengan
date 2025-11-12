<x-app-layout>
    <x-slot name="title">
        Profil Saya
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profil Saya') }}
            </h2>

            <a href="{{ route($dashboardRoute) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12 bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-1 sm:px-6 lg:px-8"> {{-- Max width sedikit lebih kecil --}}

            {{-- Card Profil Utama --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                {{-- Header Card --}}
                <div
                    class="bg-gradient-to-r from-red-700 to-red-800 p-6 sm:p-8 flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        <div
                            class="h-24 w-24 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white text-3xl font-bold shadow-xl ring-4 ring-offset-2 ring-offset-red-800 ring-white">
                            {{-- Inisial Nama User (Huruf pertama dari setiap kata) --}}
                            @php
                                $words = explode(' ', trim($user->name));
                                $initials = '';

                                if (count($words) === 1) {
                                    // Jika hanya 1 kata, ambil 2 huruf pertama
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                } else {
                                    // Jika lebih dari 1 kata, ambil huruf pertama setiap kata
                                    foreach ($words as $word) {
                                        if (!empty($word)) {
                                            $initials .= strtoupper(substr($word, 0, 1));
                                        }
                                    }
                                    // Jika lebih dari 2 kata, ambil huruf pertama dan terakhir
                                    if (strlen($initials) > 2) {
                                        $initials = substr($initials, 0, 1) . substr($initials, -1);
                                    }
                                }
                            @endphp
                            {{ $initials }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 h-6 w-6 bg-green-500 rounded-full border-4 border-white animate-pulse"
                            title="Online"></div>
                    </div>
                    {{-- Info Singkat --}}
                    <div class="text-center sm:text-left flex-1">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-sm sm:text-base text-red-300 mt-1">{{ $user->email }}</p>
                        <span
                            class="inline-block mt-2 px-3 py-1 bg-red-600 text-red-100 text-xs sm:text-sm font-medium rounded-full border border-red-500">
                            {{ $roleName }}
                        </span>
                    </div>
                    {{-- Tombol Edit di Header Card --}}
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('profile.edit') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100 shadow-sm hover:shadow-md">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>

                {{-- Detail Informasi Akun --}}
                <div class="p-6 sm:p-8 border-t border-red-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Akun</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div class="sm:col-span-1">
                            <dt class="text-gray-500 font-medium">Nama Lengkap</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-gray-500 font-medium">Alamat Email</dt>
                            <dd class="mt-1 text-gray-900">{{ $user->email }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-gray-500 font-medium">Role / Jabatan</dt>
                            <dd class="mt-1 text-gray-900">{{ $roleName }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-gray-500 font-medium">Akun Dibuat Pada</dt>
                            {{-- Format tanggal agar lebih mudah dibaca --}}
                            <dd class="mt-1 text-gray-900">{{ $user->created_at->translatedFormat('l, d F Y H:i') }}
                                WIB</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
