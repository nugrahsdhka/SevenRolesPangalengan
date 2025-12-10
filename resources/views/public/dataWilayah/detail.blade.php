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

    <div class="pb-2">
        <section id="detail-wilayah">
            <div class="px-2 space-y-3">

                {{-- ========== HEADER + FILTER (FILTER DI KANAN) ========== --}}
                <div class="pt-3">
                    <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200
                                overflow-hidden max-h-[20vh]">

                        {{-- Scroll internal jika konten melebihi 20vh --}}
                        <div class="h-full overflow-y-auto">
                            <div class="px-4 sm:px-6 py-3 sm:py-4">

                                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                                    {{-- ========== KIRI: TITLE + INFO DESA ========== --}}
                                    <div class="space-y-1.5">
                                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 leading-tight">
                                            Detail Data Anak per Desa
                                        </h1>

                                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs sm:text-sm text-gray-600">
                                            <p class="flex items-center gap-1">
                                                <span class="font-semibold text-gray-800">Desa/Kelurahan:</span>
                                                <span class="truncate max-w-[180px] sm:max-w-[220px] lg:max-w-xs">
                                                    {{ $desa ?? '-' }}
                                                </span>
                                            </p>
                                            <p class="flex items-center gap-1">
                                                <span class="font-semibold text-gray-800">Periode:</span>
                                                <span>{{ $periodLabel }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- ========== KANAN: BUTTON + FILTER TOOLBAR ========== --}}
                                    <div class="w-full lg:w-auto space-y-2">

                                        {{-- Tombol kembali --}}
                                        <div class="flex justify-start lg:justify-end">
                                            <a href="{{ route('data-wilayah', ['period' => $periodYm]) }}"
                                            class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2
                                                    text-xs sm:text-sm font-semibold rounded-xl
                                                    border border-gray-300 text-gray-700 bg-white
                                                    hover:bg-gray-50 hover:border-gray-400
                                                    shadow-sm hover:shadow-md
                                                    transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 19l-7-7 7-7" />
                                                </svg>
                                                <span class="whitespace-nowrap">Kembali ke Data Wilayah</span>
                                            </a>
                                        </div>

                                        {{-- FILTER TOOLBAR --}}
                                        <form action="{{ route('data-wilayah-detail-gizi') }}" method="GET"
                                            class="flex flex-wrap items-end gap-2.5 sm:gap-3 lg:gap-3.5
                                                    justify-start lg:justify-end">

                                            {{-- Hidden --}}
                                            <input type="hidden" name="desa" value="{{ $desa }}">
                                            <input type="hidden" name="period" value="{{ $periodYm }}">

                                            {{-- Posyandu --}}
                                            <div class="flex flex-col">
                                                <label class="text-[11px] sm:text-xs font-semibold text-gray-700 mb-1">
                                                    Posyandu
                                                </label>
                                                <select name="posyandu"
                                                        class="min-w-[130px] sm:min-w-[150px]
                                                            px-3 py-1.5 border border-gray-300 rounded-xl
                                                            text-xs sm:text-sm font-medium text-gray-700
                                                            focus:ring-2 focus:ring-red-500 focus:border-red-500
                                                            hover:border-gray-400 transition-colors">
                                                    <option value="">Semua</option>
                                                    @foreach ($posyanduOptions ?? [] as $pos)
                                                        <option value="{{ $pos }}" @selected($filterPosyandu === $pos)>
                                                            {{ $pos }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- RT --}}
                                            <div class="flex flex-col">
                                                <label class="text-[11px] sm:text-xs font-semibold text-gray-700 mb-1">
                                                    RT
                                                </label>
                                                <select name="rt"
                                                        class="w-20 sm:w-24 px-3 py-1.5 border border-gray-300 rounded-xl
                                                            text-xs sm:text-sm font-medium text-gray-700
                                                            focus:ring-2 focus:ring-red-500 focus:border-red-500
                                                            hover:border-gray-400 transition-colors">
                                                    <option value="">Semua</option>
                                                    @foreach ($rtOptions ?? [] as $rt)
                                                        <option value="{{ $rt }}" @selected($filterRt === $rt)>
                                                            {{ $rt }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- RW --}}
                                            <div class="flex flex-col">
                                                <label class="text-[11px] sm:text-xs font-semibold text-gray-700 mb-1">
                                                    RW
                                                </label>
                                                <select name="rw"
                                                        class="w-20 sm:w-24 px-3 py-1.5 border border-gray-300 rounded-xl
                                                            text-xs sm:text-sm font-medium text-gray-700
                                                            focus:ring-2 focus:ring-red-500 focus:border-red-500
                                                            hover:border-gray-400 transition-colors">
                                                    <option value="">Semua</option>
                                                    @foreach ($rwOptions ?? [] as $rw)
                                                        <option value="{{ $rw }}" @selected($filterRw === $rw)>
                                                            {{ $rw }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Tombol --}}
                                            <div class="flex gap-1.5 sm:gap-2">
                                                <a href="{{ route('data-wilayah-detail-gizi', [
                                                        'desa'   => $desa,
                                                        'period' => $periodYm,
                                                    ]) }}"
                                                class="px-3 py-1.5 text-[11px] sm:text-xs font-semibold
                                                        border border-gray-300 rounded-xl
                                                        text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-400
                                                        transition-all">
                                                    Reset
                                                </a>

                                                <button type="submit"
                                                        class="px-3 sm:px-4 py-1.5 text-[11px] sm:text-xs font-semibold text-white
                                                            rounded-xl bg-gradient-to-r from-red-600 to-pink-600
                                                            shadow-md hover:shadow-lg hover:from-red-700 hover:to-pink-700
                                                            transform hover:scale-[1.02] active:scale-95
                                                            transition-all duration-200">
                                                    Terapkan
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- ========== CARD TABEL DETAIL ANAK ========== --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="relative">
                        <div class="max-h-[85vh] overflow-y-auto overflow-x-auto bg-white">
                            <table class="w-full min-w-[1200px] divide-y divide-gray-200">

                                {{-- THEAD (STICKY) --}}
                                <thead class="sticky top-0 z-20 bg-gradient-to-r from-gray-50 to-gray-100 shadow-sm">
                                    <tr class="uppercase border-b border-gray-200 text-[11px] sm:text-xs">
                                        <th class="px-4 py-3 text-left font-bold text-gray-700 tracking-wider">
                                            No
                                        </th>
                                        <th class="px-4 py-3 text-left font-bold text-gray-700 tracking-wider">
                                            Nama
                                        </th>
                                        <th class="px-4 py-3 text-left font-bold text-gray-700 tracking-wider">
                                            Posyandu
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider">
                                            RT
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider">
                                            RW
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            Tanggal Lahir
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            Berat (kg)
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            Tinggi (cm)
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            ZS BB/U
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            Status BB/U
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            ZS TB/U
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            Status TB/U
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            ZS BB/TB
                                        </th>
                                        <th class="px-4 py-3 text-center font-bold text-gray-700 tracking-wider whitespace-nowrap">
                                            Status BB/TB
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-100 text-xs sm:text-sm">
                                    @forelse ($dataAnak as $index => $row)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">

                                            {{-- No --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                                {{ ($dataAnak->firstItem() ?? 0) + $index }}
                                            </td>

                                            {{-- Nama --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-900 font-semibold">
                                                {{ $row->nama ?? '-' }}
                                            </td>

                                            {{-- Posyandu --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                                {{ $row->posyandu ?? '-' }}
                                            </td>

                                            {{-- RT --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-gray-700">
                                                {{ $row->rt ?? '-' }}
                                            </td>

                                            {{-- RW --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-gray-700">
                                                {{ $row->rw ?? '-' }}
                                            </td>

                                            {{-- Tanggal lahir --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-gray-700">
                                                @if ($row->tgl_lahir)
                                                    {{ $row->tgl_lahir instanceof \Carbon\Carbon
                                                        ? $row->tgl_lahir->format('d-m-Y')
                                                        : \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            {{-- Berat --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-gray-700">
                                                {{ !is_null($row->berat) ? number_format($row->berat, 1) : '-' }}
                                            </td>

                                            {{-- Tinggi --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center text-gray-700">
                                                {{ !is_null($row->tinggi) ? number_format($row->tinggi, 1) : '-' }}
                                            </td>

                                            {{-- ZS BB/U --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $row->zs_bb_u])
                                            </td>

                                            {{-- Status BB/U (PAKAI zscore-badge) --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $row->status_bb_u])
                                            </td>

                                            {{-- ZS TB/U --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $row->zs_tb_u])
                                            </td>

                                            {{-- Status TB/U (PAKAI zscore-badge) --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $row->status_tb_u])
                                            </td>

                                            {{-- ZS BB/TB --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $row->zs_bb_tb])
                                            </td>

                                            {{-- Status BB/TB (PAKAI zscore-badge) --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $row->status_bb_tb])
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0 18 0z" />
                                                    </svg>
                                                    <p class="text-sm font-medium text-gray-500">
                                                        Tidak ada data anak untuk desa ini pada periode yang dipilih.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>


                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION --}}
                    @if (isset($dataAnak) && $dataAnak instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    Halaman
                                    <span class="font-semibold text-gray-700">{{ $dataAnak->currentPage() }}</span>
                                    dari
                                    <span class="font-semibold text-gray-700">{{ $dataAnak->lastPage() }}</span>
                                </div>
                                <div>
                                    {{ $dataAnak->appends([
                                        'desa'     => $desa,
                                        'period'   => $periodYm,
                                        'posyandu' => $filterPosyandu,
                                        'rt'       => $filterRt,
                                        'rw'       => $filterRw,
                                    ])->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>
</x-guest-layout>
