{{-- resources/views/public_view/dataStunting/index.blade.php --}}
<x-app-layout>
    {{-- ======================= TITLE ======================= --}}
    <x-slot name="title">
        RSP | Data Stunting
    </x-slot>

    {{-- ======================= NAV PUBLIC ======================= --}}
    @include('layouts.nav_public')

    @php
        // Ambil semua query param, lalu pakai untuk build URL tab
        $queryParams = request()->query();

        $tableUrl = request()->fullUrlWithQuery(array_merge($queryParams, [
            'view' => 'table',
        ]));

        $chartUrl = request()->fullUrlWithQuery(array_merge($queryParams, [
            'view' => 'chart',
        ]));
    @endphp

    {{-- ======================= TAB NAVIGATION (TABEL / CHART) ======================= --}}
    <div class="border-b border-gray-200 bg-gray-50">
        <div class="flex">
            {{-- Tombol Tab Tabel --}}
            <a href="{{ $tableUrl }}"
               id="tab-button-table"
               data-target="tab-table"
               class="relative flex-1 px-6 py-4 text-sm font-semibold focus:outline-none group transition-colors duration-200
                    {{ $currentView === 'table'
                        ? 'text-gray-900 bg-white'
                        : 'text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Tabel
                </div>
                <div
                    class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-pink-600 {{ $currentView !== 'table' ? 'hidden' : '' }}">
                </div>
            </a>

            {{-- Tombol Tab Chart --}}
            <a href="{{ $chartUrl }}"
               id="tab-button-chart"
               data-target="tab-chart"
               class="relative flex-1 px-6 py-4 text-sm font-semibold focus:outline-none group transition-colors duration-200
                    {{ $currentView === 'chart'
                        ? 'text-gray-900 bg-white'
                        : 'text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Chart
                </div>
                <div
                    class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-pink-600 {{ $currentView !== 'chart' ? 'hidden' : '' }}">
                </div>
            </a>
        </div>
    </div>

    {{-- ======================= KONTEN: HANYA RENDER YANG AKTIF ======================= --}}
    @if ($currentView === 'table')
        {{-- TAB: TABLE (FILTER + TABEL + MODAL INFO) --}}
        @include('public.dataStunting.table', [
            'currentView'  => $currentView,
            'search'       => $search ?? '',
            'tingkat_zs'   => $tingkat_zs ?? '',
            'dataStunting' => $dataStunting,
        ])
    @elseif ($currentView === 'chart')
        {{-- TAB: CHART (CHART + MODAL INFO) --}}
        @include('public.dataStunting.chart', [
            'currentView' => $currentView,
        ])
    @endif

    {{-- ======================= SCRIPT: CHART / MODAL ======================= --}}

</x-app-layout>
