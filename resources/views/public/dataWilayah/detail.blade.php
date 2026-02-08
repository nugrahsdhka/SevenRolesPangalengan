<x-guest-layout>
    <x-slot name="title">
        RSP | Detail Data Wilayah
    </x-slot>

    @php
        $desa = $desa ?? request('desa');
        $periodYm = $periodYm ?? request('period');

        if (!empty($periodYm)) {
            try {
                $periodLabel = \Carbon\Carbon::createFromFormat('Y-m', $periodYm)->isoFormat('MMMM Y');
            } catch (\Exception $e) { 
                $periodLabel = $periodYm; 
            }
        } else {
            $periodLabel = 'Semua Periode';
        }

        $filterPosyandu = $filterPosyandu ?? request('posyandu');
        $filterRt       = $filterRt ?? request('rt');
        $filterRw       = $filterRw ?? request('rw');
    @endphp

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section id="detail-wilayah" class="max-w-7xl mx-auto px-2">
            
            {{-- ========== HEADER INFO & FILTER ========== --}}
            <div class="py-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                            
                            {{-- Identity Section --}}
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('data-wilayah', ['period' => $periodYm]) }}" 
                                       class="p-3 bg-gray-100 text-gray-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all shadow-sm group">
                                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                    <div>
                                        <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase leading-none">
                                            Detail <span class="text-red-600">Wilayah</span>
                                        </h1>
                                        <p class="mt-2 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">Data Agregat per Anak</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <div class="flex w-full md:w-auto items-center gap-2 px-5 py-2.5 bg-red-50 rounded-2xl border border-red-100">
                                        <span class="text-[10px] font-black text-red-400 uppercase tracking-widest leading-none">Wilayah</span>
                                        <span class="text-sm font-black text-red-700 uppercase leading-none">{{ $desa ?? '-' }}</span>
                                    </div>
                                    <div class="flex w-full md:w-auto items-center gap-2 px-5 py-2.5 bg-gray-900 rounded-2xl border border-gray-800">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Periode</span>
                                        <span class="text-sm font-black text-white uppercase leading-none">{{ $periodLabel }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Filter Toolbar --}}
                            <div class="w-full lg:w-auto">
                                <form action="{{ route('data-wilayah-detail-gizi') }}" method="GET" 
                                      class="grid grid-cols-2 md:flex md:items-end gap-3">
                                    <input type="hidden" name="desa" value="{{ $desa }}">
                                    <input type="hidden" name="period" value="{{ $periodYm }}">

                                    <div class="col-span-2 md:w-44">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Posyandu</label>
                                        <select name="posyandu" class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all cursor-pointer">
                                            <option value="">Semua Posyandu</option>
                                            @foreach ($posyanduOptions ?? [] as $pos)
                                                <option value="{{ $pos }}" @selected($filterPosyandu === $pos)>{{ $pos }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full md:w-20">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1 text-center">RT</label>
                                        <select name="rt" class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all">
                                            <option value="">RT</option>
                                            @foreach ($rtOptions ?? [] as $rt)
                                                <option value="{{ $rt }}" @selected($filterRt === $rt)>{{ $rt }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full md:w-20">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1 text-center">RW</label>
                                        <select name="rw" class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all">
                                            <option value="">RW</option>
                                            @foreach ($rwOptions ?? [] as $rw)
                                                <option value="{{ $rw }}" @selected($filterRw === $rw)>{{ $rw }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-span-2 md:w-auto">
                                        <button type="submit" class="w-full px-8 py-3 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95">
                                            Filter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== STICKY TABLE CONTAINER ========== --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col max-h-[85vh]">
                
                {{-- Area Scrollable --}}
                <div class="overflow-auto scrollbar-hide">
                    <table class="w-full min-w-[1200px] border-separate border-spacing-0">
                        <thead class="sticky top-0 z-30">
                            <tr class="bg-gray-50">
                                {{-- Kolom No (Sticky) --}}
                                <th class="sticky left-0 top-0 z-40 bg-gray-50 px-6 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200">
                                    NO
                                </th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase">Nama Anak</th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase">Posyandu</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase">RT/RW</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase">Tgl Lahir</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase whitespace-nowrap">B (kg) / T (cm)</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase">ZS BB/U</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase">ZS TB/U</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-200 uppercase whitespace-nowrap">ZS BB/TB</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($dataAnak as $index => $row)
                                <tr class="group hover:bg-red-50/30 transition-colors">
                                    <td class="sticky left-0 z-10 bg-white group-hover:bg-red-50/30 px-6 py-5 text-xs font-black text-gray-400 border-r border-gray-50 transition-colors">
                                        {{ ($dataAnak->firstItem() ?? 0) + $index }}
                                    </td>
                                    <td class="px-4 py-5">
                                        <p class="text-sm font-black text-gray-900 group-hover:text-red-600 transition-colors uppercase leading-tight">
                                            {{ $row->nama ?? '-' }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-5">
                                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $row->posyandu ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-lg">RT {{ $row->rt }} / RW {{ $row->rw }}</span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="text-xs font-bold text-gray-500">
                                            {{ $row->tgl_lahir ? \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <span class="text-xs font-black text-gray-800">{{ $row->berat ?? '-' }}</span>
                                            <span class="text-[10px] font-bold text-gray-300">/</span>
                                            <span class="text-xs font-black text-gray-800">{{ $row->tinggi ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        @include('components.zscore-badge', ['value' => $row->zs_bb_u])
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        @include('components.zscore-badge', ['value' => $row->zs_tb_u])
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        @include('components.zscore-badge', ['value' => $row->zs_bb_tb])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-6 bg-gray-50 rounded-full mb-4 text-gray-300">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-400 font-black uppercase text-xs tracking-widest">Tidak Ada Data Ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if (isset($dataAnak) && $dataAnak instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 mt-auto">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Menampilkan <span class="text-red-600">{{ $dataAnak->firstItem() }} - {{ $dataAnak->lastItem() }}</span> dari {{ $dataAnak->total() }} Anak
                            </div>
                            <div class="pagination-rsp">
                                {{ $dataAnak->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <style>
        /* Custom Pagination Red Theme */
        .pagination-rsp nav span[aria-current="page"] span {
            @apply bg-red-600 border-red-600 rounded-xl font-black text-xs text-white shadow-lg shadow-red-200 !important;
        }
        .pagination-rsp nav a, .pagination-rsp nav span {
            @apply rounded-xl border-gray-200 text-xs font-black uppercase tracking-tighter hover:bg-red-50 hover:text-red-600 transition-all !important;
        }

        /* Hilangkan scrollbar tapi tetap bisa scroll */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-guest-layout>