@php
    // Mode input: manual / import (default: manual)
    $inputMode = request('mode', 'manual');
    // Periode aktif (Y-m) + label tampilan
    $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');
    $currentPeriodLabel = $currentPeriod
        ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
        : now()->isoFormat('MMMM Y');
@endphp

<div class="px-2 py-2 flex flex-col gap-4">
    {{-- HEADER: Judul + info periode --}}
    <div class="rounded-xl shadow-sm border border-gray-200 bg-white px-4 py-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                    Tambah Data Gizi
                </h3>
            </div>
            <div class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                <span class="font-medium text-gray-800">Periode aktif:</span>
                <span class="ml-1">{{ $currentPeriodLabel }}</span>
            </div>
        </div>
    </div>
    {{-- Main card --}}
    <div class="w-full flex justify-center">
        <div class="w-full max-w-3xl">
            {{-- TABS: Manual vs Import --}}
            <div class="border-b border-gray-200 mb-4">
                <nav class="flex flex-wrap gap-2" aria-label="Metode input">
                    @php
                        $tabItemsTambah = [
                            'manual' => [
                                'label' => 'Input Manual',
                                'bg' => 'border-red-500 text-red-700 bg-red-50',
                            ],
                            'import' => [
                                'label' => 'Import Excel',
                                'bg' => 'border-emerald-500 text-emerald-700 bg-emerald-50',
                            ],
                        ];
                    @endphp
                    @foreach ($tabItemsTambah as $modeKey => $meta)
                        @php $active = ($inputMode === $modeKey); @endphp
                        <a href="{{ route('gizi.index', [
                            'view' => 'tambah',
                            'mode' => $modeKey,
                            'period' => $activePeriodYm,
                        ]) }}"
                            class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition-all
                                {{ $active
                                    ? $meta['bg'] . ' border-b-2'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300' }}">
                            <span
                                class="flex h-2 w-2 rounded-full
                                        {{ $active ? 'bg-current' : 'bg-gray-300 group-hover:bg-gray-400' }}"></span>
                            <span>{{ $meta['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
            {{-- KONTEN: sesuai tab --}}
            @if ($inputMode === 'manual')
                {{-- ================= TAB: INPUT MANUAL (STYLE SAMA DENGAN IMPORT) ================= --}}
                <div class="max-w-3xl border border-gray-200 rounded-xl p-5 bg-white">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">
                                Input Manual
                            </h4>
                            <p class="text-xs text-gray-500">
                                Gunakan formulir ini untuk menambah satu data gizi anak.
                            </p>
                        </div>
                        <div
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-medium text-gray-600 bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Isi data satu per satu</span>
                        </div>
                    </div>

                    <form action="{{ route('gizi.store') }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- Periode --}}
                        <div class="space-y-2">
                            <label for="manual_period" class="text-xs font-medium text-gray-700">Periode</label>
                            <input
                                type="month"
                                id="manual_period"
                                name="period"
                                value="{{ old('period', $activePeriodYm) }}"
                                required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                            >
                        </div>

                        {{-- Desa --}}
                        <div class="space-y-2">
                            <label for="desa_kelurahan" class="text-xs font-medium text-gray-700">Desa/Kelurahan</label>
                            <input
                                type="text"
                                id="desa_kelurahan"
                                name="desa_kelurahan"
                                value="{{ old('desa_kelurahan') }}"
                                required
                                placeholder="Contoh: Pangalengan"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                            >
                        </div>

                        {{-- Berat & Tinggi --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="space-y-2">
                                <label for="berat" class="text-xs font-medium text-gray-700">Berat (kg)</label>
                                <input
                                    type="number"
                                    id="berat"
                                    name="berat"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('berat') }}"
                                    required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="tinggi" class="text-xs font-medium text-gray-700">Tinggi (cm)</label>
                                <input
                                    type="number"
                                    id="tinggi"
                                    name="tinggi"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('tinggi') }}"
                                    required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                            </div>
                        </div>

                        {{-- Status & Z-Score --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            {{-- BB/U --}}
                            <div class="space-y-2">
                                <label class="text-xs font-medium text-gray-700">
                                    Status BB/U
                                </label>
                                <input
                                    type="text"
                                    name="status_bb_u"
                                    value="{{ old('status_bb_u') }}"
                                    placeholder="misal: Gizi Kurang"
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                                <input
                                    type="number"
                                    step="0.01"
                                    name="zs_bb_u"
                                    required
                                    value="{{ old('zs_bb_u') }}"
                                    placeholder="Z-Score BB/U"
                                    class="mt-1 w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                            </div>

                            {{-- TB/U --}}
                            <div class="space-y-2">
                                <label class="text-xs font-medium text-gray-700">
                                    Status TB/U
                                </label>
                                <input
                                    type="text"
                                    name="status_tb_u"
                                    value="{{ old('status_tb_u') }}"
                                    placeholder="misal: Pendek"
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                                <input
                                    type="number"
                                    step="0.01"
                                    name="zs_tb_u"
                                    required
                                    value="{{ old('zs_tb_u') }}"
                                    placeholder="Z-Score TB/U"
                                    class="mt-1 w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                            </div>

                            {{-- BB/TB --}}
                            <div class="space-y-2">
                                <label class="text-xs font-medium text-gray-700">
                                    Status BB/TB
                                </label>
                                <input
                                    type="text"
                                    name="status_bb_tb"
                                    value="{{ old('status_bb_tb') }}"
                                    placeholder="misal: Kurus"
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                                <input
                                    type="number"
                                    step="0.01"
                                    name="zs_bb_tb"
                                    required
                                    value="{{ old('zs_bb_tb') }}"
                                    placeholder="Z-Score BB/TB"
                                    class="mt-1 w-full px-3 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                >
                            </div>
                        </div>

                        <div class="pt-2 flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white
                                    bg-gradient-to-r from-red-600 to-red-700 rounded-lg
                                    hover:from-red-700 hover:to-red-800 active:from-red-800 active:to-red-900
                                    transition-all duration-200 shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- ================= TAB: IMPORT EXCEL ================= --}}
                <div x-data="{
                    open: true,
                    showFormatModal: false,
                    isDragging: false,
                    fileName: '',
                    handleFiles(files) {
                        if (files.length > 0) {
                            this.fileName = files[0].name;
                            this.$refs.fileInput.files = files;
                        }
                    }
                }" x-cloak class="max-w-3xl border border-gray-200 rounded-xl p-5 bg-white">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900">
                                Import dari Excel
                            </h4>
                            <p class="text-xs text-gray-500">
                                Gunakan file Excel dengan format yang sudah ditentukan.
                            </p>
                        </div>
                        <button @click="showFormatModal = true" type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lihat Contoh Format
                        </button>
                    </div>

                    <form action="{{ route('gizi.import') }}" method="POST" enctype="multipart/form-data"
                        x-data="{
                            isDragging: false,
                            files: [],
                            get hasFiles() {
                                return this.files.length > 0;
                            },
                            handleFiles(selected) {
                                if (!selected || !selected.length) return;

                                // DataTransfer untuk sync ke <input type='file'>
                                const dt = new DataTransfer();

                                // Tambahkan file-file yang sudah ada dulu
                                this.files.forEach(f => dt.items.add(f));

                                // Tambahkan file baru (hindari duplikat berdasarkan nama+size+lastModified)
                                Array.from(selected).forEach(file => {
                                    const exists = this.files.some(f =>
                                        f.name === file.name &&
                                        f.size === file.size &&
                                        f.lastModified === file.lastModified
                                    );
                                    if (!exists) {
                                        this.files.push(file);
                                        dt.items.add(file);
                                    }
                                });

                                // Sync ke input file
                                this.$refs.fileInput.files = dt.files;
                            },
                            clearFiles() {
                                this.files = [];
                                this.$refs.fileInput.value = '';
                            }
                        }"
                        class="space-y-4">

                        @csrf

                        <div class="space-y-2">
                            <label for="import_period" class="text-xs font-medium text-gray-700">
                                Periode Import
                            </label>
                            <input
                                type="month"
                                id="import_period"
                                name="period"
                                required
                                value="{{ old('period', $activePeriodYm) }}"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white
                                    focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                        </div>


                        <div
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="isDragging=false; handleFiles($event.dataTransfer.files)"
                            :class="isDragging ? 'border-red-500 bg-red-50 scale-[1.02]' : 'border-gray-300 bg-gray-50'"
                            class="relative border-2 border-dashed rounded-xl p-6 sm:p-8 text-center transition-all duration-200 cursor-pointer hover:border-red-400 hover:bg-red-50/50"
                            @click="$refs.fileInput.click()">

                            {{-- Ikon + animasi drag --}}
                            <div class="flex justify-center mb-3">
                                <div class="relative">
                                    <svg class="w-12 h-12 text-gray-300 transition-transform duration-200"
                                        :class="isDragging ? 'scale-110 text-red-400' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div x-show="isDragging"
                                        class="absolute inset-0 flex items-center justify-center">
                                        <div
                                            class="w-16 h-16 border-4 border-red-500 border-dashed rounded-full animate-spin">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Teks default (belum ada file) --}}
                            <div x-show="!hasFiles">
                                <p class="text-sm font-medium text-gray-700 mb-1">
                                    <span class="text-red-600">Klik untuk upload</span> atau drag & drop file
                                </p>
                                <p class="text-xs text-gray-500">
                                    Excel (XLSX, XLS, CSV) • Maks 10MB
                                </p>
                            </div>

                            {{-- Daftar file (vertikal + scrollable) --}}
                            <div x-show="hasFiles" x-transition
                                class="mt-2 p-3 bg-white rounded-lg border border-green-200 text-left w-full mx-auto max-w-md">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700">
                                            File terpilih (<span x-text="files.length"></span>)
                                        </span>
                                    </div>

                                    <button type="button" @click.stop="clearFiles()"
                                        class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- LIST VERTIKAL + SCROLL --}}
                                <ul class="text-xs text-gray-700 space-y-1 max-h-24 overflow-y-auto pr-1">
                                    <template x-for="(file, index) in files" :key="index">
                                        <li class="flex items-center gap-2">
                                            <span class="text-[10px] text-gray-400" x-text="(index + 1) + '.'"></span>
                                            <span class="truncate" x-text="file.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            {{-- Input file tersembunyi --}}
                            <input
                                type="file"
                                name="files[]"
                                x-ref="fileInput"
                                @change="handleFiles($event.target.files)"
                                class="hidden"
                                accept=".xlsx,.xls,.csv"
                                multiple
                                required>
                        </div>

                        <div class="pt-1 flex justify-end">
                            <button type="submit"
                                :disabled="!hasFiles"
                                :class="hasFiles
                                    ? 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg'
                                    : 'bg-gray-200 cursor-not-allowed'"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Upload File
                            </button>
                        </div>
                    </form>

                    {{-- MODAL FORMAT EXCEL --}}
                    <div x-show="showFormatModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
                        style="display: none;" @keydown.escape.window="showFormatModal = false">
                        <div class="flex items-center justify-center min-h-screen px-4 py-8">
                            <div @click="showFormatModal = false"
                                class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity">
                            </div>
                            <div x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden">
                                <div class="p-6 sm:p-8">
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                                    Format Excel Import
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    Pastikan file Excel Anda mengikuti format di
                                                    bawah ini
                                                </p>
                                            </div>
                                        </div>
                                        <button @click="showFormatModal = false"
                                            class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div
                                        class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 p-4 shadow-inner">
                                        <img src="{{ asset('img/contohFormatExcel/divisiGizi.png') }}"
                                            alt="Contoh Format Excel" class="w-full h-auto rounded-lg shadow-md">
                                        <p class="text-xs text-gray-400 text-center mt-4">
                                            Screenshot contoh format Excel yang benar
                                        </p>
                                    </div>
                                    <div
                                        class="mt-6 p-5 bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 rounded-xl">
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 text-sm">
                                                <p class="font-semibold text-red-900 mb-3">
                                                    Catatan Penting:
                                                </p>
                                                <ul class="space-y-2 text-red-800">
                                                    <li class="flex items-start gap-2">
                                                        <span
                                                            class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                        <span>Header (baris pertama) harus
                                                            sesuai dengan contoh di atas</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span
                                                            class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                        <span>Jangan ada kolom yang
                                                            kosong</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span
                                                            class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                        <span>Format file: .xlsx, .xls, atau
                                                            .csv</span>
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span
                                                            class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                        <span>Maksimal ukuran file: 2MB</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-8 flex justify-end">
                                        <button @click="showFormatModal = false"
                                            class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 active:from-red-800 active:to-red-900 transition-all duration-200 shadow-md hover:shadow-lg">
                                            Mengerti
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
