<x-app-layout>
    <x-slot name="title">
        Detail Data Gizi - {{ strtoupper($desaKelurahan) }}
    </x-slot>

    @php
        // Periode aktif dikirim dari controller (format Y-m)
        $periodYm = $periodYm ?? now()->format('Y-m');

        try {
            $currentPeriodLabel = \Carbon\Carbon::createFromFormat('Y-m', $periodYm)->isoFormat('MMMM Y');
        } catch (\Exception $e) {
            $currentPeriodLabel = now()->isoFormat('MMMM Y');
        }
    @endphp

    {{-- HEADER ATAS --}}
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            {{-- Kiri: Judul --}}
            <div class="flex items-center gap-3 min-w-0 flex-1">
                @php
                    // Prioritas: query ?from=..., lalu role user, lalu fallback ke previous()
                    $from   = request('from');
                    $status = request('status', 'pending'); // default pending kalau nggak ada
                    $period = $periodYm ?? request('period'); // dari controller atau query

                    if ($from === 'admin' || auth()->user()->hasRole('IT Admin')) {
                        $backUrl = route('admin.published.index', [
                            'active_tab' => 'gizi',
                            'status'     => $status,
                            'period'     => $period,
                        ]);
                    } elseif ($from === 'camat' || auth()->user()->hasRole('Camat')) {
                        $backUrl = route('camat.review', [
                            'active_tab' => 'gizi',
                            'status'     => $status,
                            'period'     => $period,
                        ]);
                    } else {
                        $backUrl = url()->previous() ?: route('dashboard');
                    }
                @endphp


                <a href="{{ $backUrl }}" class="flex-shrink-0 p-2 rounded-lg hover:bg-gray-100 transition"
                    title="Kembali">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <div class="min-w-0">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                        DETAIL DATA GIZI
                    </h2>
                </div>
            </div>

            {{-- Kanan: Dropdown Menu User --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                    type="button" title="{{ auth()->user()->name }}">
                    @php
                        $words = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        foreach ($words as $word) {
                            if (!empty($word)) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                        }
                        if (strlen($initials) > 2) {
                            $initials = substr($initials, 0, 1) . substr($initials, -1);
                        }
                    @endphp
                    {{ $initials }}
                </button>

                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                    style="display: none;">
                    <div class="py-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0012 11z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">Profil Saya</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="font-medium">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- BODY --}}
    <div class="px-1 py-2 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mb-2 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <svg class="h-5 w-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mb-2 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <svg class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                    <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- HEADER CARD: JUDUL + PERIODE (tanpa select) --}}
            <div class="rounded-xl shadow-sm border border-gray-200 bg-white px-4 py-4 mb-3">
                <div
                    class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between text-center lg:text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-center lg:justify-start gap-4 w-full lg:w-auto">
                        <div class="w-full">
                            <h3 class="text-sm md:text-lg font-extrabold text-gray-800">
                                Detail Data Gizi per Anak – {{ strtoupper($desaKelurahan) }}
                            </h3>

                            <div>
                                <p class="text-sm text-gray-500 mt-1">
                                    Periode
                                    <span class="font-semibold text-gray-800">
                                        {{ $currentPeriodLabel }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD UTAMA: Statistik + Tabel --}}
            <div class="bg-white/30 backdrop-blur-md border border-gray-200/30 rounded-xl px-2 py-2">
                <div class="space-y-4">

                    {{-- STATISTIK RINGKAS --}}
                    <div x-data="{ isOpen: false }">
                        <button type="button" @click="isOpen = !isOpen"
                            class="w-full text-left text-sm font-bold text-gray-900 mb-3 flex items-center gap-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                </svg>
                                Statistik Ringkas
                            </span>
                            <span class="text-gray-500">
                                <svg x-show="isOpen" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                                <svg x-show="!isOpen" style="display: none;" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3" x-show="isOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;">
                            {{-- Total anak --}}
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4 hover:shadow-md transition-shadow">
                                <p class="text-[10px] sm:text-xs font-bold text-blue-700 uppercase tracking-wide mb-2">
                                    Total Anak
                                </p>
                                <p class="text-2xl sm:text-3xl font-bold text-blue-900">
                                    {{ $statistik->total_anak ?? 0 }}
                                </p>
                            </div>

                            {{-- Kasus Stunting --}}
                            <div
                                class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-4 hover:shadow-md transition-shadow">
                                <p class="text-[10px] sm:text-xs font-bold text-red-700 uppercase tracking-wide mb-2">
                                    Kasus Stunting
                                </p>
                                <p class="text-2xl sm:text-3xl font-bold text-red-900">
                                    {{ $statistik->total_stunting ?? 0 }}
                                </p>
                                <p class="text-[10px] text-red-600 mt-1">
                                    {{ ($statistik->total_anak ?? 0) > 0
                                        ? number_format(($statistik->total_stunting / $statistik->total_anak) * 100, 1)
                                        : 0 }}%
                                </p>
                            </div>

                            {{-- Rata-rata tinggi --}}
                            <div
                                class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200 p-4 hover:shadow-md transition-shadow">
                                <p
                                    class="text-[10px] sm:text-xs font-bold text-emerald-700 uppercase tracking-wide mb-2">
                                    Rata-rata Tinggi
                                </p>
                                <p class="text-2xl sm:text-3xl font-bold text-emerald-900">
                                    {{ $statistik && $statistik->avg_tinggi ? number_format($statistik->avg_tinggi, 1) : '-' }}
                                </p>
                                <p class="text-[10px] text-emerald-600 mt-1">cm</p>
                            </div>

                            {{-- Rata-rata ZS TB/U --}}
                            <div
                                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-4 hover:shadow-md transition-shadow">
                                <p
                                    class="text-[10px] sm:text-xs font-bold text-purple-700 uppercase tracking-wide mb-2">
                                    Rata-rata ZS TB/U
                                </p>
                                <div
                                    class="text-2xl sm:text-3xl font-bold text-purple-900 flex items-center h-9 sm:h-10">
                                    @if ($statistik && !is_null($statistik->avg_zs_tbu))
                                        <x-zscore-badge :value="$statistik->avg_zs_tbu" />
                                    @else
                                        <span class="text-gray-400 text-2xl sm:text-3xl">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 mb-1"></div>

                    {{-- TABEL DATA INDIVIDU --}}
                    <div>
                        <div class="flex gap-2 items-center justify-between flex-wrap mb-2">
                            <h5 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Data Individu 
                            </h5>
                            <div class="text-xs sm:text-sm text-gray-500">
                                {{ $currentPeriodLabel }}
                            </div>
                        </div>

                        <div class="relative">
                            <div class="max-h-[64vh] md:max-h-[63vh] overflow-y-auto overflow-x-auto bg-white">
                                <table class="w-full min-w-[900px] text-sm text-left text-gray-700">
                                    <thead class="sticky top-0 z-20 bg-gray-50 shadow-sm border-b border-gray-200">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                No
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                Tanggal Input
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                Berat (kg)
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                Tinggi (cm)
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                BB/U
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                ZS BB/U
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                TB/U
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                ZS TB/U
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                BB/TB
                                            </th>
                                            <th
                                                class="px-4 py-3 text-center text-[10px] md:text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                ZS BB/TB
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse ($dataGizi as $index => $row)
                                            <tr class="transition-colors even:bg-gray-50/60 hover:bg-emerald-50/60">
                                                {{-- No --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-600 font-medium align-top">
                                                    {{ $dataGizi->firstItem() + $index }}
                                                </td>

                                                {{-- Tanggal Input --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-700 align-top">
                                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}
                                                </td>

                                                {{-- Berat --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ is_numeric($row->berat) ? number_format($row->berat, 2) : '-' }}
                                                </td>

                                                {{-- Tinggi --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ is_numeric($row->tinggi) ? number_format($row->tinggi, 2) : '-' }}
                                                </td>

                                                {{-- BB/U --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ $row->status_bb_u ?? '-' }}
                                                </td>

                                                {{-- ZS BB/U --}}
                                                <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                    <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                        <x-zscore-badge :value="$row->zs_bb_u" />
                                                    </div>
                                                </td>

                                                {{-- TB/U --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ $row->status_tb_u ?? '-' }}
                                                </td>

                                                {{-- ZS TB/U --}}
                                                <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                    <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                        <x-zscore-badge :value="$row->zs_tb_u" />
                                                    </div>
                                                </td>

                                                {{-- BB/TB --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ $row->status_bb_tb ?? '-' }}
                                                </td>

                                                {{-- ZS BB/TB --}}
                                                <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                    <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                        <x-zscore-badge :value="$row->zs_bb_tb" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10"
                                                    class="px-6 py-10 text-center text-sm text-gray-500">
                                                    <div class="flex flex-col items-center gap-3">
                                                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                        </svg>
                                                        <div>
                                                            <p class="font-medium">
                                                                Belum ada data individu untuk periode ini.
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-1">
                                                                Periode mengikuti pilihan di halaman Review Data
                                                                Gizi.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pagination (pakai pagination.red biar sama UI-nya) --}}
                        @if ($dataGizi && $dataGizi->hasPages())
                            <div class="mt-3">
                                {{ $dataGizi->onEachSide(1)->links('pagination.red') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
