@php
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

    $status = $status ?? request('status', 'pending');

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

    $hasPending = $bulkActions['has_pending'] ?? false;
    $hasApproved = $bulkActions['has_approved'] ?? false;
    $soloMode = ($hasPending xor $hasApproved);
@endphp

<div class="space-y-2">

    {{-- ================= HEADER CARD: JUDUL & FILTER PERIODE ================= --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300">
        <div class="p-6 md:p-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                {{-- Kiri: Judul --}}
                <div class="space-y-1 text-center lg:text-left">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase leading-none">
                        Review <span class="text-red-600">Data Gizi</span>
                    </h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Periode Aktif: <span class="text-gray-700">{{ $currentPeriodLabel }}</span>
                    </p>
                </div>

                {{-- Kanan: Filter Periode --}}
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">               
                    <div class="bg-red-50 border border-red-100 px-6 py-3 rounded-2xl flex items-center gap-3 w-full lg:w-auto justify-center lg:justify-start">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-red-400 uppercase tracking-widest">Prevalensi Kec.</span>
                            <span class="text-sm font-black text-red-700 tracking-tight leading-none">
                                {{ number_format($totalPrevalensiKecamatan ?? 0, 2) }}%
                            </span>
                        </div>
                    </div>
                    
                    <form method="GET" action="{{ route('camat.review') }}" class="flex items-center gap-3 w-full lg:w-auto">
                        <input type="hidden" name="active_tab" value="gizi">
                        <input type="hidden" name="status" value="{{ $status }}">
                        
                        <div class="relative flex-1 lg:flex-none">
                            <select name="period" required
                                    class="w-full lg:w-56 pl-5 pr-10 py-3 bg-gray-50 border-none rounded-2xl text-[11px] font-black text-gray-700 uppercase tracking-widest focus:ring-2 focus:ring-red-500 transition-all appearance-none cursor-pointer">
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
                        <button type="submit" class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-red-600 transition-all shadow-lg active:scale-95">
                            Tampilkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= TAB STATUS & BULK ACTIONS ================= --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            {{-- Modern Segmented Control --}}
            <nav class="flex p-1.5 bg-gray-100 rounded-[2rem] shadow-inner overflow-x-auto no-scrollbar">
                @foreach ($tabItems as $key => $meta)
                    @php $active = ($status === $key); @endphp
                    <a href="{{ route('camat.review', ['active_tab' => 'gizi', 'period' => $periodYm, 'status' => $key]) }}"
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

            {{-- Bulk Action Buttons --}}
            <div class="flex items-center gap-3 w-full lg:w-auto">
                @if ($hasPending)
                    <form method="POST" action="{{ route('camat.bulk_status') }}" class="flex-1 lg:flex-none" x-data>
                        @csrf
                        <input type="hidden" name="model_type" value="gizi"><input type="hidden" name="action" value="approve_all">
                        <input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="period" value="{{ $periodYm }}">
                        <button type="button" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Approve Semua?', message: 'Seluruh data pending akan di-approve untuk diverifikasi Admin.' })"
                                class="w-full px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                            Approve Semua
                        </button>
                    </form>
                @endif

                @if ($hasApproved)
                    <form method="POST" action="{{ route('camat.bulk_status') }}" class="flex-1 lg:flex-none" x-data>
                        @csrf
                        <input type="hidden" name="model_type" value="gizi"><input type="hidden" name="action" value="unapprove_all">
                        <input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="period" value="{{ $periodYm }}">
                        <button type="button" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Tarik Semua?', message: 'Data Approved akan dikembalikan ke status Pending.' })"
                                class="w-full px-6 py-3 bg-white border-2 border-amber-500 text-amber-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-amber-50 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                            Tarik Semua
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= STICKY TABLE CARD ================= --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col max-h-[70vh]">
        <div class="overflow-auto scrollbar-hide border-separate border-spacing-0">
            <table class="w-full min-w-[1100px]">
                <thead class="sticky top-0 z-20">
                    <tr class="bg-gray-50 uppercase">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Desa/Kelurahan</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Total Anak</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Avg ZS TB/U</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Prevalensi Stunting</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Status</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Petugas</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Terakhir Input</th>
                        <th class="px-8 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em] border-b border-gray-100 bg-gray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse ($dataGizi as $item)
                        @php $avgZsTbu = $item->total_anak > 0 ? $item->sum_zs_tbu / $item->total_anak : null; @endphp
                        <tr class="group hover:bg-red-50/30 transition-all duration-300">
                            <td class="px-8 py-6 font-black text-gray-900 uppercase text-sm group-hover:text-red-600 transition-colors">
                                {{ $item->desa_kelurahan }}
                            </td>
                            <td class="px-4 py-6 text-center">
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-lg uppercase tracking-tighter">{{ $item->total_anak }} Anak</span>
                            </td>
                            <td class="px-4 py-6 text-center">
                                <x-zscore-badge :value="$avgZsTbu" />
                            </td>
                            <td class="px-4 py-6 text-center">
                                @php
                                    $p = $item->prevalensi_stunting ?? 0;
                                    // Warna dinamis: Merah (>20%), Amber (>10%), Emerald (<=10%)
                                    $style = $p > 20 ? 'bg-red-100 text-red-700' : ($p > 10 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700');
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $style }}">
                                    {{ number_format($p, 2) }}%
                                </span>
                            </td>
                            <td class="px-4 py-6 text-center">
                                @php
                                    $style = match($item->status) {
                                        'published' => 'bg-emerald-100 text-emerald-700',
                                        'approved' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $style }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-4 py-6 text-center text-[10px] font-black text-gray-500 uppercase leading-tight">
                                {{ $item->user->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-6 text-center text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($item->tanggal_input_terbaru)->diffForHumans() }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL ICON --}}
                                    <a href="{{ route('camat.gizi.detail', ['desa_kelurahan' => $item->desa_kelurahan, 'period' => $periodYm, 'status' => $status, 'from' => 'camat']) }}"
                                       title="Lihat Detail"
                                       class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    
                                    {{-- APPROVE / TARIK ICON --}}
                                    @if ($item->status === 'pending')
                                        <form method="POST" action="{{ route('camat.status') }}" x-data>
                                            @csrf
                                            <input type="hidden" name="model" value="DataGizi"><input type="hidden" name="new_status" value="approved">
                                            <input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}"><input type="hidden" name="period" value="{{ $periodYm }}"><input type="hidden" name="active_tab" value="gizi">
                                            <button type="button" title="Approve Data" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Approve Data?', message: 'Data desa {{ $item->desa_kelurahan }} akan diteruskan ke Admin.' })"
                                                    class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                    @elseif ($item->status === 'approved' || $item->status === 'published')
                                        <form method="POST" action="{{ route('camat.status') }}" x-data>
                                            @csrf
                                            <input type="hidden" name="model" value="DataGizi"><input type="hidden" name="new_status" value="pending">
                                            <input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}"><input type="hidden" name="period" value="{{ $periodYm }}"><input type="hidden" name="active_tab" value="gizi">
                                            <button type="button" title="Tarik Kembali" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Tarik Data?', message: 'Kembalikan data desa {{ $item->desa_kelurahan }} ke status Pending?' })"
                                                    class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- HAPUS ICON --}}
                                    <form method="POST" action="{{ route('camat.destroy') }}" x-data>
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="model" value="DataGizi"><input type="hidden" name="active_tab" value="gizi"><input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}"><input type="hidden" name="period" value="{{ $periodYm }}"><input type="hidden" name="status" value="{{ $item->status }}">
                                        <button type="button" title="Hapus Permanen" @click.prevent="$dispatch('open-confirm', { form: $el.closest('form'), title: 'Hapus Data?', message: 'Data desa {{ $item->desa_kelurahan }} pada periode ini akan dihapus permanen.' })"
                                                class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-32 text-center">
                                <div class="flex flex-col items-center opacity-40">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500 italic">Data belum tersedia untuk periode ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $dataGizi->onEachSide(1)->links('pagination.red') }}
    </div>
</div>

<style>
    /* Styling khusus scrollbar & table sticky */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .border-separate { border-collapse: separate; }
</style>