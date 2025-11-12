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

            {{-- Kanan: Dropdown Menu User --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                    type="button" aria-expanded="false" aria-haspopup="true" title="{{ auth()->user()->name }}">
                    @php
                        $words = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        foreach ($words as $word) {
                            if (!empty($word)) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                        }
                        if (strlen($initials) > 2) {
                            $initials = substr($initials, 0, 1) . substr($initials, -1);
                        }
                    @endphp
                    {{ $initials }}
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @keydown.escape.window="open = false"
                    class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                    style="display: none;">
                    <div class="py-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
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
                        <div class="border-t border-gray-100 my-1"></div>
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

    <div class="px-1 py-2 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-7xl ">
            {{-- Alerts --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="my-3 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="my-3 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="my-3 p-4 bg-red-50 text-red-800 rounded-xl ring-1 ring-red-200 text-sm">
                    <p class="font-bold mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Main Card --}}
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                <div class="space-y-6">
                    {{-- Header Section --}}
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        {{-- Title & Period Selector --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <h3 class="text-xl sm:text-2xl font-semibold text-center md:text-left text-gray-900">
                                Data Gizi per Desa/Kelurahan
                            </h3>
                        </div>

                        {{-- Action Buttons (Only on Pending Tab) --}}
                        @if ($status === 'pending')
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <button x-data @click="$dispatch('open-import-form')"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 active:bg-gray-100 transition-all duration-200 shadow-sm hover:shadow">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    <span>Import Excel</span>
                                </button>

                                <a href="{{ route('gizi.create', ['period' => $periodYm ?? now()->format('Y-m')]) }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 active:from-red-800 active:to-red-900 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span>Tambah Data</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Period Form --}}
                    @php
                        $periodYm = $currentPeriod ? \Carbon\Carbon::parse($currentPeriod)->format('Y-m') : null;
                    @endphp

                    {{-- Status Tabs with Beautiful Badges --}}
                    @php
                        $tabItems = [
                            'pending' => [
                                'label' => 'Pending',
                                'bg' => 'bg-amber-50',
                                'text' => 'text-amber-700',
                                'border' => 'border-amber-500',
                                'badge_bg' => 'bg-amber-100',
                                'badge_text' => 'text-amber-700',
                                'dot' => 'bg-amber-500',
                            ],
                            'approved' => [
                                'label' => 'Approved',
                                'bg' => 'bg-blue-50',
                                'text' => 'text-blue-700',
                                'border' => 'border-blue-500',
                                'badge_bg' => 'bg-blue-100',
                                'badge_text' => 'text-blue-700',
                                'dot' => 'bg-blue-500',
                            ],
                            'published' => [
                                'label' => 'Published',
                                'bg' => 'bg-emerald-50',
                                'text' => 'text-emerald-700',
                                'border' => 'border-emerald-500',
                                'badge_bg' => 'bg-emerald-100',
                                'badge_text' => 'text-emerald-700',
                                'dot' => 'bg-emerald-500',
                            ],
                        ];
                        $counts = array_merge(['pending' => 0, 'approved' => 0, 'published' => 0], $statusCounts ?? []);
                    @endphp

                    <div class=" flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        {{-- Navigation Tabs --}}
                        <nav class="flex flex-col sm:flex-row sm:gap-2" role="tablist" aria-label="Status data">
                            @foreach ($tabItems as $key => $meta)
                                @php $active = ($status === $key); @endphp
                                <a href="{{ route('gizi.index', ['period' => $periodYm ?? now()->format('Y-m'), 'status' => $key]) }}"
                                    role="tab" aria-selected="{{ $active ? 'true' : 'false' }}"
                                    class="group relative flex items-center justify-between sm:justify-center gap-3 px-4 py-3 sm:py-4 text-sm font-medium border-b-2 sm:border-b-3 transition-all duration-200
                                    {{ $active
                                        ? $meta['text'] . ' ' . $meta['border'] . ' ' . $meta['bg']
                                        : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}">
                                    <span class="flex items-center gap-2">
                                        {{-- Animated Dot Indicator --}}
                                        <span
                                            class="relative flex h-2 w-2 {{ $active ? '' : 'opacity-0 group-hover:opacity-50' }}">
                                            @if ($active && $counts[$key] > 0)
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $meta['dot'] }} opacity-75"></span>
                                            @endif
                                            <span
                                                class="relative inline-flex rounded-full h-2 w-2 {{ $meta['dot'] }}"></span>
                                        </span>
                                        <span>{{ $meta['label'] }}</span>
                                    </span>

                                    {{-- Elegant Badge --}}
                                    <span
                                        class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-1 rounded-full text-xs font-semibold transition-all duration-200
                                        {{ $active
                                            ? $meta['badge_bg'] . ' ' . $meta['badge_text'] . ' shadow-sm'
                                            : 'bg-gray-100 text-gray-600 group-hover:bg-gray-200' }}">
                                                            {{ $counts[$key] ?? 0 }}
                                    </span>

                                    {{-- Active Indicator Line --}}
                                    @if ($active)
                                        <span
                                            class="absolute bottom-0 left-0 right-0 h-0.5 {{ $meta['bg'] }} hidden sm:block"></span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>

                        <form method="GET" action="{{ route('gizi.index') }}"
                            class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <label class="text-sm text-gray-600">Periode:</label>
                            <select name="period" required
                                class="text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                                @forelse($availablePeriods ?? [] as $p)
                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                    <option value="{{ $ym }}" {{ $periodYm === $ym ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                    </option>
                                @empty
                                    @php $ymNow = now()->format('Y-m'); @endphp
                                    <option value="{{ $ymNow }}" selected>{{ now()->isoFormat('MMMM Y') }}
                                    </option>
                                @endforelse
                            </select>

                            <input type="hidden" name="status" value="{{ $status ?? 'pending' }}" />

                            <button type="submit"
                                class="px-2 md:px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                Tampilkan
                            </button>
                        </form>
                    </div>


                    {{-- Import Section --}}
                    @if ($status === 'pending')
                        <div x-data="{
                            open: false,
                            showFormatModal: false,
                            isDragging: false,
                            fileName: '',
                            handleFiles(files) {
                                if (files.length > 0) {
                                    this.fileName = files[0].name;
                                    this.$refs.fileInput.files = files;
                                }
                            }
                        }" @open-import-form.window="open = !open" x-cloak>

                            <button x-show="open" @click="showFormatModal = true" type="button"
                                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 active:bg-red-200 transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Lihat Contoh Format Excel
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="mt-4 p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
                                <form action="{{ route('gizi.import') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
                                        <label for="import_period"
                                            class="text-sm font-medium text-gray-700 whitespace-nowrap">Periode
                                            Import:</label>
                                        <input type="month" id="import_period" name="period" required
                                            value="{{ $periodYm ?? now()->format('Y-m') }}"
                                            class="w-full sm:w-auto px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all">
                                    </div>

                                    <div @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
                                        @drop.prevent="isDragging=false; handleFiles($event.dataTransfer.files)"
                                        :class="isDragging ? 'border-red-500 bg-red-50 scale-[1.02]' :
                                            'border-gray-300 bg-gray-50'"
                                        class="relative border-2 border-dashed rounded-xl p-8 sm:p-12 text-center transition-all duration-200 cursor-pointer hover:border-red-400 hover:bg-red-50/50"
                                        @click="$refs.fileInput.click()">

                                        <div class="flex justify-center mb-4">
                                            <div class="relative">
                                                <svg class="w-16 h-16 text-gray-300 transition-transform duration-200"
                                                    :class="isDragging ? 'scale-110 text-red-400' : ''" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <div x-show="isDragging"
                                                    class="absolute inset-0 flex items-center justify-center">
                                                    <div
                                                        class="w-20 h-20 border-4 border-red-500 border-dashed rounded-full animate-spin">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="!fileName">
                                            <p class="text-base font-medium text-gray-700 mb-2">
                                                <span class="text-red-600">Klik untuk upload</span> atau drag & drop
                                                file
                                            </p>
                                            <p class="text-sm text-gray-500">Excel (XLSX, XLS, CSV) • Maksimal 2MB</p>
                                        </div>

                                        <div x-show="fileName" x-transition
                                            class="flex flex-col sm:flex-row items-center justify-center gap-3 p-4 bg-white rounded-lg border border-green-200">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700 break-all"
                                                    x-text="fileName"></span>
                                            </div>
                                            <button type="button" @click.stop="fileName=''; $refs.fileInput.value=''"
                                                class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <input type="file" name="file" x-ref="fileInput"
                                            @change="handleFiles($event.target.files)" class="hidden"
                                            accept=".xlsx,.xls,.csv" required>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button type="submit" :disabled="!fileName"
                                            :class="fileName ?
                                                'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg' :
                                                'bg-gray-200 cursor-not-allowed'"
                                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white rounded-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Upload File
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- Format Modal --}}
                            <div x-show="showFormatModal" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                                @keydown.escape.window="showFormatModal = false">
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
                                                        <svg class="w-6 h-6 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                                            Format Excel Import</h3>
                                                        <p class="mt-1 text-sm text-gray-500">Pastikan file Excel Anda
                                                            mengikuti format di bawah ini</p>
                                                    </div>
                                                </div>
                                                <button @click="showFormatModal = false"
                                                    class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div
                                                class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 p-4 shadow-inner">
                                                <img src="{{ asset('img/contohFormatExcel/divisiGizi.png') }}"
                                                    alt="Contoh Format Excel"
                                                    class="w-full h-auto rounded-lg shadow-md">
                                                <p class="text-xs text-gray-400 text-center mt-4">Screenshot contoh
                                                    format Excel yang benar</p>
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
                                                        <p class="font-semibold text-red-900 mb-3">Catatan Penting:</p>
                                                        <ul class="space-y-2 text-red-800">
                                                            <li class="flex items-start gap-2">
                                                                <span
                                                                    class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                <span>Header (baris pertama) harus sesuai dengan contoh
                                                                    di atas</span>
                                                            </li>
                                                            <li class="flex items-start gap-2">
                                                                <span
                                                                    class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                <span>Jangan ada kolom yang kosong</span>
                                                            </li>
                                                            <li class="flex items-start gap-2">
                                                                <span
                                                                    class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                <span>Format file: .xlsx, .xls, atau .csv</span>
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

                {{-- ====== TABEL AGREGASI (berdasarkan status aktif) ====== --}}
                <div class="mt-6 overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <div class="max-h-[64vh] md:max-h-[63vh] overflow-y-auto bg-white">
                            <table class="w-full min-w-[1200px]"> {{-- Lebarkan min-width --}}
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Periode</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Desa/Kelurahan</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Total Anak</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Avg. Berat (kg)</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Avg. Tinggi (cm)</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Avg. ZS BB/U</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Avg. ZS TB/U</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Avg. ZS BB/TB</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Status Data</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">

                                    {{-- Loop menggunakan $aggregatedData --}}
                                    @forelse ($aggregatedData as $item)
                                        {{-- Hitung semua rata-rata di sini --}}
                                        @php
                                            $avgBerat =
                                                $item->total_anak > 0 ? $item->sum_berat / $item->total_anak : null;
                                            $avgTinggi =
                                                $item->total_anak > 0 ? $item->sum_tinggi / $item->total_anak : null;
                                            $avgZsBBU =
                                                $item->total_anak > 0 ? $item->sum_zs_bbu / $item->total_anak : null;
                                            $avgZsTBU =
                                                $item->total_anak > 0 ? $item->sum_zs_tbu / $item->total_anak : null;
                                            $avgZsBBTB =
                                                $item->total_anak > 0 ? $item->sum_zs_bbtb / $item->total_anak : null;
                                        @endphp

                                        <tr class="transition hover:bg-gray-50">
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <div class="text-sm font-semibold text-gray-900 uppercase">
                                                    {{ $item->desa_kelurahan }}</div>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                                {{ $item->total_anak }}</td>

                                            {{-- Kolom Rata-rata Baru --}}
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                                {{ !is_null($avgBerat) ? number_format($avgBerat, 2) : '-' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                                {{ !is_null($avgTinggi) ? number_format($avgTinggi, 2) : '-' }}
                                            </td>

                                            {{-- Menggunakan Badge Z-Score Anda untuk Rata-rata --}}
                                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                                <x-zscore-badge :value="$avgZsBBU" />
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                                <x-zscore-badge :value="$avgZsTBU" />
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                                <x-zscore-badge :value="$avgZsBBTB" />
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                                @if ($item->status === 'published')
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                        <span
                                                            class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published
                                                    </span>
                                                @elseif($item->status === 'approved')
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                                        <span
                                                            class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                                        <span
                                                            class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3">
                                                <div class="flex items-center justify-center gap-3">
                                                    <a href="{{ route('gizi.detail', [
                                                        'desa' => $item->desa_kelurahan,
                                                        'period' => $periodYm ?? now()->format('Y-m'),
                                                        'status' => $status,
                                                    ]) }}"
                                                        class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800 hover:underline">
                                                        Detail
                                                    </a>

                                                    <span class="text-gray-300">|</span>

                                                    <form action="{{ route('gizi.destroyAggregated') }}"
                                                        method="POST" x-data>
                                                        @csrf
                                                        <input type="hidden" name="desa_kelurahan"
                                                            value="{{ $item->desa_kelurahan }}">
                                                        <input type="hidden" name="status"
                                                            value="{{ $item->status }}">
                                                        <input type="hidden" name="period"
                                                            value="{{ $periodYm ?? now()->format('Y-m') }}">
                                                        <button type="button"
                                                            @click.prevent="$dispatch('open-confirm', { form: $el.closest('form') })"
                                                            class="text-sm font-medium text-red-600 transition hover:text-red-800 hover:underline">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="px-6 py-12 text-center"> {{-- Sesuaikan colspan jadi 10 --}}
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                
                                                {{-- LOGIKA PESAN DINAMIS BARU --}}
                                                @php
                                                    // Cek total data di periode ini, di SEMUA status
                                                    $totalDataInPeriod = ($statusCounts['pending'] ?? 0) + ($statusCounts['approved'] ?? 0) + ($statusCounts['published'] ?? 0);
                                                @endphp

                                                @if ($totalDataInPeriod === 0)
                                                    {{-- KASUS 1: Tidak ada data sama sekali di periode ini --}}
                                                    <p class="mt-4 text-sm text-gray-500">Data tidak ditemukan untuk periode ini</p>
                                                    
                                                @else
                                                    {{-- KASUS 2: Ada data, tapi tidak di tab ini --}}
                                                    @if ($status === 'pending')
                                                        <p class="mt-4 text-sm text-gray-500">Semua data untuk periode ini sudah diproses (di-approve/publish).</p>
                                                    @elseif ($status === 'approved')
                                                        <p class="mt-4 text-sm text-gray-500">Belum ada data gizi pada periode ini yang di-approve.</p>
                                                    @elseif ($status === 'published')
                                                        <p class="mt-4 text-sm text-gray-500">Belum ada data gizi pada periode ini yang di-publish.</p>
                                                    @endif
                                                @endif
                                                
                                                {{-- Tombol "Tambah Data" HANYA muncul jika di tab 'pending' --}}
                                                @if ($status === 'pending')
                                                    <a href="{{ route('gizi.create', ['period' => $periodYm ?? now()->format('Y-m')]) }}"
                                                        class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        {{-- Ganti teks berdasarkan apakah ada data atau tidak --}}
                                                        {{ $totalDataInPeriod === 0 ? 'Tambah Data Pertama' : 'Tambah Data Lagi' }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ====== Pagination (bawa status & period) ====== --}}
                @php $paginator = $aggregatedData; @endphp {{-- Pastikan ini $aggregatedData --}}
                @if ($paginator->hasPages())
                    <div class="mt-6 sm:mt-8 px-4 sm:px-0">
                        {{-- Mobile --}}
                        <div class="flex sm:hidden flex-col gap-3">
                            <div class="flex items-center justify-center">
                                <nav
                                    class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                    @if ($paginator->onFirstPage())
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $paginator->previousPageUrl() . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </a>
                                    @endif
                                    {{-- halaman pertama --}}
                                    @if ($paginator->currentPage() == 1)
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">1</span>
                                    @else
                                        <a href="{{ $paginator->url(1) . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">1</a>
                                    @endif
                                    @if ($paginator->currentPage() > 2)
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400">...</span>
                                    @endif
                                    @if ($paginator->currentPage() != 1 && $paginator->currentPage() != $paginator->lastPage())
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                            {{ $paginator->currentPage() }}
                                        </span>
                                    @endif
                                    @if ($paginator->currentPage() < $paginator->lastPage() - 1)
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400">...</span>
                                    @endif
                                    @if ($paginator->lastPage() > 1)
                                        @if ($paginator->currentPage() == $paginator->lastPage())
                                            <span
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                                {{ $paginator->lastPage() }}
                                            </span>
                                        @else
                                            <a href="{{ $paginator->url($paginator->lastPage()) . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                                {{ $paginator->lastPage() }}
                                            </a>
                                        @endif
                                    @endif
                                    @if ($paginator->hasMorePages())
                                        <a href="{{ $paginator->nextPageUrl() . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status }}"
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
                            <div class="flex items-center justify-center gap-2 mt-3">
                                <span class="text-sm text-gray-500">Go to:</span>
                                <form method="GET" action="{{ $paginator->url(1) }}"
                                    class="flex items-center gap-2"
                                    onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $paginator->lastPage() }}) window.location.href = this.action.replace('page=1', 'page=' + page) + '&period={{ $periodYm ?? now()->format('Y-m') }}&status={{ $status }}';">
                                    <input type="number" name="page" min="1"
                                        max="{{ $paginator->lastPage() }}"
                                        placeholder="{{ $paginator->currentPage() }}"
                                        class="w-16 px-3 py-2 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">Go</button>
                                </form>
                            </div>
                        </div>

                        {{-- Desktop --}}
                        <div class="hidden sm:flex flex-col items-center gap-3">
                            <nav
                                class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                @if ($paginator->onFirstPage())
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-300 cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $paginator->previousPageUrl() . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status }}"
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- angka halaman --}}
                                @foreach ($paginator->links()->elements as $element)
                                    @if (is_string($element))
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-400">...</span>
                                    @endif
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @php $urlWithParams = $url . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status; @endphp
                                            @if ($page == $paginator->currentPage())
                                                <span
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $page }}</span>
                                            @else
                                                <a href="{{ $urlWithParams }}"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                @if ($paginator->hasMorePages())
                                    <a href="{{ $paginator->nextPageUrl() . '&period=' . ($periodYm ?? now()->format('Y-m')) . '&status=' . $status }}"
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-md text-gray-300 cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>

                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-sm text-gray-500">Go to page:</span>
                                <form method="GET" action="{{ $paginator->url(1) }}"
                                    class="flex items-center gap-2"
                                    onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $paginator->lastPage() }}) window.location.href = this.action.replace('page=1', 'page=' + page) + '&period={{ $periodYm ?? now()->format('Y-m') }}&status={{ $status }}';">
                                    <input type="number" name="page" min="1"
                                        max="{{ $paginator->lastPage() }}"
                                        placeholder="{{ $paginator->currentPage() }}"
                                        class="w-20 px-3 py-1.5 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit"
                                        class="px-4 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">Go</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-4 text-center">
                            <p class="text-xs sm:text-sm text-gray-500">
                                Menampilkan
                                <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
                                sampai
                                <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
                                dari
                                <span class="font-medium text-gray-700">{{ $paginator->total() }}</span>
                                record desa/kelurahan {{-- Teks dikembalikan seperti semula --}}
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
