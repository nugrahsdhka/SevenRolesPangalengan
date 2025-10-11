<x-app-layout>
    <x-slot name="title">
        Divisi Pencegahan
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Data Pencegahan') }}
            </h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Log Out
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="p-6 sm:p-8 bg-white shadow-lg rounded-2xl ring-1 ring-black/5">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-red-900">Daftar Data Terinput</h3>
                        <p class="mt-1 text-sm text-gray-600">Berikut adalah semua data yang telah Anda masukkan.</p>
                    </div>
                    <a href="{{ route('pencegahan.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + Tambah Data
                    </a>
                </div>

                <div class="space-y-4 mb-8">
                    @if (session('success'))
                        {{-- ✅ PERBAIKAN: Tambahkan atribut x-data, x-show, x-init, dan x-transition --}}
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 500)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" 
                             role="alert">
                            <p class="font-bold">Sukses</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        {{-- ✅ PERBAIKAN: Tambahkan atribut x-data, x-show, x-init, dan x-transition --}}
                        <div x-data="{ show: true }"
                             x-show="show"
                             x-init="setTimeout(() => show = false, 500)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" 
                             role="alert">
                             <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                </div>

                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-l-lg">Periode</th>
                                <th scope="col" class="px-6 py-3">Judul Kegiatan</th>
                                <th scope="col" class="px-6 py-3">Lokasi</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3 rounded-r-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $item->periode->format('F Y') }}</td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $item->judul_kegiatan }}</th>
                                    <td class="px-6 py-4">{{ $item->lokasi }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        {{-- ✅ PERBAIKAN: Logika status yang benar --}}
                                        @if($item->status === 'published')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                        @elseif($item->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data yang diinput. Silakan klik tombol "+ Tambah Data".
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($data->hasPages())
                    <div class="mt-6">
                        {{ $data->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>