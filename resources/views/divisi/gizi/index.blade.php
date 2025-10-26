<x-app-layout>
    <x-slot name="title">
        Ringkasan Data Gizi per Desa/Kelurahan
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            {{-- Kiri: Judul Divisi + Nama User --}}
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight whitespace-nowrap">
                    {{ __('Divisi Gizi') }}
                </h2>
            </div>

            {{-- Kanan: Dropdown Menu User (Lingkaran Profile) --}}
            <div class="relative" x-data="{ open: false }">
                {{-- Tombol Trigger Dropdown (Lingkaran Profile) --}}
                <button @click="open = !open" 
                        @click.away="open = false" 
                        class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                        type="button"
                        aria-expanded="false"
                        aria-haspopup="true"
                        title="{{ auth()->user()->name }}">
                    {{-- Inisial Nama User --}}
                    @php
                        $words = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        foreach ($words as $word) { if (!empty($word)) { $initials .= strtoupper(substr($word, 0, 1)); } }
                        if (strlen($initials) > 2) { $initials = substr($initials, 0, 1) . substr($initials, -1); }
                    @endphp
                    {{ $initials }}
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     @keydown.escape.window="open = false"
                     class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden" style="display: none;">
                    <div class="py-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0012 11z" clip-rule="evenodd" /></svg>
                            <span class="font-medium">Profil Saya</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                <span class="font-medium">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="mb-6 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="flex-1"><p class="text-sm font-medium text-green-900">{{ session('success') }}</p></div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="flex-1"><p class="text-sm font-medium text-red-900">{{ session('error') }}</p></div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-xl ring-1 ring-red-200 text-sm">
                    <p class="font-bold mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- Main Card --}}
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                     {{-- Judul --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Data Gizi per Desa/Kelurahan</h3>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Tombol Import --}}
                        <button x-data @click="$dispatch('open-import-form')" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                             Import Excel
                        </button>
                        {{-- Tombol Tambah Data --}}
                        <a href="{{ route('gizi.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-red-600 to-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-red-700 hover:to-rose-700 hover:shadow-lg">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                             Tambah Data
                        </a>
                    </div>
                </div>

                {{-- FORM IMPORT (disembunyikan secara default) --}}
                <div x-data="{ 
                     open: false, 
                     showFormatModal: false,
                     isDragging: false,
                     fileName: '',
                     handleFiles(files) { if (files.length > 0) { this.fileName = files[0].name; this.$refs.fileInput.files = files; } }
                 }" 
                 @open-import-form.window="open = !open" 
                 class="mt-6" x-cloak>
                     <button x-show="open"
                         @click="showFormatModal = true" type="button"
                         class="mb-2 inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                         Lihat Contoh Format
                      </button>
                      
                     <div x-show="open" x-transition class="p-4 bg-gray-50 rounded-xl border border-dashed">
                        <form action="{{ route('gizi.import') }}" method="POST" enctype="multipart/form-data">
                             @csrf
                             <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)"
                                  :class="isDragging ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white'"
                                  class="relative border-2 border-dashed rounded-lg p-8 text-center transition-all duration-200 cursor-pointer hover:border-red-400"
                                  @click="$refs.fileInput.click()">
                                 <div class="flex justify-center mb-4"> <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg> </div>
                                 <div x-show="!fileName"> <p class="text-sm text-gray-600 mb-1"> <span class="font-semibold text-red-600">Klik untuk upload</span> atau drag & drop </p> <p class="text-xs text-gray-500">Excel (XLSX, XLS, CSV) - Maks 2MB</p> </div>
                                 <div x-show="fileName" class="flex items-center justify-center gap-2"> <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> <span class="text-sm font-medium text-gray-700" x-text="fileName"></span> <button type="button" @click.stop="fileName = ''; $refs.fileInput.value = ''" class="text-red-600 hover:text-red-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button> </div>
                                 <input type="file" name="file" x-ref="fileInput" @change="handleFiles($event.target.files)" class="hidden" accept=".xlsx,.xls,.csv" required>
                             </div>
                             <div class="mt-4 flex justify-end"> <button type="submit" :disabled="!fileName" :class="fileName ? 'bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800' : 'bg-gray-300 cursor-not-allowed'" class="inline-flex justify-center items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-semibold text-white transition"> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg> Upload File </button> </div>
                        </form>
                     </div>

                     <div x-show="showFormatModal" x-transition class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                         <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                             <div @click="showFormatModal = false" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>
                             <div x-transition class="relative inline-block w-full max-w-4xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:p-6">
                                 <div class="flex items-center justify-between mb-4"> <div class="flex items-center gap-2"> <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full"><svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></div> <div> <h3 class="text-lg font-semibold text-gray-900">Format Excel Import Data Gizi</h3> <p class="text-sm text-gray-500">Pastikan file Excel Anda mengikuti format di bawah ini</p> </div> </div> <button @click="showFormatModal = false" class="text-gray-400 hover:text-gray-600 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button> </div>
                                 <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50 p-4"> <img src="{{ asset('img/contohFormatExcel/divisiGizi.png') }}" alt="Contoh Format Excel" class="w-full h-auto rounded-lg shadow-sm"> <p class="text-xs text-gray-500 text-center mt-3">Screenshot contoh format Excel yang benar</p> </div>
                                 <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg"> <div class="flex gap-2"> <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> <div class="text-sm"> <p class="font-semibold text-amber-800 mb-1">Catatan Penting:</p> <ul class="list-disc list-inside text-amber-700 space-y-1"> <li>Header (baris pertama) harus sesuai dengan contoh di atas</li> <li>Jangan ada kolom yang kosong</li> <li>Format file: .xlsx, .xls, atau .csv</li> <li>Maksimal ukuran file: 2MB</li> </ul> </div> </div> </div>
                                 <div class="mt-6 flex justify-end"> <button @click="showFormatModal = false" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"> Mengerti </button> </div>
                             </div>
                         </div>
                     </div>
                </div>

                {{-- TABEL AGREGRASI --}}
                <div class="mt-6 overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-200">
                                    {{-- Kolom Header --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Update Terakhir</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Desa/Kelurahan</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Total Anak</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Kasus Stunting</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Rata-rata ZS (Stunting)</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Status Desa</th> 
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Status Data</th> {{-- <-- KOLOM BARU --}}
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                {{-- Loop data agregat --}}
                                @forelse ($aggregatedData as $item)
                                    <tr class="transition hover:bg-gray-50">
                                        {{-- Tanggal Input Terbaru --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($item->tanggal_input_terbaru)->isoFormat('D MMM YYYY, HH:mm') }}
                                        </td>

                                        {{-- Desa/Kelurahan --}}
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900 uppercase">{{ $item->desa_kelurahan }}</div>
                                        </td>

                                         {{-- Total Anak --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 text-center">
                                           {{ $item->total_anak }}
                                        </td>

                                        {{-- Total Kasus Stunting --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-center {{ $item->total_kasus_stunting > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                            {{ $item->total_kasus_stunting }}
                                        </td>

                                        {{-- Rata-rata ZS TB/U (SEMUA Anak) --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 text-center">
                                            @php
                                                $avgZsTbU = ($item->total_anak > 0) ? ($item->sum_zs_tbu / $item->total_anak) : null;
                                            @endphp
                                            {{ !is_null($avgZsTbU) ? number_format($avgZsTbU, 2) : '-' }}
                                        </td>

                                        {{-- Status Desa --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            @php
                                                // Logika status desa tetap sama
                                                $avgZsTbU = ($item->total_anak > 0 && $item->sum_zs_tbu != 0) 
                                                    ? ($item->sum_zs_tbu / $item->total_anak) 
                                                    : null;
                                            @endphp
                                            
                                            @if(is_null($avgZsTbU) || $item->total_anak == 0)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">N/A</span>
                                            @elseif($avgZsTbU < -3)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                                   <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                   Sangat Pendek ({{ number_format($avgZsTbU, 2) }})
                                                </span>
                                            @elseif($avgZsTbU < -2)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700">
                                                   <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                                   Pendek ({{ number_format($avgZsTbU, 2) }})
                                                </span>
                                            @elseif($avgZsTbU <= 2)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                   <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                   Normal ({{ number_format($avgZsTbU, 2) }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                                   <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 4.414 6.707 7.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                                   Tinggi ({{ number_format($avgZsTbU, 2) }})
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ✅ KOLOM BARU: Status Data --}}
                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            @if($item->status === 'published') 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published
                                                </span> 
                                            @elseif($item->status === 'approved') 
                                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved
                                                </span> 
                                            @else 
                                                {{-- Default: Pending --}}
                                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending
                                                </span> 
                                            @endif
                                        </td>
                                        
                                        {{-- Aksi --}}
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-center gap-3">
                                                
                                                {{-- Link Detail --}}
                                                <a href="{{ route('gizi.detail', ['desa' => $item->desa_kelurahan]) }}" 
                                                class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800 hover:underline">
                                                    Detail
                                                </a>
                                                
                                                <span class="text-gray-300">|</span>

                                                {{-- Form Hapus Agregat --}}
                                                <form action="{{ route('gizi.destroyAggregated') }}" method="POST" 
                                                    onsubmit="return confirm('Yakin ingin menghapus SEMUA ({{ $item->total_anak }} data) untuk desa {{ $item->desa_kelurahan }} dengan status \'{{ $item->status }}\'? Tindakan ini tidak dapat diurungkan.');">
                                                    
                                                    {{-- INI WAJIB ADA --}}
                                                    @csrf
                                                    
                                                    {{-- @method('DELETE') --}}
                                                    
                                                    <input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}">
                                                    <input type="hidden" name="status" value="{{ $item->status }}">
                                                    <button type="submit" class="text-sm font-medium text-red-600 transition hover:text-red-800 hover:underline">
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- ✅ Colspan disesuaikan menjadi 8 --}}
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                            <p class="mt-4 text-sm text-gray-500">Belum ada data gizi yang diinput</p>
                                            <a href="{{ route('gizi.create') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                Tambah Data Pertama
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @php $paginator = $aggregatedData; @endphp 
                @if ($paginator->hasPages())
                    <div class="mt-6 sm:mt-8 px-4 sm:px-0">
                        {{-- Mobile Version: Compact Pagination --}}
                        <div class="flex sm:hidden flex-col gap-3">
                            <div class="flex items-center justify-center">
                                <nav class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                    @if ($paginator->onFirstPage())
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></span>
                                    @else
                                        <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
                                    @endif
                                    @if ($paginator->currentPage() == 1) <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">1</span>
                                    @else <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">1</a> @endif
                                    @if ($paginator->currentPage() > 2) <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400">...</span> @endif
                                    @if ($paginator->currentPage() != 1 && $paginator->currentPage() != $paginator->lastPage()) <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $paginator->currentPage() }}</span> @endif
                                    @if ($paginator->currentPage() < $paginator->lastPage() - 1) <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400">...</span> @endif
                                    @if ($paginator->lastPage() > 1)
                                        @if ($paginator->currentPage() == $paginator->lastPage()) <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $paginator->lastPage() }}</span>
                                        @else <a href="{{ $paginator->url($paginator->lastPage()) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">{{ $paginator->lastPage() }}</a> @endif
                                    @endif
                                    @if ($paginator->hasMorePages())
                                        <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                                    @else
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                                    @endif
                                </nav>
                            </div>
                            <div class="flex items-center justify-center gap-2 mt-3">
                                <span class="text-sm text-gray-500">Go to:</span>
                                <form method="GET" action="{{ $paginator->url(1) }}" class="flex items-center gap-2" onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $paginator->lastPage() }}) window.location.href = this.action.replace('page=1', 'page=' + page);">
                                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}" placeholder="{{ $paginator->currentPage() }}" class="w-16 px-3 py-2 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">Go</button>
                                </form>
                            </div>
                        </div>

                        {{-- Desktop/Tablet Version: Custom Pagination --}}
                        <div class="hidden sm:flex flex-col items-center gap-3">
                            <nav class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                @if ($paginator->onFirstPage()) <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></span>
                                @else <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a> @endif
                                @foreach ($paginator->links()->elements as $element)
                                    @if (is_string($element)) <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-400">...</span> @endif
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $paginator->currentPage()) <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $page }}</span>
                                            @else <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">{{ $page }}</a> @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @if ($paginator->hasMorePages()) <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                                @else <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span> @endif
                            </nav>
                             <div class="flex items-center gap-2 mt-2">
                                <span class="text-sm text-gray-500">Go to page:</span>
                                <form method="GET" action="{{ $paginator->url(1) }}" class="flex items-center gap-2" onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $paginator->lastPage() }}) window.location.href = this.action.replace('page=1', 'page=' + page);">
                                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}" placeholder="{{ $paginator->currentPage() }}" class="w-20 px-3 py-1.5 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">Go</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-4 text-center">
                            <p class="text-xs sm:text-sm text-gray-500">
                                Menampilkan <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
                                sampai <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
                                dari <span class="font-medium text-gray-700">{{ $paginator->total() }}</span> record desa/kelurahan
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>