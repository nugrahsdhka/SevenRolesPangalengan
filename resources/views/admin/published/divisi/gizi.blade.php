@php
    // ============================== PERIODE ==============================
    if (!empty($periodYm ?? null)) {
        try {
            $currentPeriodLabel = \Carbon\Carbon::createFromFormat('Y-m', $periodYm)->isoFormat('MMMM Y');
        } catch (\Exception $e) {
            $currentPeriodLabel = now()->isoFormat('MMMM Y');
        }
    } else {
        $currentPeriodLabel = now()->isoFormat('MMMM Y');
        $periodYm = now()->format('Y-m');
    }

    // ============================== STATUS TAB ==============================
    $status = $status ?? request('status', 'approved');

    $tabItems = [
        'pending' => [
            'label' => 'Pending',
            'text' => 'text-amber-600',
            'dot' => 'bg-amber-500',
            'badge_bg' => 'bg-amber-100',
        ],
        'approved' => [
            'label' => 'Approved',
            'text' => 'text-blue-600',
            'dot' => 'bg-blue-500',
            'badge_bg' => 'bg-blue-100',
        ],
        'published' => [
            'label' => 'Published',
            'text' => 'text-emerald-600',
            'dot' => 'bg-emerald-500',
            'badge_bg' => 'bg-emerald-100',
        ],
    ];

    $hasApproved = $bulkActions['has_approved'] ?? false;
    $hasPublished = $bulkActions['has_published'] ?? false;
    $soloMode = ($hasApproved xor $hasPublished);
@endphp

<div class="space-y-4">

    {{-- ================= HEADER CARD: JUDUL & FILTER PERIODE ================= --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 overflow-hidden transition-all duration-300">
        <div class="p-4 md:p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                {{-- Kiri: Judul --}}
                <div class="space-y-1 text-center lg:text-left">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase leading-none">
                        Review <span class="text-primary-500">Data Gizi</span>
                    </h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Periode Aktif: <span class="text-gray-700">{{ $currentPeriodLabel }}</span>
                    </p>
                </div>

                {{-- Kanan: Filter Periode --}}
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                    {{-- Info Prevalensi --}}
                    <div class="bg-primary-50/80 border border-primary-100/60 px-6 py-3 rounded-2xl flex items-center gap-3 backdrop-blur-sm">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-primary-400 uppercase tracking-widest">Prevalensi Kec.</span>
                            <span class="text-sm font-black text-primary-700 tracking-tight leading-none">
                                {{ number_format($totalPrevalensiKecamatan ?? 0, 2) }}%
                            </span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.published.index') }}" class="flex items-center gap-3 w-full lg:w-auto">
                        <input type="hidden" name="active_tab" value="gizi">
                        <input type="hidden" name="status" value="{{ $status }}">
                        
                        <div class="relative flex-1 lg:flex-none">
                            <select name="period" required
                                    class="w-full lg:w-56 pl-5 pr-10 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-[11px] font-black text-gray-700 uppercase tracking-widest focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all appearance-none cursor-pointer hover:bg-white">
                                @forelse($availablePeriods ?? [] as $p)
                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                    <option value="{{ $ym }}" {{ ($periodYm ?? now()->format('Y-m')) === $ym ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                    </option>
                                @empty
                                    <option value="{{ now()->format('Y-m') }}">{{ now()->isoFormat('MMMM Y') }}</option>
                                @endforelse
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-primary-500 transition-all shadow-lg shadow-gray-200/50 hover:shadow-primary-200/50 active:scale-95">
                            Ganti
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= TAB STATUS & BULK ACTIONS ================= --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 p-4 md:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <nav class="flex p-1.5 bg-gray-100 rounded-[2rem] shadow-inner overflow-x-auto no-scrollbar">
                @foreach ($tabItems as $key => $meta)
                    @php $active = ($status === $key); @endphp
                    <a href="{{ route('admin.published.index', ['active_tab' => 'gizi', 'period' => $periodYm, 'status' => $key]) }}"
                       class="flex items-center gap-3 px-6 py-2.5 rounded-[1.8rem] text-[10px] font-black uppercase tracking-widest transition-all duration-300 whitespace-nowrap
                       {{ $active ? 'bg-white ' . $meta['text'] . ' shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                        <span class="relative flex h-2 w-2">
                            @if ($active && ($statusCounts[$key] ?? 0) > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $meta['dot'] }} opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $meta['dot'] }}"></span>
                        </span>
                        {{ $meta['label'] }}
                        <span class="ml-1 px-2 py-0.5 rounded-lg {{ $active ? $meta['badge_bg'] : 'bg-gray-200' }}">
                            {{ $statusCounts[$key] ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                @if ($hasApproved)
                    <form method="POST" action="{{ route('admin.bulk_publish') }}" x-data>
                        @csrf
                        <input type="hidden" name="model_type" value="gizi"><input type="hidden" name="action" value="publish_all">
                        <input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="period" value="{{ $periodYm }}">
                        <button type="button" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Publish Semua?', message: 'Data berstatus Approved akan dipublish ke publik.' })"
                                class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-100/50 transition-all active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                            Publish Approved
                        </button>
                    </form>
                @endif

                @if ($hasPublished)
                    <form method="POST" action="{{ route('admin.bulk_publish') }}" x-data>
                        @csrf
                        <input type="hidden" name="model_type" value="gizi"><input type="hidden" name="action" value="unpublish_all">
                        <input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="period" value="{{ $periodYm }}">
                        <button type="button" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Tarik Semua?', message: 'Data berstatus Published akan ditarik kembali ke Approved.' })"
                                class="px-6 py-3 bg-white border-2 border-amber-500 text-amber-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-amber-50 transition-all active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                            Tarik Published
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= STICKY TABLE CARD ================= --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-sm border border-white/50 overflow-hidden flex flex-col max-h-[70vh]">
        <div class="overflow-auto scrollbar-hide border-separate border-spacing-0">
            <table class="w-full min-w-[1100px]">
                <thead class="sticky top-0 z-20">
                    <tr class="bg-gray-50 uppercase">
                        <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Desa/Kelurahan</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Total Anak</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Avg ZS TB/U</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Prevalensi Stunting</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Status</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Petugas</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 whitespace-nowrap">Input Terakhir</th>
                        <th class="px-6 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100/80 bg-white/60">
                    @forelse ($dataGizi as $item)
                        @php $avgZsTbu = $item->total_anak > 0 ? $item->sum_zs_tbu / $item->total_anak : null; @endphp
                        <tr class="group hover:bg-primary-50/40 transition-all duration-300 cursor-pointer">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-black text-primary-600">{{ substr($item->desa_kelurahan, 0, 1) }}</span>
                                    </div>
                                    <span class="font-black text-gray-900 uppercase text-sm group-hover:text-primary-600 transition-colors">
                                        {{ $item->desa_kelurahan }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">{{ $item->total_anak }} Anak</span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <x-zscore-badge :value="$avgZsTbu" />
                            </td>
                            <td class="px-4 py-5 text-center">
                                <x-prevalensi-badge :value="$item->prevalensi_stunting" />
                            </td>
                            <td class="px-4 py-5 text-center">
                                <x-status-badge :value="$item->status" />
                            </td>
                            <td class="px-4 py-5 text-center text-[10px] font-black text-gray-500 uppercase leading-tight">
                                {{ $item->user->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($item->tanggal_input_terbaru)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL ICON --}}
                                    <a href="{{ route('admin.gizi.detail', ['desa_kelurahan' => $item->desa_kelurahan, 'period' => $periodYm, 'status' => $status]) }}"
                                       title="Lihat Detail"
                                       class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    
                                    {{-- PUBLISH / TARIK ICON --}}
                                    @if ($item->status === 'approved')
                                        <form method="POST" action="{{ route('admin.publish') }}" x-data>
                                            @csrf
                                            <input type="hidden" name="model" value="DataGizi"><input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}">
                                            <input type="hidden" name="current_status" value="approved"><input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="period" value="{{ $periodYm }}">
                                            <button type="button" 
                                                    title="Publish ke Publik"
                                                    @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Publish Data?', message: 'Data desa {{ $item->desa_kelurahan }} akan tampil di publik.' })"
                                                    class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @elseif ($item->status === 'published')
                                        <form method="POST" action="{{ route('admin.publish') }}" x-data>
                                            @csrf
                                            <input type="hidden" name="model" value="DataGizi"><input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}">
                                            <input type="hidden" name="current_status" value="published"><input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="period" value="{{ $periodYm }}">
                                            <button type="button" 
                                                    title="Tarik dari Publik"
                                                    @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Tarik Data?', message: 'Data desa {{ $item->desa_kelurahan }} akan ditarik dari publik.' })"
                                                    class="p-2 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- HAPUS ICON --}}
                                    <form method="POST" action="{{ route('admin.destroy') }}" x-data>
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="model" value="DataGizi"><input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}"><input type="hidden" name="period" value="{{ $periodYm }}"><input type="hidden" name="active_tab" value="gizi">
                                        <button type="button" 
                                                title="Hapus Data"
                                                @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Hapus Data?', message: 'Seluruh data desa {{ $item->desa_kelurahan }} pada periode ini akan dihapus.' })"
                                                class="p-2 bg-primary-50 text-primary-500 rounded-xl hover:bg-primary-500 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-24 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    </div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Tidak ada data untuk status ini</p>
                                    <p class="text-gray-300 text-xs mt-1">Coba ubah filter atau periode pencarian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 px-2">
        {{ $dataGizi->onEachSide(1)->links('pagination.red') }}
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
</style>