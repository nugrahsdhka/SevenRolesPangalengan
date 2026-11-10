{{-- resources/views/public_view/dataStunting/index.blade.php --}}
<x-guest-layout>
    <x-slot name="title">
        RSP | Data Stunting
    </x-slot>

    {{-- ======================= NAV PUBLIC ======================= --}}
    @include('layouts.nav_public')

    @php
        $queryParams = request()->query();
        $tableUrl = request()->fullUrlWithQuery(array_merge($queryParams, ['view' => 'table']));
        $chartUrl = request()->fullUrlWithQuery(array_merge($queryParams, ['view' => 'chart']));
        $currentView = $currentView ?? 'table';

        $tabBase   = 'flex flex-1 lg:flex-none items-center justify-center gap-2 whitespace-nowrap rounded-full lg:rounded-[1.8rem] px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 text-xs sm:text-sm font-black uppercase tracking-wider sm:tracking-widest transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500';
        $tabActive = 'bg-white text-primary-500 shadow-sm';
        $tabIdle   = 'text-gray-400 hover:text-gray-600';
    @endphp

    <div class="min-h-screen bg-gray-50/50 pb-8 sm:pb-12">
        <section class="mx-auto w-full max-w-7xl px-3 sm:px-4 lg:px-8 space-y-3 sm:space-y-4">

            {{-- ========== HEADER: TITLE + VIEW SWITCHER ========== --}}
            <div class="pt-3 sm:pt-4">
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 lg:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 sm:gap-6 lg:gap-8">

                            {{-- Title Section --}}
                            <div class="min-w-0 text-center lg:text-left">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 tracking-tight uppercase leading-tight">
                                    Data <span class="text-primary-500">Stunting</span>
                                </h1>
                                <p class="mt-1 text-gray-500 font-bold uppercase text-[9px] sm:text-[10px] tracking-[0.12em] sm:tracking-[0.2em] leading-relaxed">
                                    Informasi Gizi & Statistik Stunting Anak per Desa
                                </p>
                            </div>

                            {{-- View Switcher (Segmented Control) --}}
                            <nav aria-label="Pilih tampilan data"
                                 class="flex w-full lg:w-auto p-1 sm:p-1.5 bg-gray-100 rounded-full lg:rounded-[2rem] shadow-inner">
                                <a href="{{ $tableUrl }}"
                                   @if ($currentView === 'table') aria-current="page" @endif
                                   class="{{ $tabBase }} {{ $currentView === 'table' ? $tabActive : $tabIdle }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Tabel
                                </a>
                                <a href="{{ $chartUrl }}"
                                   @if ($currentView === 'chart') aria-current="page" @endif
                                   class="{{ $tabBase }} {{ $currentView === 'chart' ? $tabActive : $tabIdle }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Chart
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== CONTENT AREA: TABEL / CHART ========== --}}
            {{-- min-w-0 mencegah child (tabel/chart) melebar melewati viewport --}}
            <div class="min-w-0 bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="min-w-0 p-2 sm:p-4 lg:p-6">
                    @if ($currentView === 'table')
                        @include('public.dataStunting.table', [
                            'currentView'  => $currentView,
                            'search'       => $search ?? '',
                            'tingkat_zs'   => $tingkat_zs ?? '',
                            'dataStunting' => $dataStunting,
                        ])
                    @elseif ($currentView === 'chart')
                        @include('public.dataStunting.chart', [
                            'currentView' => $currentView,
                        ])
                    @endif
                </div>
            </div>

        </section>
    </div>
</x-guest-layout>