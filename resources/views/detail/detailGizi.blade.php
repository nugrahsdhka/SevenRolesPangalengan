<x-app-layout>
    <x-slot name="title">
        RSP | Detail Gizi {{ strtoupper($desaKelurahan) }}
    </x-slot>

    @php
        $periodYm = $periodYm ?? now()->format('Y-m');
        try {
            $currentPeriodLabel = \Carbon\Carbon::createFromFormat('Y-m', $periodYm)->isoFormat('MMMM Y');
        } catch (\Exception $e) {
            $currentPeriodLabel = now()->isoFormat('MMMM Y');
        }

        // Logika URL Kembali
        $from = request('from');
        $status = request('status', 'approved');
        $period = $periodYm ?? request('period');

        if ($from === 'admin' || auth()->user()->hasRole('IT Admin')) {
            $backUrl = route('admin.published.index', ['active_tab' => 'gizi', 'status' => $status, 'period' => $period]);
        } elseif ($from === 'camat' || auth()->user()->hasRole('Camat')) {
            $backUrl = route('camat.review', ['active_tab' => 'gizi', 'status' => $status, 'period' => $period]);
        } else {
            $backUrl = url()->previous() ?: route('dashboard');
        }
    @endphp

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- ========== HEADER SECTION ========== --}}
            <div class="py-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300">
                    <div class="p-2">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                            
                            {{-- Title & Back Button --}}
                            <div class="flex items-center gap-4">
                                <a href="{{ $backUrl }}" 
                                   class="p-3 bg-gray-100 text-gray-500 rounded-2xl hover:bg-red-600 hover:text-white transition-all shadow-sm group">
                                    <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                                <div>
                                    <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase leading-none">
                                        Detail <span class="text-red-600">Monitoring</span>
                                    </h1>
                                    <p class="mt-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                        Desa: <span class="text-gray-700">{{ $desaKelurahan }}</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Period Badge --}}
                            <div class="flex items-center gap-3 px-6 py-3 bg-white rounded-2xl shadow-lg shadow-gray-200">
                                <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest leading-none">Periode:</span>
                                <span class="text-sm font-black text-gray-600 uppercase leading-none">{{ $currentPeriodLabel }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== QUICK STATS GRID ========== --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-2">
                @php
                    $stats = [
                        ['label' => 'Total Anak', 'val' => $statistik->total_anak ?? 0, 'unit' => 'Jiwa', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                        ['label' => 'Kasus Stunting', 'val' => $statistik->total_stunting ?? 0, 'unit' => 'Kasus', 'color' => 'text-red-600', 'bg' => 'bg-red-50'],
                        ['label' => 'Avg Tinggi', 'val' => number_format($statistik->avg_tinggi ?? 0, 1), 'unit' => 'CM', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                    ];
                @endphp

                @foreach($stats as $s)
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center text-center transition-transform hover:scale-[1.03]">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">{{ $s['label'] }}</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-black {{ $s['color'] }} tracking-tighter">{{ $s['val'] }}</span>
                        <span class="text-[8px] font-black text-gray-300 uppercase">{{ $s['unit'] }}</span>
                    </div>
                </div>
                @endforeach

                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center justify-center transition-transform hover:scale-[1.03]">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Avg Z-Score TB/U</span>
                    <div class="scale-110">
                        @if ($statistik && !is_null($statistik->avg_zs_tbu))
                            <x-zscore-badge :value="$statistik->avg_zs_tbu" />
                        @else
                            <span class="text-gray-300 font-black">-</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ========== DATA TABLE SECTION ========== --}}
            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col transition-all">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-white">
                    <div class="flex items-center gap-3">
                        <span class="w-2.5 h-2.5 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.5)]"></span>
                        <h5 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em]">Daftar Riwayat Individu</h5>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Urutan Berdasarkan Input</span>
                    </div>
                </div>

                <div class="overflow-x-auto scrollbar-hide border-separate border-spacing-0 max-h-[60vh]">
                    <table class="w-full min-w-[1100px]">
                        <thead class="sticky top-0 z-20">
                            <tr class="bg-gray-50/80 backdrop-blur-md">
                                <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">No</th>
                                <th class="px-4 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Nama Lengkap Anak</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">B (kg) / T (cm)</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Indikator Z-Score</th>
                                <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Status Gizi</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 bg-gray-50/50">Record</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            @forelse ($dataGizi as $index => $row)
                                <tr class="group hover:bg-red-50/40 transition-all duration-300">
                                    <td class="px-8 py-6 text-xs font-black text-gray-300 group-hover:text-red-500 transition-colors">
                                        {{ ($dataGizi->firstItem() + $index) }}
                                    </td>
                                    <td class="px-4 py-6">
                                        <p class="text-sm font-black text-gray-900 uppercase group-hover:text-red-600 transition-colors leading-tight">
                                            {{ $row->nama }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-2xl border border-gray-100 group-hover:bg-white group-hover:border-red-100 transition-all">
                                            <span class="text-xs font-black text-gray-800">{{ number_format($row->berat, 1) }}</span>
                                            <span class="text-[10px] font-bold text-gray-300">/</span>
                                            <span class="text-xs font-black text-gray-800">{{ number_format($row->tinggi, 1) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-6">
                                        <div class="flex items-center justify-center gap-6 scale-90">
                                            <div class="flex flex-col items-center gap-1">
                                                <x-zscore-badge :value="$row->zs_bb_u" />
                                                <span class="text-[7px] font-black text-gray-400 uppercase tracking-tighter">BB/U</span>
                                            </div>
                                            <div class="flex flex-col items-center gap-1">
                                                <x-zscore-badge :value="$row->zs_tb_u" />
                                                <span class="text-[7px] font-black text-red-400 uppercase tracking-tighter">TB/U</span>
                                            </div>
                                            <div class="flex flex-col items-center gap-1">
                                                <x-zscore-badge :value="$row->zs_bb_tb" />
                                                <span class="text-[7px] font-black text-gray-400 uppercase tracking-tighter">BB/TB</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-6 text-center">
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-tighter bg-gray-100 text-gray-600 border border-gray-200 group-hover:bg-red-600 group-hover:text-white group-hover:border-red-600 transition-all">
                                            {{ $row->status_tb_u }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter leading-none mb-1">
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}
                                        </p>
                                        <p class="text-[9px] font-medium text-gray-300 uppercase leading-none">
                                            {{ \Carbon\Carbon::parse($row->created_at)->format('H:i') }} WIB
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-32 text-center">
                                        <div class="flex flex-col items-center opacity-40">
                                            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500 italic">Data belum tersedia pada periode ini</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER / PAGINATION --}}
                @if ($dataGizi && $dataGizi->hasPages())
                    <div class="px-10 py-6 border-t border-gray-50 bg-white pagination-rsp">
                        {{ $dataGizi->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Custom Pagination Red Theme */
        .pagination-rsp nav span[aria-current="page"] span {
            @apply bg-red-600 border-red-600 rounded-xl font-black text-xs text-white shadow-lg shadow-red-200 !important;
        }
        .pagination-rsp nav a, .pagination-rsp nav span {
            @apply rounded-xl border-gray-200 text-xs font-black uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all !important;
        }
    </style>
</x-app-layout>