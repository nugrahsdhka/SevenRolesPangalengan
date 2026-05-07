{{-- resources/views/public_view/dataStunting/index.blade.php --}}
<x-app-layout>
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
    @endphp

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section class="max-w-7xl mx-auto px-2">
            
            {{-- ========== HEADER: TITLE + VIEW SWITCHER ========== --}}
            <div class="py-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                            
                            {{-- Title Section --}}
                            <div class="flex flex-col justify-center items-center md:items-start md:justify-start">
                                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                                    Data <span class="text-red-600">Stunting</span>
                                </h1>
                                <p class="hidden md:block mt-1 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                                    Informasi Gizi & Statistik Stunting Anak per Desa
                                </p>
                            </div>

                            {{-- View Switcher (Segmented Control) --}}
                            <div class="inline-flex justify-center p-1.5 bg-gray-100 rounded-[2rem] shadow-inner">
                                <a href="{{ $tableUrl }}" 
                                   class="flex items-center gap-2 px-8 py-3 rounded-[1.8rem] text-sm font-black uppercase tracking-widest transition-all duration-300 {{ $currentView === 'table' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Tabel
                                </a>
                                <a href="{{ $chartUrl }}" 
                                   class="flex items-center gap-2 px-8 py-3 rounded-[1.8rem] text-sm font-black uppercase tracking-widest transition-all duration-300 {{ $currentView === 'chart' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Chart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== CONTENT AREA: TABEL / CHART ========== --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-2 md:p-4">
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

    <x-goToTop />
</x-app-layout>