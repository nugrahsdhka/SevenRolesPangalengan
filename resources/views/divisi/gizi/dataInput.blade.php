<div class="space-y-2">
    {{-- ========== HEADER CARD: JUDUL & AKSI UTAMA ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                {{-- Kiri: Judul & Info Periode --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-red-100 text-red-600 rounded-2xl">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase leading-none">
                                Data <span class="text-red-600">Diinput </span>
                            </h1>
                            @php
                                $activePeriodYm = $periodYm ?? now()->format('Y-m');
                                $activePeriodLabel = 'Semua Periode';
                                if (!empty($availablePeriods)) {
                                    foreach ($availablePeriods as $p) {
                                        if (\Carbon\Carbon::parse($p)->format('Y-m') === $activePeriodYm) {
                                            $activePeriodLabel = \Carbon\Carbon::parse($p)->isoFormat('MMMM Y');
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <p class="mt-1.5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                Periode Aktif: <span class="text-gray-700">{{ $activePeriodLabel }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Tombol Aksi --}}
                <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                    {{-- Tombol Export --}}
                    <button type="button" 
                    onclick="downloadExcelFile('{{ route('gizi.export', ['period' => $activePeriodYm, 'status' => $status]) }}', 'Data_Gizi_{{ strtoupper($status) }}_{{ $activePeriodYm }}.xlsx')"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-50 text-emerald-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 hover:text-white shadow-sm hover:shadow-lg transition-all active:scale-95 border border-emerald-100 hover:border-emerald-600 group">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export Excel
                    </button>

                    {{-- Tombol Tambah --}}
                    <a href="{{ route('gizi.index', ['view' => 'tambah', 'period' => $activePeriodYm, 'status' => 'pending']) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95 group">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah / Import Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TAB NAVIGATION & PERIODE FILTER ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            {{-- Modern Segmented Control for Status --}}
            <nav class="flex p-1.5 bg-gray-100 rounded-[2rem] shadow-inner w-full lg:w-auto overflow-x-auto no-scrollbar">
                @foreach ($tabItems as $key => $meta)
                    @php $active = ($status === $key); @endphp
                    <a href="{{ route('gizi.index', ['view' => 'data', 'period' => $activePeriodYm, 'status' => $key]) }}"
                       class="flex items-center gap-3 px-6 py-2.5 rounded-[1.8rem] text-[10px] font-black uppercase tracking-widest transition-all duration-300 whitespace-nowrap
                       {{ $active ? 'bg-white ' . $meta['text'] . ' shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                        
                        <span class="relative flex h-2 w-2">
                            @if ($active && ($counts[$key] ?? 0) > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $meta['dot'] }} opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $meta['dot'] }}"></span>
                        </span>
                        {{ $meta['label'] }}
                        <span class="ml-1 px-2 py-0.5 rounded-lg {{ $active ? $meta['badge_bg'] : 'bg-gray-200 text-gray-500' }}">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </nav>

            {{-- Period Filter --}}
            <form method="GET" action="{{ route('gizi.index') }}" class="flex items-center gap-3 w-full lg:w-auto">
                <input type="hidden" name="view" value="data" />
                <input type="hidden" name="status" value="{{ $status ?? 'pending' }}" />
                <div class="relative flex-1 lg:flex-none">
                    <select name="period" required
                            class="w-full lg:w-56 pl-4 pr-10 py-3 bg-gray-50 border-none rounded-2xl text-xs font-black text-gray-700 uppercase tracking-widest focus:ring-2 focus:ring-red-500 transition-all appearance-none cursor-pointer">
                        @forelse($availablePeriods ?? [] as $p)
                            @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                            <option value="{{ $ym }}" {{ ($periodYm ?? $activePeriodYm) === $ym ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                            </option>
                        @empty
                            <option value="{{ now()->format('Y-m') }}">{{ now()->isoFormat('MMMM Y') }}</option>
                        @endforelse
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
                <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-600 transition-all shadow-lg active:scale-95">
                    Ganti
                </button>
            </form>
        </div>
    </div>
    
    {{-- ========== STICKY TABLE CARD ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col max-h-[70vh]">
        <div class="overflow-auto scrollbar-hide">
            <table class="w-full min-w-[1200px] border-separate border-spacing-0">
                <thead class="sticky top-0 z-30">
                    <tr class="bg-gray-50 uppercase border-b border-gray-100">
                        <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em]">Desa/Kelurahan</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Total Anak</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Avg BB & TB</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Avg ZS BB/U</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Avg ZS TB/U</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Avg ZS BB/TB</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Petugas Input</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Status</th>
                        <th class="px-6 py-5 text-right text-[10px] font-black text-gray-400 tracking-[0.2em]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($aggregatedData as $item)
                        @php
                            $avgBerat = $item->total_anak > 0 ? $item->sum_berat / $item->total_anak : null;
                            $avgTinggi = $item->total_anak > 0 ? $item->sum_tinggi / $item->total_anak : null;
                        @endphp
                        <tr class="group hover:bg-red-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-gray-900 group-hover:text-red-600 transition-colors uppercase leading-none">{{ $item->desa_kelurahan }}</span>
                                <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase">{{ \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMM Y') }}</p>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-lg">{{ $item->total_anak }} Anak</span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-xs font-black text-gray-700">{{ number_format($avgBerat, 1) }} <small class="text-[9px] text-gray-400 uppercase">kg</small></span>
                                    <span class="text-xs font-black text-gray-700">{{ number_format($avgTinggi, 1) }} <small class="text-[9px] text-gray-400 uppercase">cm</small></span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center"><x-zscore-badge :value="$item->sum_zs_bbu / ($item->total_anak ?: 1)" /></td>
                            <td class="px-4 py-5 text-center"><x-zscore-badge :value="$item->sum_zs_tbu / ($item->total_anak ?: 1)" /></td>
                            <td class="px-4 py-5 text-center"><x-zscore-badge :value="$item->sum_zs_bbtb / ($item->total_anak ?: 1)" /></td>
                            <td class="px-4 py-5 text-center">
                                <span class="text-[10px] font-black text-gray-600 uppercase leading-tight">{{ $item->user->name ?? 'System' }}</span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                @php
                                    $statusStyle = match($item->status) {
                                        'published' => 'bg-emerald-100 text-emerald-700',
                                        'approved' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $statusStyle }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('gizi.index', ['view' => 'detail', 'desa' => $item->desa_kelurahan, 'period' => $activePeriodYm]) }}"
                                       class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all shadow-sm border border-indigo-100 group/btn">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
    
                                    @if (in_array($item->status, ['pending', 'need_review']))
                                        <form action="{{ route('gizi.destroyAggregated') }}" method="POST" x-data>
                                            @csrf
                                            <input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}">
                                            <input type="hidden" name="status" value="{{ $item->status }}">
                                            <input type="hidden" name="period" value="{{ $periodYm }}">
                                            <button type="button" 
                                                    @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Hapus Data?', message: 'Data desa {{ $item->desa_kelurahan }} akan dihapus permanen.' })"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-all shadow-sm border border-red-100">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p class="text-xs font-black uppercase tracking-widest text-gray-500">Belum ada data untuk ditampilkan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Styling khusus agar sticky header tidak patah saat scroll */
    .border-separate { border-collapse: separate; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Hover effect row */
    .group:hover td { @apply border-red-100; }
</style>

<script>
    async function downloadExcelFile(url, filename) {
        try {
            // Karena menggunakan fetch, AppLoader Anda akan otomatis menyala
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memproses file dari server');
            }

            // Ubah response menjadi bentuk Blob (file biner)
            const blob = await response.blob();

            // Buat URL sementara untuk file Blob
            const windowUrl = window.URL || window.webkitURL;
            const downloadUrl = windowUrl.createObjectURL(blob);

            // Buat elemen <a> virtual untuk memicu download di browser
            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            
            // Bersihkan elemen virtual dan memori
            a.remove();
            windowUrl.revokeObjectURL(downloadUrl);

            // AppLoader Anda akan otomatis mati setelah blok try/finally selesai dijalankan
            
        } catch (error) {
            console.error('Error saat export:', error);
            alert('Terjadi kesalahan saat mengunduh file Excel. Silakan coba lagi.');
        }
    }
</script>