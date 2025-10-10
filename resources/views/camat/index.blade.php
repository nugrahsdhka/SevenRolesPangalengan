<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-800 leading-tight">
                {{ __('Review dan Approval Data') }}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-900 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded-lg relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ==================== Data Gizi ==================== --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10 text-red-900">Data Gizi</h3>
                    @forelse ($allData['gizi'] as $data)
                        <div class="border-b border-white/10 py-3 flex justify-between items-center hover:bg-white/5 transition">
                            <div>
                                <p class="font-semibold text-red-900">{{ $data->judul }}</p>
                                <p class="text-sm text-gray-300">Diinput oleh: {{ $data->user->name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Badge Status --}}
                                @if($data->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif

                                {{-- Aksi Berdasarkan Status --}}
                                @if($data->status === 'pending')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataGizi">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="approved">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                    </form>
                                @elseif($data->status === 'approved')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataGizi">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="pending">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                    </form>
                                @endif

                                {{-- Form untuk Hapus --}}
                                <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="model" value="DataGizi">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data untuk direview.</p>
                    @endforelse
                </div>
            </div>
            
            {{-- ==================== Data Pencegahan ==================== --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10 text-red-900">Data Pencegahan</h3>
                     @forelse ($allData['pencegahan'] as $data)
                        <div class="border-b border-white/10 py-3 flex justify-between items-center hover:bg-white/5 transition">
                            <div>
                                <p class="font-semibold text-red-900">{{ $data->judul_kegiatan }}</p>
                                <p class="text-sm text-gray-300">Diinput oleh: {{ $data->user->name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($data->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                                @if($data->status === 'pending')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataPencegahan">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="approved">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                    </form>
                                @elseif($data->status === 'approved')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataPencegahan">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="pending">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                    </form>
                                @endif
                                <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="model" value="DataPencegahan">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data untuk direview.</p>
                    @endforelse
                </div>
            </div>

            {{-- ✅ PERBAIKAN: BLOK DATA PENANGGULANGAN --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10 text-red-900">Data Penanggulangan</h3>
                     @forelse ($allData['penanggulangan'] as $data)
                        <div class="border-b border-white/10 py-3 flex justify-between items-center hover:bg-white/5 transition">
                            <div>
                                <p class="font-semibold text-red-900">{{ $data->jenis_bencana }} ({{$data->jumlah_korban}} korban)</p>
                                <p class="text-sm text-gray-300">Diinput oleh: {{ $data->user->name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($data->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                                @if($data->status === 'pending')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataPenanggulangan">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="approved">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                    </form>
                                @elseif($data->status === 'approved')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataPenanggulangan">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="pending">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                    </form>
                                @endif
                                <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="model" value="DataPenanggulangan">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data untuk direview.</p>
                    @endforelse
                </div>
            </div>

            {{-- ✅ PERBAIKAN: BLOK DATA DESA --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10 text-red-900">Data Desa</h3>
                     @forelse ($allData['desa'] as $data)
                        <div class="border-b border-white/10 py-3 flex justify-between items-center hover:bg-white/5 transition">
                            <div>
                                <p class="font-semibold text-red-900">Penduduk: {{ number_format($data->jumlah_penduduk) }} | Luas: {{ number_format($data->luas_wilayah) }} m²</p>
                                <p class="text-sm text-gray-300">Diinput oleh: {{ $data->user->name }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($data->status === 'approved')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                                @if($data->status === 'pending')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataDesa">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="approved">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                    </form>
                                @elseif($data->status === 'approved')
                                    <form action="{{ route('camat.status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="model" value="DataDesa">
                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                        <input type="hidden" name="new_status" value="pending">
                                        <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                    </form>
                                @endif
                                <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini secara permanen?');">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="model" value="DataDesa">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data untuk direview.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>