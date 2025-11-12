<x-app-layout>
    <x-slot name="title">
        Manajemen Data Penanggulangan
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            {{-- Kiri: Judul Divisi + Nama User --}}
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight whitespace-nowrap">
                    {{ __('Divisi Penanggulangan') }}
                </h2>
            </div>

            {{-- Kanan: Dropdown Menu User (Lingkaran Profile) --}}
            <div class="relative" x-data="{ open: false }">
                {{-- Tombol Trigger Dropdown (Lingkaran Profile) --}}
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                    type="button" aria-expanded="false" aria-haspopup="true" title="{{ auth()->user()->name }}">
                    {{-- Inisial Nama User (Huruf pertama dari setiap kata) --}}
                    @php
                        $words = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        foreach ($words as $word) {
                            if (!empty($word)) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                        }
                        // Ambil huruf pertama dan terakhir jika lebih dari 2 kata
                        if (strlen($initials) > 2) {
                            $initials = substr($initials, 0, 1) . substr($initials, -1);
                        }
                    @endphp
                    {{ $initials }}
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @keydown.escape.window="open = false"
                    class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                    style="display: none;">
                    <div class="py-1">
                        {{-- Info User Header --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        {{-- Menu Profil --}}
                        <a href="{{ route('profile.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0012 11z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">Profil Saya</span>
                        </a>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 my-1"></div>

                        {{-- Menu Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="font-medium">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ✅ PERBAIKAN 1: Tambahkan state untuk modal (openModal, modalData) --}}
    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50" x-data="{ openModal: false, modalData: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-6 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800"><svg
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800"><svg
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            @endif

            {{-- Main Card --}}
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex flex-col justify-center items-center">
                        <h3 class="text-md font-bold text-red-700">Daftar Data Penanggulangan</h3>
                    </div>
                    <a href="{{ route('penanggulangan.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-red-600 to-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-red-700 hover:to-rose-700 hover:shadow-lg">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Data
                    </a>
                </div>

                {{-- ✅ Tombol dan Form Import --}}
                <div x-data="{
                    open: false,
                    showModal: false,
                    isDragging: false,
                    fileName: '',
                    handleFiles(files) {
                        if (files.length > 0) {
                            this.fileName = files[0].name;
                            this.$refs.fileInput.files = files;
                        }
                    }
                }" class="mb-6">
                    <!-- Toggle Button & Info Button -->
                    <div class="flex items-center gap-3">
                        <button @click="open = !open"
                            class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span x-text="open ? 'Tutup Form Import' : 'Import dari Excel'"></span>
                        </button>

                        <!-- Info Button -->
                        <button @click="showModal = true" type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lihat Contoh Format
                        </button>
                    </div>

                    <!-- Upload Form -->
                    <div x-show="open" x-transition class="mt-4 p-4 bg-gray-50 rounded-xl border border-dashed">
                        <form action="{{ route('penanggulangan.import') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Drag & Drop Area -->
                            <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)"
                                :class="isDragging ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white'"
                                class="relative border-2 border-dashed rounded-lg p-8 text-center transition-all duration-200 cursor-pointer hover:border-red-400"
                                @click="$refs.fileInput.click()">
                                <!-- Icon Upload -->
                                <div class="flex justify-center mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>

                                <!-- Text -->
                                <div x-show="!fileName">
                                    <p class="text-sm text-gray-600 mb-1">
                                        <span class="font-semibold text-red-600">Klik untuk upload</span> atau drag &
                                        drop
                                    </p>
                                    <p class="text-xs text-gray-500">Excel (XLSX, XLS) - Maks 2MB</p>
                                </div>

                                <!-- File Name Display -->
                                <div x-show="fileName" class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700" x-text="fileName"></span>
                                    <button type="button" @click.stop="fileName = ''; $refs.fileInput.value = ''"
                                        class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Hidden File Input -->
                                <input type="file" name="file" id="file" x-ref="fileInput"
                                    @change="handleFiles($event.target.files)" class="hidden" accept=".xlsx,.xls"
                                    required>
                            </div>

                            <!-- Upload Button -->
                            <div class="mt-4 flex justify-end">
                                <button type="submit" :disabled="!fileName"
                                    :class="fileName ?
                                        'bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800' :
                                        'bg-gray-300 cursor-not-allowed'"
                                    class="inline-flex justify-center items-center gap-2 rounded-lg px-6 py-2.5 text-sm font-semibold text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
                        style="display: none;">
                        <!-- Backdrop -->
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                            <div @click="showModal = false"
                                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

                            <!-- Modal Content -->
                            <div x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="relative inline-block w-full max-w-4xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:p-6">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Format Excel Import Data
                                                Penanggulangan</h3>
                                            <p class="text-sm text-gray-500">Pastikan file Excel Anda mengikuti format
                                                di bawah ini</p>
                                        </div>
                                    </div>
                                    <button @click="showModal = false"
                                        class="text-gray-400 hover:text-gray-600 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Image Example -->
                                <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50 p-4">
                                    <img src="{{ asset('img/contohFormatExcel/divisiPenanggulangan.png') }}"
                                        alt="Contoh Format Excel Penanggulangan"
                                        class="w-full h-auto rounded-lg shadow-sm">
                                    <p class="text-xs text-gray-500 text-center mt-3">Screenshot contoh format Excel
                                        yang benar</p>
                                </div>

                                <!-- Notes -->
                                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                    <div class="flex gap-2">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <div class="text-sm">
                                            <p class="font-semibold text-amber-800 mb-1">Catatan Penting:</p>
                                            <ul class="list-disc list-inside text-amber-700 space-y-1">
                                                <li>Header (baris pertama) harus sesuai dengan contoh di atas</li>
                                                <li>Jangan ada kolom yang kosong</li>
                                                <li>Format file: .xlsx atau .xls</li>
                                                <li>Maksimal ukuran file: 2MB</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="mt-6 flex justify-end">
                                    <button @click="showModal = false"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                        Mengerti
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-200">
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Periode</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Jenis Bencana</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Jumlah Korban</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($data as $item)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                            {{ $item->periode->format('F Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $item->jenis_bencana }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ number_format($item->jumlah_korban) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($item->status === 'published')
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"><span
                                                        class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published</span>
                                            @elseif($item->status === 'approved')
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"><span
                                                        class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved</span>
                                            @else<span
                                                    class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"><span
                                                        class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-center gap-3">
                                                {{-- ✅ PERBAIKAN 2: Tambahkan tombol "Detail" --}}
                                                <button @click="openModal = true; modalData = {{ Js::from($item) }}"
                                                    class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                                    Detail
                                                </button>
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('penanggulangan.edit', $item->id) }}"
                                                    class="text-sm font-medium text-blue-600 transition hover:text-blue-800">Edit</a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('penanggulangan.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-sm font-medium text-red-600 transition hover:text-red-800">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="mt-4 text-sm text-gray-500">Belum ada data penanggulangan yang
                                                diinput</p>
                                            <a href="{{ route('penanggulangan.create') }}"
                                                class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Tambah Data Pertama
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($data->hasPages())
                    <div class="mt-6 sm:mt-8 px-4 sm:px-0">
                        {{-- Mobile Version: Compact Pagination --}}
                        <div class="flex sm:hidden flex-col gap-3">
                            <div class="flex items-center justify-center">
                                <nav
                                    class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                    {{-- Previous Button --}}
                                    @if ($data->onFirstPage())
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $data->previousPageUrl() }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($data->currentPage() == 1)
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                            1
                                        </span>
                                    @else
                                        <a href="{{ $data->url(1) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                            1
                                        </a>
                                    @endif

                                    {{-- Left Ellipsis --}}
                                    @if ($data->currentPage() > 2)
                                        <button
                                            onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                            ...
                                        </button>
                                    @endif

                                    {{-- Current Page (if not first or last) --}}
                                    @if ($data->currentPage() != 1 && $data->currentPage() != $data->lastPage())
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                            {{ $data->currentPage() }}
                                        </span>
                                    @endif

                                    {{-- Right Ellipsis --}}
                                    @if ($data->currentPage() < $data->lastPage() - 1)
                                        <button
                                            onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                            ...
                                        </button>
                                    @endif

                                    {{-- Last Page --}}
                                    @if ($data->lastPage() > 1)
                                        @if ($data->currentPage() == $data->lastPage())
                                            <span
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                                {{ $data->lastPage() }}
                                            </span>
                                        @else
                                            <a href="{{ $data->url($data->lastPage()) }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                                {{ $data->lastPage() }}
                                            </a>
                                        @endif
                                    @endif

                                    {{-- Next Button --}}
                                    @if ($data->hasMorePages())
                                        <a href="{{ $data->nextPageUrl() }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>

                            {{-- Mobile Go To --}}
                            <div class="flex items-center justify-center gap-2">
                                <span class="text-sm text-gray-500">Go to:</span>
                                <form method="GET" action="" class="flex items-center gap-2"
                                    onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $data->lastPage() }}) window.location.href = '{{ $data->url(1) }}'.replace(/page=\d+/, 'page=' + page);">
                                    <input type="number" name="page" min="1"
                                        max="{{ $data->lastPage() }}" placeholder="{{ $data->currentPage() }}"
                                        class="w-16 px-3 py-2 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">
                                        Go
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Desktop/Tablet Version: Compact Pagination --}}
                        <div class="hidden sm:flex flex-col items-center gap-3">
                            <nav
                                class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                {{-- Previous Button --}}
                                @if ($data->onFirstPage())
                                    <span
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $data->previousPageUrl() }}"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- First Page --}}
                                @if ($data->currentPage() == 1)
                                    <span
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                        1
                                    </span>
                                @else
                                    <a href="{{ $data->url(1) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                        1
                                    </a>
                                @endif

                                {{-- Left Ellipsis --}}
                                @if ($data->currentPage() > 3)
                                    <button
                                        onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        ...
                                    </button>
                                @endif

                                {{-- Previous Adjacent Page --}}
                                @if ($data->currentPage() > 2)
                                    <a href="{{ $data->url($data->currentPage() - 1) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                        {{ $data->currentPage() - 1 }}
                                    </a>
                                @endif

                                {{-- Current Page (if not first or last) --}}
                                @if ($data->currentPage() != 1 && $data->currentPage() != $data->lastPage())
                                    <span
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                        {{ $data->currentPage() }}
                                    </span>
                                @endif

                                {{-- Next Adjacent Page --}}
                                @if ($data->currentPage() < $data->lastPage() - 1)
                                    <a href="{{ $data->url($data->currentPage() + 1) }}"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                        {{ $data->currentPage() + 1 }}
                                    </a>
                                @endif

                                {{-- Right Ellipsis --}}
                                @if ($data->currentPage() < $data->lastPage() - 2)
                                    <button
                                        onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        ...
                                    </button>
                                @endif

                                {{-- Last Page --}}
                                @if ($data->lastPage() > 1)
                                    @if ($data->currentPage() == $data->lastPage())
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                            {{ $data->lastPage() }}
                                        </span>
                                    @else
                                        <a href="{{ $data->url($data->lastPage()) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                            {{ $data->lastPage() }}
                                        </a>
                                    @endif
                                @endif

                                {{-- Next Button --}}
                                @if ($data->hasMorePages())
                                    <a href="{{ $data->nextPageUrl() }}"
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>

                            {{-- Desktop Go To --}}
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">Go to page:</span>
                                <form method="GET" action="" class="flex items-center gap-2"
                                    onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $data->lastPage() }}) window.location.href = '{{ $data->url(1) }}'.replace(/page=\d+/, 'page=' + page);">
                                    <input type="number" name="page" min="1"
                                        max="{{ $data->lastPage() }}" placeholder="{{ $data->currentPage() }}"
                                        class="w-20 px-3 py-1.5 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit"
                                        class="px-4 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">
                                        Go
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Info Text (Optional) --}}
                        <div class="mt-3 sm:mt-4 text-center">
                            <p class="text-xs sm:text-sm text-gray-500">
                                Menampilkan <span class="font-medium text-gray-700">{{ $data->firstItem() }}</span>
                                sampai <span class="font-medium text-gray-700">{{ $data->lastItem() }}</span>
                                dari <span class="font-medium text-gray-700">{{ $data->total() }}</span> hasil
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        {{-- ✅ PERBAIKAN 3: Tambahkan struktur Modal --}}
        <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                <div x-show="openModal" @click="openModal = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Detail Data
                                    Penanggulangan</h3>
                                <div class="mt-4 text-sm text-gray-600 space-y-4">
                                    <template x-if="modalData">
                                        <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-4">
                                            <div>
                                                <p><span class="font-semibold text-gray-800">Periode Laporan:</span>
                                                    <span
                                                        x-text="new Date(modalData.periode).toLocaleString('id-ID', { month: 'long', year: 'numeric' })"></span>
                                                </p>
                                                <p><span class="font-semibold text-gray-800">Jenis Bencana:</span>
                                                    <span x-text="modalData.jenis_bencana"></span></p>
                                                <p><span class="font-semibold text-gray-800">Jumlah Korban:</span>
                                                    <span x-text="modalData.jumlah_korban"></span></p>
                                                <p class="font-semibold text-gray-800 mt-2">Tindakan:</p>
                                                <p class="preserve-lines" x-text="modalData.tindakan"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="openModal = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
