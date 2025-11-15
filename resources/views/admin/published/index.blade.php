<x-app-layout>
    <x-slot name="title">
        Admin
    </x-slot>

    {{-- HEADER + SIDEBAR (TAB PER DIVISI) --}}
    @include('admin.published.partials.header-sidebar')

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-2">

            {{-- Flash Message --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                    class="mb-2 flex items-start sm:items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-green-900 truncate">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800"><svg
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                    class="mb-2 flex items-start sm:items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-red-900 truncate">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800"><svg
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            @endif

            {{-- KONTEN UTAMA: PER DIVISI (DARI FILE TERPISAH) --}}
            <div class="mt-0">
                @if ($activeTab === 'gizi')
                    @include('admin.published.divisi.gizi', [
                        'dataGizi'        => $allData['gizi'],
                        'bulkActions'     => $bulkActionData['gizi'],
                        'status'          => $status ?? 'pending',
                        'statusCounts'    => $statusCounts ?? [],
                        'periodYm'        => $periodYm ?? now()->format('Y-m'),
                        'availablePeriods'=> $availablePeriods ?? [],
                    ])
                @elseif ($activeTab === 'pencegahan')
                    @include('admin.published.divisi.pencegahan', [
                        'dataPencegahan' => $allData['pencegahan'],
                        'bulkActions'    => $bulkActionData['pencegahan'],
                    ])
                @elseif ($activeTab === 'penanggulangan')
                    @include('admin.published.divisi.penanggulangan', [
                        'dataPenanggulangan' => $allData['penanggulangan'],
                        'bulkActions'        => $bulkActionData['penanggulangan'],
                    ])
                @elseif ($activeTab === 'desa')
                    @include('admin.published.divisi.desa', [
                        'dataDesa'    => $allData['desa'],
                        'bulkActions' => $bulkActionData['desa'],
                    ])
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
