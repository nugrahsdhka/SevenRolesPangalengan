<x-guest-layout>
    <x-slot name="title">
        RSP | Data Wilayah
    </x-slot>

    @include('layouts.nav_public')

    @php
        $availablePeriods = $availablePeriods ?? collect();
        $selectedPeriod = $selectedPeriod ?? request('period');
        $search = $search ?? request()->query('search');
    @endphp

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section id="tab-wilayah" class="max-w-7xl mx-auto px-2">

            {{-- ========== HEADER: TITLE + FILTER ========== --}}
            <div class="py-2">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">

                            {{-- Title Section --}}
                            <div class="flex flex-col justify-center items-center md:items-start md:justify-start">
                                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                                    Data <span class="text-primary-500">Wilayah</span>
                                </h1>
                                <p class="hidden md:block mt-1 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                                    Kecamatan Pangalengan • Kabupaten Bandung
                                </p>
                            </div>

                            {{-- Filter Section --}}
                            <div class="w-full lg:w-auto">
                                <form action="{{ route('data-wilayah') }}" method="GET"
                                      class="flex flex-col sm:flex-row items-end gap-4">

                                    {{-- Periode --}}
                                    <div class="w-full sm:w-48">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                            Periode Laporan
                                        </label>
                                        <select name="period"
                                                class="w-full px-4 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all cursor-pointer hover:bg-white">
                                            <option value="" @selected(empty($selectedPeriod))>Semua Periode</option>
                                            @foreach ($availablePeriods as $ym)
                                                @php
                                                    try {
                                                        $label = \Carbon\Carbon::createFromFormat('Y-m', $ym)->isoFormat('MMMM Y');
                                                    } catch (\Exception $e) { $label = $ym; }
                                                @endphp
                                                <option value="{{ $ym }}" @selected($selectedPeriod === $ym)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Search --}}
                                    <div class="w-full sm:w-64">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                            Cari Desa
                                        </label>
                                        <div class="relative group">
                                            <input type="text" name="search" value="{{ $search }}"
                                                   placeholder="Ketik nama desa..."
                                                   class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all hover:bg-white">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit"
                                            class="w-full sm:w-auto px-8 py-3 bg-primary-500 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-primary-600 hover:shadow-lg hover:shadow-primary-200/50 active:scale-95 transition-all">
                                        Filter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== TABEL DATA ========== --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100">
                                <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Desa/Kelurahan</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tinggi (cm)</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Berat (kg)</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Populasi</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ZS BB/U</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ZS TB/U</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ZS BB/TB</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100/80">
                            @forelse ($dataWilayah as $data)
                                @php
                                    $avgZsBbU = $data->total_anak > 0 ? ($data->sum_zs_bbu / $data->total_anak) : null;
                                    $avgZsTbU = $data->total_anak > 0 ? ($data->sum_zs_tbu / $data->total_anak) : null;
                                    $avgZsBbTb = $data->total_anak > 0 ? ($data->sum_zs_bbtb / $data->total_anak) : null;
                                @endphp
                                <tr class="group hover:bg-primary-50/40 transition-all duration-300 cursor-pointer">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-xs font-black text-primary-600">{{ substr($data->desa_kelurahan, 0, 1) }}</span>
                                            </div>
                                            <span class="text-sm font-black text-gray-900 group-hover:text-primary-600 transition-colors">{{ $data->desa_kelurahan }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">{{ number_format($data->avg_tb, 1) }}</span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">{{ number_format($data->avg_bb, 1) }}</span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">{{ number_format($data->total_anak) }} Anak</span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <x-zscore-badge :value="$avgZsBbU" />
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <x-zscore-badge :value="$avgZsTbU" />
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <x-zscore-badge :value="$avgZsBbTb" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-20">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Tidak ada Data Ditemukan</h3>
                                            <p class="text-gray-300 text-xs mt-1">Coba ubah filter atau periode pencarian</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <x-goToTop />

</x-guest-layout>