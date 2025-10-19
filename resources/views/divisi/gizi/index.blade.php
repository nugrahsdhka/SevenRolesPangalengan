<x-app-layout>
    <x-slot name="title">
        Manajemen Data Gizi
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex gap-1 justify-center items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Divisi Gizi') }}
                </h2>
                <span class="text-gray-300">|</span>
                <h2 class="font-semibold text-xs text-gray-800 leading-tight">
                    {{ auth()->user()->name }}
                </h2>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Log Out
                </button>
            </form>
        </div>
    </x-slot>

    {{-- ✅ PERBAIKAN 1: Tambahkan state untuk modal (openModal, modalData) --}}
    <div class="py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50" x-data="{ openModal: false, modalData: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="mb-6 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="flex-1"><p class="text-sm font-medium text-green-900">{{ session('success') }}</p></div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="flex-1"><p class="text-sm font-medium text-red-900">{{ session('error') }}</p></div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
            @endif

            {{-- Main Card --}}
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar Data Gizi</h3>
                        <p class="mt-1 text-sm text-gray-500">Kelola dan pantau data gizi yang telah diinput</p>
                    </div>
                    <a href="{{ route('gizi.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-red-600 to-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-red-700 hover:to-rose-700 hover:shadow-lg">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Data
                    </a>
                </div>

                {{-- Table --}}
                <div class="mt-6 overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-200">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Periode</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Judul Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($data as $item)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $item->periode->format('F Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4"><div class="text-sm font-semibold text-gray-900">{{ $item->judul }}</div></td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if($item->status === 'published')<span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published</span>
                                            @elseif($item->status === 'approved')<span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved</span>
                                            @else<span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending</span>@endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-center gap-3">
                                                {{-- ✅ PERBAIKAN 2: Tambahkan tombol "Lihat Detail" --}}
                                                <button @click="openModal = true; modalData = {{ Js::from($item) }}" class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800">
                                                    Detail
                                                </button>
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('gizi.edit', $item->id) }}" class="text-sm font-medium text-blue-600 transition hover:text-blue-800">Edit</a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('gizi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 transition hover:text-red-800">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                            <p class="mt-4 text-sm text-gray-500">Belum ada data gizi yang diinput</p>
                                            <a href="{{ route('gizi.create') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                Tambah Data Pertama
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($data->hasPages())
                    <div class="mt-6">
                        {{ $data->links() }}
                    </div>
                @endif
            </section>
        </div>

        {{-- ✅ PERBAIKAN 3: Tambahkan struktur Modal --}}
        <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="openModal" @click="openModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Detail Data Gizi</h3>
                                <div class="mt-4 text-sm text-gray-600 space-y-4">
                                    <template x-if="modalData">
                                        <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-4">
                                            <div>
                                                <p><span class="font-semibold text-gray-800">Periode:</span> <span x-text="new Date(modalData.periode).toLocaleString('id-ID', { month: 'long', year: 'numeric' })"></span></p>
                                                <p class="font-semibold text-gray-800 mt-2">Judul:</p>
                                                <p x-text="modalData.judul"></p>
                                                <p class="font-semibold text-gray-800 mt-2">Deskripsi:</p>
                                                <p class="preserve-lines" x-text="modalData.deskripsi"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="openModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>