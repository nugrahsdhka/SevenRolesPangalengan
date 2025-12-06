<div class="px-2 py-2 flex flex-col gap-4">
    {{-- HEADER CARD: Judul + Tombol + Tabs + Periode --}}
    <div class="rounded-xl shadow-sm border border-gray-200 bg-white px-4 py-3">
        <div class="space-y-0">
            {{-- Baris 1: Judul + Periode Aktif + Tombol Tambah --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between text-center lg:text-left">
                {{-- Kiri: Judul + Periode Aktif --}}
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-center lg:justify-start gap-4 w-full lg:w-auto">
                    <div class="w-full">
                        <h3 class="text-sm md:text-lg font-extrabold text-gray-800">
                            Data Gizi per Desa/Kelurahan
                        </h3>
                        @php
                            // Tentukan periode aktif untuk ditampilkan (label seperti "November 2025")
                            $activePeriodYm = $periodYm ?? now()->format('Y-m');
                            $activePeriodLabel = null;
                            if (!empty($availablePeriods)) {
                                foreach ($availablePeriods as $p) {
                                    $ym = \Carbon\Carbon::parse($p)->format('Y-m');
                                    if ($ym === $activePeriodYm) {
                                        $activePeriodLabel = \Carbon\Carbon::parse($p)->isoFormat('MMMM Y');
                                        break;
                                    }
                                }
                            }
                            if (!$activePeriodLabel) {
                                $activePeriodLabel = \Carbon\Carbon::createFromFormat(
                                    'Y-m',
                                    $activePeriodYm,
                                )->isoFormat('MMMM Y');
                            }
                        @endphp
                        <p class="text-xs text-gray-500 mt-1">
                            Periode aktif:
                            <span class="font-semibold text-gray-800">
                                {{ $activePeriodLabel }}
                            </span>
                        </p>
                    </div>
                </div>
                {{-- Kanan: Tombol Tambah / Import Data --}}
                <div class="w-full lg:w-auto flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-3">
                    <a href="{{ route('gizi.index', [
                        'view' => 'tambah',
                        'period' => $periodYm ?? now()->format('Y-m'),
                        'status' => 'pending',
                    ]) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5
                            text-sm font-medium text-white
                            bg-gradient-to-r from-red-600 to-red-700
                            rounded-lg hover:from-red-700 hover:to-red-800
                            active:from-red-800 active:to-red-900
                            transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="whitespace-nowrap">Tambah / Import Data</span>
                    </a>
                </div>
            </div>
            {{-- Baris 2: Status Tabs + Periode Selector --}}
            <div
                class="flex flex-col lg:flex-row lg:justify-between items-center gap-3 text-center lg:text-left w-full">
                {{-- Status Tabs --}}
                <div class="border-t border-gray-100 pt-3 w-full lg:w-auto lg:flex lg:items-center">
                    <nav class="flex flex-row flex-wrap gap-2 justify-center lg:justify-start" role="tablist"
                        aria-label="Status data">
                        @foreach ($tabItems as $key => $meta)
                            @php $active = ($status === $key); @endphp
                            <a href="{{ route('gizi.index', [
                                'view' => 'data',
                                'period' => $periodYm ?? now()->format('Y-m'),
                                'status' => $key,
                            ]) }}"
                                role="tab" aria-selected="{{ $active ? 'true' : 'false' }}"
                                class="
                                    group relative
                                    flex items-center justify-between sm:justify-center
                                    gap-3
                                    px-4 py-3 sm:py-4
                                    text-xs sm:text-sm font-medium border-b-2 transition-all duration-200
                                    {{ $active
                                        ? $meta['text'] . ' ' . $meta['border'] . ' ' . $meta['bg']
                                        : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}
                            ">
                                {{-- Label + Dot + Badge (mobile = flex-col, desktop = flex-row) --}}
                                <span class="flex flex-col sm:flex-row items-center sm:items-center gap-1 sm:gap-3">
                                    {{-- Label + Dot --}}
                                    <span class="flex items-center gap-2">
                                        {{-- Animated Dot Indicator --}}
                                        <span
                                            class="relative flex h-2 w-2 {{ $active ? '' : 'opacity-0 group-hover:opacity-50' }}">
                                            @if ($active && ($counts[$key] ?? 0) > 0)
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
                                            rounded-full text-[11px] sm:text-xs font-semibold transition-all duration-200
                                            {{ $active
                                                ? $meta['badge_bg'] . ' ' . $meta['badge_text'] . ' shadow-sm'
                                                : 'bg-gray-100 text-gray-600 group-hover:bg-gray-200' }}">
                                        {{ $counts[$key] ?? 0 }}
                                    </span>
                                </span>
                                {{-- Active Indicator Line (desktop) --}}
                                @if ($active)
                                    <span
                                        class="absolute bottom-0 left-0 right-0 h-0.5 {{ $meta['bg'] }} hidden sm:block"></span>
                                @endif
                            </a>
                        @endforeach
                    </nav>
                </div>
                {{-- Periode Selector --}}
                <div class="w-full lg:w-auto">
                    <form method="GET" action="{{ route('gizi.index') }}"
                        class="w-full lg:w-auto flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-2 sm:gap-3">
                        <input type="hidden" name="view" value="data" />
                        <input type="hidden" name="status" value="{{ $status ?? 'pending' }}" />
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
                                        {{ ($periodYm ?? $activePeriodYm) === $ym ? 'selected' : '' }}>
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
            </div>
        </div>
    </div>
    
    {{-- TABLE CARD --}}
    <div class="bg-white/30 backdrop-blur-md border border-gray-200/30 rounded-xl px-2 py-3">
        <div class="relative">
            <div class="max-h-[64vh] md:max-h-[53vh] overflow-y-auto overflow-x-auto bg-white">
                <table class="w-full min-w-[1200px]">
                    <thead class="sticky top-0 z-20 bg-gray-50 shadow-sm uppercase">
                        <tr class="border-b border-gray-200">
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold tracking-wider text-gray-700">
                                Periode
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold tracking-wider text-gray-700">
                                Desa/Kelurahan
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold tracking-wider text-gray-700">
                                Total Anak
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                <p>
                                    rata-rata
                                </p>
                                <p>berat (kg)</p>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                <p>
                                    rata-rata
                                </p>
                                <p>tinggi (cm)</p>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                <p>
                                    rata-rata
                                </p>
                                <p>zs bb/u</p>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                <p>
                                    rata-rata
                                </p>
                                <p>tb/u</p>
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                <p>
                                    rata-rata
                                </p>
                                <p>bb/tb</p>
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold tracking-wider text-gray-700">
                                Status Data
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold tracking-wider text-gray-700">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($aggregatedData as $item)
                            @php
                                $avgBerat = $item->total_anak > 0 ? $item->sum_berat / $item->total_anak : null;
                                $avgTinggi = $item->total_anak > 0 ? $item->sum_tinggi / $item->total_anak : null;
                                $avgZsBBU = $item->total_anak > 0 ? $item->sum_zs_bbu / $item->total_anak : null;
                                $avgZsTBU = $item->total_anak > 0 ? $item->sum_zs_tbu / $item->total_anak : null;
                                $avgZsBBTB = $item->total_anak > 0 ? $item->sum_zs_bbtb / $item->total_anak : null;
                            @endphp
                            <tr class="transition hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y') }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $item->desa_kelurahan }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                    {{ $item->total_anak }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                    {{ !is_null($avgBerat) ? number_format($avgBerat, 2) : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                    {{ !is_null($avgTinggi) ? number_format($avgTinggi, 2) : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                    <x-zscore-badge :value="$avgZsBBU" />
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                    <x-zscore-badge :value="$avgZsTBU" />
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                    <x-zscore-badge :value="$avgZsBBTB" />
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                    @if ($item->status === 'published')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                            Published
                                        </span>
                                    @elseif($item->status === 'approved')
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
                                <td class="whitespace-nowrap px-4 py-3">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('gizi.index', [
                                            'view' => 'detail',
                                            'desa' => $item->desa_kelurahan,
                                            'period' => $periodYm ?? now()->format('Y-m'),
                                        ]) }}"
                                            class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800 hover:underline">
                                            Detail
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        {{-- <form action="{{ route('gizi.destroyAggregated') }}" method="POST" x-data>
                                            @csrf
                                            <input type="hidden" name="desa_kelurahan"
                                                value="{{ $item->desa_kelurahan }}">
                                            <input type="hidden" name="status" value="{{ $item->status }}">
                                            <input type="hidden" name="period"
                                                value="{{ $periodYm ?? now()->format('Y-m') }}">
                                            <button type="button"
                                                @click.prevent="$dispatch('open-confirm', {
                                                    form: $el.closest('form'),
                                                    title: 'Hapus Data Desa {{ $item->desa_kelurahan }}',
                                                    message: 'Yakin ingin menghapus seluruh data gizi desa {{ $item->desa_kelurahan }} pada periode ini? Tindakan ini tidak bisa dibatalkan.',
                                                    okText: 'Ya, Hapus',
                                                    cancelText: 'Batal'
                                                })"
                                                class="text-sm font-medium text-red-600 transition hover:text-red-800 hover:underline">
                                                Hapus
                                            </button>
                                        </form> --}}
<form method="POST" action="{{ route('gizi.destroyAggregat') }}" onsubmit="return confirm('Hapus semua data desa ini untuk periode ini?')">
    @csrf
    @method('DELETE')
    <input type="hidden" name="period" value="{{ $periodYm }}">
    <input type="hidden" name="desa" value="{{ $row->desa_kelurahan }}">
    <button type="submit" class="text-red-600 hover:underline text-sm">
        Hapus
    </button>
</form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    @php
                                        $totalDataInPeriod =
                                            ($statusCounts['pending'] ?? 0) +
                                            ($statusCounts['approved'] ?? 0) +
                                            ($statusCounts['published'] ?? 0);
                                    @endphp
                                    @if ($totalDataInPeriod === 0)
                                        <p class="mt-4 text-sm text-gray-500">
                                            Data tidak ditemukan untuk periode ini
                                        </p>
                                    @else
                                        @if ($status === 'pending')
                                            <p class="mt-4 text-sm text-gray-500">
                                                Semua data untuk periode ini sudah diproses
                                                (di-approve/publish).
                                            </p>
                                        @elseif ($status === 'approved')
                                            <p class="mt-4 text-sm text-gray-500">
                                                Belum ada data gizi pada periode ini yang
                                                di-approve.
                                            </p>
                                        @elseif ($status === 'published')
                                            <p class="mt-4 text-sm text-gray-500">
                                                Belum ada data gizi pada periode ini yang
                                                di-publish.
                                            </p>
                                        @endif
                                    @endif
                                    @if ($status === 'pending')
                                        <a href="{{ route('gizi.index', [
                                            'view' => 'tambah',
                                            'period' => $periodYm ?? now()->format('Y-m'),
                                        ]) }}"
                                            class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            {{ $totalDataInPeriod === 0 ? 'Tambah Data Pertama' : 'Tambah Data Lagi' }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
