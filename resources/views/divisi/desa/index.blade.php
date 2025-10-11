<x-app-layout>
    <x-slot name="title">
        Manajemen Data Desa
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Data Desa') }}
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
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-red-900">Daftar Data Terinput</h3>
                        <p class="mt-1 text-sm text-gray-600">Berikut adalah semua data yang telah Anda masukkan.</p>
                    </div>
                    {{-- ✅ PERBAIKAN: Tombol "Tambah Data" kondisional --}}
                    @if($canCreate)
                        <a href="{{ route('desa.create') }}" class="flex-shrink-0 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            + Tambah Data 
                        </a>
                    @else
                        <div class="flex-shrink-0 text-right">
                             <span class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-500 uppercase tracking-widest cursor-not-allowed">
                                + Tambah Data
                            </span>
                            <p class="mt-1 text-xs text-gray-500">Data untuk periode ini sudah diinput.</p>
                        </div>
                    @endif
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
                                <th scope="col" class="px-6 py-3">Nama Desa</th>
                                <th scope="col" class="px-6 py-3">Jumlah Penduduk</th>
                                <th scope="col" class="px-6 py-3">Kasus Stunting</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3 rounded-r-lg text-center">Aksi</th> {{-- Kolom Aksi --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $item->periode->format('F Y') }}</td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $item->nama_desa }}</th>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->jumlah_penduduk }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->jumlah_kasus_stunting }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->status === 'published')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                        @elseif($item->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    {{-- ✅ PERBAIKAN: Tombol Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="{{ route('desa.edit', $item->id) }}" class="font-medium text-blue-600 hover:text-blue-800">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('desa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data yang diinput.
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