<x-app-layout>
    @php
        $periodYm = $currentPeriod ? \Carbon\Carbon::parse($currentPeriod)->format('Y-m') : null;

        $tabItems = [
            'pending' => [
                'label' => 'Pending',
                'bg' => 'bg-amber-50',
                'text' => 'text-amber-700',
                'border' => 'border-amber-500',
                'badge_bg' => 'bg-amber-100',
                'badge_text' => 'text-amber-700',
                'dot' => 'bg-amber-500',
            ],
            'approved' => [
                'label' => 'Approved',
                'bg' => 'bg-blue-50',
                'text' => 'text-blue-700',
                'border' => 'border-blue-500',
                'badge_bg' => 'bg-blue-100',
                'badge_text' => 'text-blue-700',
                'dot' => 'bg-blue-500',
            ],
            'published' => [
                'label' => 'Published',
                'bg' => 'bg-emerald-50',
                'text' => 'text-emerald-700',
                'border' => 'border-emerald-500',
                'badge_bg' => 'bg-emerald-100',
                'badge_text' => 'text-emerald-700',
                'dot' => 'bg-emerald-500',
            ],
        ];

        $counts = array_merge(['pending' => 0, 'approved' => 0, 'published' => 0], $statusCounts ?? []);
        $section = $view ?? 'data';
    @endphp

    {{-- TITLE DINAMIS --}}
    <x-slot name="title">
        @if ($section === 'tambah')
            Gizi | Tambah Data
        @elseif ($section === 'detail')
            Gizi | Detail Data
        @else
            Divisi Gizi - {{ $currentUser->name }}
        @endif
    </x-slot>

    {{-- HEADER + SIDEBAR DIVISI GIZI --}}
    @include('divisi.gizi.partials.header-sidebar')

    <div class="px-1 py-1">
        <div class="max-w-7xl w-full mx-auto">

            <div class="px-2 py-0">
                {{-- Alerts --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="my-3 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
    
                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="my-3 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
    
                @if ($errors->any())
                    <div class="my-3 p-4 bg-red-50 text-red-800 rounded-xl ring-1 ring-red-200 text-sm">
                        <p class="font-bold mb-2">Terjadi kesalahan validasi:</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row">
                {{-- MAIN CONTENT --}}
                <section class="flex-1 rounded-2xl shadow-lg">
                    @if ($section === 'tambah')
                        {{-- HALAMAN TAMBAH (TAB INPUT MANUAL / IMPORT EXCEL) --}}
                        @include('divisi.gizi.create')

                    @elseif ($section === 'detail')
                        {{-- HALAMAN DETAIL PER DESA --}}
                        @include('divisi.gizi.detail')

                    @else
                        {{-- === HALAMAN DATA (AGREGASI PER DESA) === --}}
                        @include('divisi.gizi.dataInput')
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
