<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-800 leading-tight">
                {{ __('Manajemen Data Pencegahan') }}
            </h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                        class="text-red-600 hover:text-red-800"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Card transparan --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <a href="{{ route('pencegahan.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 mb-4">
                        + Tambah Data Pencegahan
                    </a>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{-- Tabel --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-100">
                            <thead class="text-xs uppercase bg-red-700 text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Judul Kegiatan</th>
                                    <th scope="col" class="px-6 py-3">Lokasi</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr class="bg-white/5 border-b border-white/10 hover:bg-white/10 transition">
                                        <th scope="row" class="px-6 py-4 font-medium text-red-900 whitespace-nowrap">
                                            {{ $item->judul_kegiatan }}
                                        </th>
                                        <td class="px-6 py-4 text-red-900">{{ $item->lokasi }}</td>
                                        <td class="px-6 py-4 text-red-900">
                                            {{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->is_published)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Published
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-200">
                                            Belum ada data yang diinput.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">{{ $data->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
