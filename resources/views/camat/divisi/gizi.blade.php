{{-- resources/views/camat/gizi.blade.php --}}

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
            'text' => 'text-amber-700',
            'border' => 'border-amber-500',
            'bg' => 'bg-amber-50',
            'dot' => 'bg-amber-500',
            'badge_bg' => 'bg-amber-100',
            'badge_text' => 'text-amber-800',
        ],
        'approved' => [
            'label' => 'Approved',
            'text' => 'text-blue-700',
            'border' => 'border-blue-500',
            'bg' => 'bg-blue-50',
            'dot' => 'bg-blue-500',
            'badge_bg' => 'bg-blue-100',
            'badge_text' => 'text-blue-800',
        ],
        'published' => [
            'label' => 'Published',
            'text' => 'text-emerald-700',
            'border' => 'border-emerald-500',
            'bg' => 'bg-emerald-50',
            'dot' => 'bg-emerald-500',
            'badge_bg' => 'bg-emerald-100',
            'badge_text' => 'text-emerald-800',
        ],
    ];

    if (!isset($statusCounts)) {
        $statusCounts = [
            'pending' => 0,
            'approved' => 0,
            'published' => 0,
        ];
        foreach ($dataGizi as $row) {
            if (isset($statusCounts[$row->status])) {
                $statusCounts[$row->status]++;
            }
        }
    }

    $hasPending = $bulkActions['has_pending'] ?? false;
    $hasApproved = $bulkActions['has_approved'] ?? false;

    // true kalau dua-duanya aktif
    $soloMode = ($hasPending xor $hasApproved);
@endphp


<div class="px-0 py-0 flex flex-col gap-4">

    {{-- ================= HEADER CARD: JUDUL + PERIODE + STATUS TABS + BULK ACTION ================= --}}
    <div class="rounded-xl shadow-sm border border-gray-200 bg-white px-4 py-4">
        <div class="space-y-2">

            {{-- Baris 1: Judul + Info Periode + Filter Periode --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between text-center lg:text-left">

                {{-- Judul + Periode Aktif --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-center lg:justify-start gap-4 w-full lg:w-auto">
                    <div class="w-full">
                        <h3 class="text-sm md:text-lg font-extrabold text-gray-800">
                            Review Data Gizi per Desa/Kelurahan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Periode aktif:
                            <span class="font-semibold text-gray-800">
                                {{ $currentPeriodLabel }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Filter Periode (khusus tab Gizi Camat) --}}
                <form method="GET" action="{{ route('camat.review') }}"
                    class="w-full lg:w-auto flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-2 sm:gap-3">
                    <input type="hidden" name="active_tab" value="gizi">
                    <input type="hidden" name="status" value="{{ $status }}">

                    <div class="w-full sm:w-auto flex flex-col sm:flex-row sm:items-center gap-2">
                        <label class="text-xs sm:text-sm text-gray-600 sm:whitespace-nowrap">
                            Periode:
                        </label>

                        <select name="period" required
                            class="text-xs sm:text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 w-full sm:w-52">
                            @forelse($availablePeriods ?? [] as $p)
                                @php
                                    $ym = \Carbon\Carbon::parse($p)->format('Y-m');
                                @endphp
                                <option value="{{ $ym }}"
                                    {{ ($periodYm ?? now()->format('Y-m')) === $ym ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                </option>
                            @empty
                                @php $ymNow = now()->format('Y-m'); @endphp
                                <option value="{{ $ymNow }}" selected>
                                    {{ now()->isoFormat('MMMM Y') }}
                                </option>
                            @endforelse
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full sm:w-auto px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition-all duration-200 shadow-sm hover:shadow-md">
                        Tampilkan
                    </button>
                </form>
            </div>

            {{-- Baris 3: Ringkasan + Bulk Actions --}}
            <div
                class="flex flex-col lg:flex-row lg:justify-between items-center gap-3 text-center lg:text-left w-full">

                {{-- Baris 2: Status Tabs --}}
                <div class="border-t border-gray-100 pt-3 w-full lg:w-auto lg:flex lg:items-center">
                    <nav class="flex flex-row flex-wrap gap-2 justify-center lg:justify-start" role="tablist"
                        aria-label="Status data">

                        @foreach ($tabItems as $key => $meta)
                            @php $active = ($status === $key); @endphp

                            <a href="{{ route('camat.review', [
                                'active_tab' => 'gizi',
                                'period' => $periodYm ?? now()->format('Y-m'),
                                'status' => $key,
                            ]) }}"
                                role="tab" aria-selected="{{ $active ? 'true' : 'false' }}"
                                class="
                                    group relative
                                    flex flex-col sm:flex-row
                                    items-center sm:items-center
                                    justify-center sm:justify-between
                                    gap-1 sm:gap-3
                                    px-4 py-3 sm:py-4
                                    text-xs sm:text-sm font-medium border-b-2 transition-all duration-200
                                    {{ $active
                                        ? $meta['text'] . ' ' . $meta['border'] . ' ' . $meta['bg']
                                        : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                                ">

                                {{-- Label + Dot (Hanya Desktop) --}}
                                <span class="flex items-center gap-2">

                                    {{-- DOT desktop only --}}
                                    <span class="hidden sm:flex relative h-2 w-2">
                                        @if ($active && ($statusCounts[$key] ?? 0) > 0)
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $meta['dot'] }} opacity-75"></span>
                                        @endif
                                        <span
                                            class="relative inline-flex rounded-full h-2 w-2 {{ $meta['dot'] }}"></span>
                                    </span>

                                    <span>{{ $meta['label'] }}</span>
                                </span>

                                {{-- Badge jumlah data --}}
                                <span
                                    class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-1
                                        rounded-full text-[11px] sm:text-xs font-semibold
                                        {{ $active
                                            ? $meta['badge_bg'] . ' ' . $meta['badge_text'] . ' shadow-sm'
                                            : 'bg-gray-100 text-gray-600 group-hover:bg-gray-200' }}">
                                    {{ $statusCounts[$key] ?? 0 }}
                                </span>

                                {{-- Bottom border hanya desktop --}}
                                @if ($active)
                                    <span
                                        class="absolute bottom-0 left-0 right-0 h-0.5 {{ $meta['bg'] }} hidden sm:block"></span>
                                @endif

                            </a>
                        @endforeach

                    </nav>
                </div>

                {{-- Bulk Approve / Unapprove --}}
                <div class="flex flex-row items-center justify-between gap-3 w-full lg:w-auto">
                    @if ($hasPending)
                        <form method="POST" action="{{ route('camat.bulk_status') }}"
                            class="{{ $soloMode ? 'w-full' : 'w-1/2' }} sm:w-auto" x-data>
                            @csrf

                            {{-- FIELD WAJIB UNTUK bulkUpdateStatus --}}
                            <input type="hidden" name="model_type" value="gizi">
                            <input type="hidden" name="action" value="approve_all">
                            <input type="hidden" name="active_tab" value="gizi">
                            <input type="hidden" name="period" value="{{ $periodYm }}">

                            <button type="button"
                                @click.prevent="$dispatch('open-confirm', {
                                    form: $el.closest('form'),
                                    title: 'Approve semua data Pending',
                                    message: 'Yakin ingin meng-approve semua data gizi berstatus Pending untuk periode ini? Data akan siap dipublish oleh Admin.',
                                    okText: 'Ya, Approve semua',
                                    cancelText: 'Batal'
                                })"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5
                                    text-xs sm:text-sm font-medium text-white
                                    bg-gradient-to-r from-emerald-600 to-emerald-700
                                    rounded-lg hover:from-emerald-700 hover:to-emerald-800
                                    active:from-emerald-800 active:to-emerald-900
                                    transition-all duration-200 shadow-md hover:shadow-lg">

                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                                {{-- Teks tombol --}}
                                @if ($soloMode)
                                    {{-- SOLO MODE → tampilkan versi lengkap di mobile OR desktop --}}
                                    <span class="whitespace-nowrap">Approve semua Pending</span>
                                @else
                                    {{-- NORMAL MODE → versi mobile & desktop beda --}}
                                    <span class="sm:hidden whitespace-nowrap">Approve semua</span>
                                    <span class="hidden sm:inline whitespace-nowrap">Approve semua Pending</span>
                                @endif
                            </button>
                        </form>
                    @endif

                    @if ($hasApproved)
                        <form method="POST" action="{{ route('camat.bulk_status') }}"
                            class="{{ $soloMode ? 'w-full' : 'w-1/2' }} sm:w-auto" x-data>
                            @csrf

                            {{-- FIELD WAJIB UNTUK bulkUpdateStatus --}}
                            <input type="hidden" name="model_type" value="gizi">
                            <input type="hidden" name="action" value="unapprove_all">
                            <input type="hidden" name="active_tab" value="gizi">
                            <input type="hidden" name="period" value="{{ $periodYm }}">

                            <button type="button"
                                @click.prevent="$dispatch('open-confirm', {
                                    form: $el.closest('form'),
                                    title: 'Tarik semua data Approved',
                                    message: 'Yakin ingin menarik semua data gizi berstatus Approved untuk periode ini kembali ke status Pending? Data tidak akan bisa dipublish oleh Admin sebelum di-approve lagi.',
                                    okText: 'Ya, Tarik semua',
                                    cancelText: 'Batal'
                                })"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5
                                    text-xs sm:text-sm font-medium text-amber-800
                                    bg-amber-50 rounded-lg border border-amber-300
                                    hover:bg-amber-100 active:bg-amber-200
                                    transition-all duration-200">

                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>

                                {{-- Teks tombol --}}
                                @if ($soloMode)
                                    <span class="whitespace-nowrap">Tarik semua Approved</span>
                                @else
                                    <span class="sm:hidden whitespace-nowrap">Tarik semua</span>
                                    <span class="hidden sm:inline whitespace-nowrap">Tarik semua Approved</span>
                                @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================= TABLE CARD: AGREGASI PER DESA ================= --}}
    <div class="bg-white/30 backdrop-blur-md border border-gray-200/30 rounded-xl shadow-lg px-2 py-1">
        <div class="relative">
            <div class="max-h-[64vh] md:max-h-[52vh] overflow-y-auto overflow-x-auto bg-white">
                <table class="w-full min-w-[1000px]">
                    <thead class="sticky top-0 z-20 bg-gray-50 shadow-sm">
                        <tr class="border-b border-gray-200">
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Desa/Kelurahan
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Total Anak
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Total Kasus Stunting
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Rata-rata ZS TB/U
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Status Data
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Petugas Input
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Terakhir Diinput
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($dataGizi as $item)
                            @php
                                $avgZsTbu = $item->total_anak > 0 ? $item->sum_zs_tbu / $item->total_anak : null;
                            @endphp

                            <tr class="transition hover:bg-gray-50">
                                {{-- Desa --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900 uppercase">
                                        {{ $item->desa_kelurahan }}
                                    </div>
                                </td>

                                {{-- Total anak --}}
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                    {{ $item->total_anak }}
                                </td>

                                {{-- Total kasus stunting --}}
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                    {{ $item->total_kasus_stunting }}
                                </td>

                                {{-- Rata-rata ZS TB/U --}}
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                    <x-zscore-badge :value="$avgZsTbu" />
                                </td>

                                {{-- Status Data --}}
                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                    @if ($item->status === 'published')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                            Published
                                        </span>
                                    @elseif ($item->status === 'approved')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                            Approved
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                {{-- Petugas Input --}}
                                <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-600 text-center">
                                    @if ($item->user)
                                        <div class="font-medium text-gray-800">
                                            {!! nl2br(e(str_replace(' ', "\n", $item->user->name))) !!}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak diketahui</span>
                                    @endif
                                </td>

                                {{-- Terakhir Diinput --}}
                                <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-500 text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_input_terbaru)->diffForHumans() }}
                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="flex items-center justify-center gap-3">
                                            {{-- Detail data per anak --}}
                                            <a href="{{ route('camat.gizi.detail', [
                                                'desa_kelurahan' => $item->desa_kelurahan,
                                                'period' => $periodYm, // periode aktif
                                                'status' => $status, // tab aktif: pending/approved/published
                                                'from' => 'camat', // info asal halaman
                                            ]) }}"
                                                class="text-xs font-medium text-indigo-600 transition hover:text-indigo-800 hover:underline">
                                                Detail
                                            </a>

                                            {{-- Approve / Tarik per DESA + PERIODE --}}
                                            @if ($item->status === 'pending')
                                                {{-- PENDING -> APPROVED (Approve) --}}
                                                <form method="POST" action="{{ route('camat.status') }}" x-data>
                                                    @csrf
                                                    <input type="hidden" name="model" value="DataGizi">
                                                    <input type="hidden" name="new_status" value="approved">
                                                    <input type="hidden" name="desa_kelurahan"
                                                        value="{{ $item->desa_kelurahan }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $periodYm }}">
                                                    <input type="hidden" name="active_tab" value="gizi">

                                                    <button type="button"
                                                        @click.prevent="$dispatch('open-confirm', {
                                                            form: $el.closest('form'),
                                                            title: 'Approve Data Desa {{ $item->desa_kelurahan }}',
                                                            message: 'Yakin ingin meng-approve semua data gizi desa {{ $item->desa_kelurahan }} pada periode ini? Data akan dapat dipublish oleh Admin.',
                                                            okText: 'Ya, Approve',
                                                            cancelText: 'Batal'
                                                        })"
                                                        class="text-xs font-medium text-emerald-600 hover:text-emerald-800 hover:underline">
                                                        Approve
                                                    </button>
                                                </form>
                                            @elseif ($item->status === 'approved' || $item->status === 'published')
                                                {{-- APPROVED -> PENDING (Tarik) --}}
                                                <form method="POST" action="{{ route('camat.status') }}" x-data>
                                                    @csrf
                                                    <input type="hidden" name="model" value="DataGizi">
                                                    <input type="hidden" name="new_status" value="pending">
                                                    <input type="hidden" name="desa_kelurahan"
                                                        value="{{ $item->desa_kelurahan }}">
                                                    <input type="hidden" name="period"
                                                        value="{{ $periodYm }}">
                                                    <input type="hidden" name="active_tab" value="gizi">

                                                    <button type="button"
                                                        @click.prevent="$dispatch('open-confirm', {
                                                            form: $el.closest('form'),
                                                            title: 'Tarik Data Desa {{ $item->desa_kelurahan }}',
                                                            message: 'Yakin ingin menarik semua data gizi desa {{ $item->desa_kelurahan }} di periode ini kembali ke status Pending? Data tidak akan bisa dipublish oleh Admin sebelum di-approve lagi.',
                                                            okText: 'Ya, Tarik',
                                                            cancelText: 'Batal'
                                                        })"
                                                        class="text-xs font-medium text-red-600 hover:text-red-800 hover:underline">
                                                        Tarik
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <form method="POST" action="{{ route('camat.destroy') }}" x-data>
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="model" value="DataGizi">
                                            <input type="hidden" name="active_tab" value="gizi">
                                            <input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}">
                                            <input type="hidden" name="period" value="{{ $periodYm }}">
                                            <input type="hidden" name="status" value="{{ $item->status }}">

                                            <button type="button"
                                                @click.prevent="$dispatch('open-confirm', {
                                                    form: $el.closest('form'),
                                                    title: 'Hapus Data Desa {{ $item->desa_kelurahan }}',
                                                    message: 'Yakin ingin menghapus seluruh data gizi desa {{ $item->desa_kelurahan }} dengan status {{ strtoupper($item->status) }} pada periode ini? Tindakan ini tidak bisa dibatalkan.',
                                                    okText: 'Ya, Hapus',
                                                    cancelText: 'Batal'
                                                })"
                                                class="text-xs font-medium text-red-600 hover:text-red-800 hover:underline">
                                                Hapus Semua
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>

                                    @if ($status === 'pending')
                                        <p class="mt-4 text-sm text-gray-500">
                                            Tidak ada data gizi dengan status <span
                                                class="font-semibold">Pending</span>
                                            untuk periode ini.
                                        </p>
                                    @elseif ($status === 'approved')
                                        <p class="mt-4 text-sm text-gray-500">
                                            Tidak ada data gizi dengan status <span
                                                class="font-semibold">Approved</span>
                                            untuk periode ini.
                                        </p>
                                    @elseif ($status === 'published')
                                        <p class="mt-4 text-sm text-gray-500">
                                            Tidak ada data gizi dengan status <span
                                                class="font-semibold">Published</span>
                                            untuk periode ini.
                                        </p>
                                    @else
                                        <p class="mt-4 text-sm text-gray-500">
                                            Tidak ada data gizi untuk periode ini.
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination mirip di divisi gizi --}}
        <div class="mt-3">
            {{ $dataGizi->onEachSide(1)->links('pagination.red') }}
        </div>
    </div>
</div>
