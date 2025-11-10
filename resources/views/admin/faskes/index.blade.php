<x-app-layout>
    <x-slot name="title">
        RSP | Faskes
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Kelola Fasilitas Kesehatan
            </h2>

            {{-- Tombol kembali --}}
            <a href="{{ route('admin.published.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 3000)"
                     x-transition
                     class="mb-4 sm:mb-6 flex items-start sm:items-center gap-3 rounded-xl bg-green-50 p-3 sm:p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-green-900 truncate">
                            {{ session('success') }}
                        </p>
                    </div>
                    <button @click="show = false"
                            class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Card utama --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                {{-- Header Card --}}
                <div class="px-3 sm:px-6 py-3 sm:py-4 flex justify-between sm:items-center gap-2 sm:gap-4 border-b border-gray-100">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Daftar Fasilitas Kesehatan</p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Total: <span class="font-semibold">{{ $faskes->total() }}</span> faskes terdaftar
                        </p>
                    </div>

                    {{-- Tombol Tambah Faskes (di dalam card) --}}
                    <a href="{{ route('admin.faskes.create') }}"
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 rounded-lg bg-red-600 text-white text-xs sm:text-sm font-medium shadow-sm hover:bg-red-700 hover:shadow-md transition">
                        + Tambah Faskes
                    </a>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr class="text-[10px] sm:text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-left">Nama Faskes</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-center">Jenis</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-center">Desa/Kel</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-center">Koordinat</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-center">Status</th>
                                <th class="px-3 sm:px-4 py-2 sm:py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($faskes as $item)
                                <tr class="hover:bg-gray-50/80">
                                    {{-- Nama Faskes + (Alamat hanya di >= sm) --}}
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 align-top">
                                        <div class="font-medium text-gray-900 text-sm sm:text-base">
                                            {{ $item->nama_faskes }}
                                        </div>
                                        {{-- Alamat hanya tampil di tablet/desktop --}}
                                        <div class="hidden sm:block text-xs text-gray-500 mt-0.5">
                                            {{ $item->alamat ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 align-top text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-blue-50 text-blue-700">
                                            {{ $item->jenis }}
                                        </span>
                                    </td>

                                    {{-- Desa/Kel --}}
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 align-top text-[11px] sm:text-sm text-gray-700 text-center">
                                        {{ $item->desa_kelurahan ?? '-' }}
                                    </td>

                                    {{-- Koordinat --}}
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 align-top text-[10px] sm:text-xs text-gray-600 text-center">
                                        <div>Lat: <span class="font-mono">{{ $item->lat }}</span></div>
                                        <div>Lng: <span class="font-mono">{{ $item->lng }}</span></div>
                                    </td>

                                    {{-- Status (Toggle) --}}
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 align-top text-center">
                                        <form action="{{ route('admin.faskes.toggle-active', $item) }}" method="POST" class="inline-flex items-center gap-1 sm:gap-2">
                                            @csrf
                                            @method('PATCH')

                                            <div class="flex flex-col items-center justify-center">
                                                {{-- Toggle switch --}}
                                                <button type="submit"
                                                        class="relative inline-flex h-4 sm:h-5 w-8 sm:w-9 items-center rounded-full transition
                                                            {{ $item->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                                    <span class="sr-only">Toggle status</span>
                                                    <span class="inline-block h-3.5 sm:h-4 w-3.5 sm:w-4 transform rounded-full bg-white shadow transition
                                                        {{ $item->is_active ? 'translate-x-4 sm:translate-x-4' : 'translate-x-1' }}">
                                                    </span>
                                                </button>

                                                {{-- Label teks kecil --}}
                                                @if ($item->is_active)
                                                    <span class="text-[9px] sm:text-[10px] font-semibold text-green-700 mt-0.5">Aktif</span>
                                                @else
                                                    <span class="text-[9px] sm:text-[10px] font-semibold text-gray-500 mt-0.5">Nonaktif</span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-3 sm:px-4 py-2 sm:py-3 align-top text-right text-[11px] sm:text-xs">
                                        <div class="inline-flex items-center gap-1.5 sm:gap-2">
                                            <a href="{{ route('admin.faskes.edit', $item) }}"
                                               class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-md border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.faskes.destroy', $item) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus faskes ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-md border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 sm:px-4 py-6 text-center text-xs sm:text-sm text-gray-500">
                                        Belum ada data faskes yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($faskes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="px-3 sm:px-6 py-2.5 sm:py-3 border-t border-gray-100 bg-gray-50">
                        {{ $faskes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
