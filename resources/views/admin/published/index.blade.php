<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-800 leading-tight">
                {{ __('Penerbitan Data (Approved by Camat)') }}
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

            @if (session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-900 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-200 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ============================ GIZI ============================ --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10">
                        <span class="text-red-900">Data Gizi</span> <span class="text-sm text-gray-400">(Siap Publish / Sudah Publish)</span>
                    </h3>
                    @forelse ($dataToReview['gizi'] as $data)
                        <div class="py-4 border-b border-white/10 last:border-0 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-red-900">{{ $data->judul }}</p>
                                <p class="text-sm text-gray-100/90 mt-1">{{ Str::limit($data->deskripsi, 80) }}</p>
                                <p class="text-xs text-gray-300 mt-2">
                                    Diinput oleh: <span class="font-medium text-gray-300">{{ $data->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    Dibuat pada: {{ $data->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                @if($data->status === 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @endif
                                <form action="{{ route('admin.publish') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="model" value="DataGizi">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                        {{ $data->status === 'published' ? 'Pull' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data Gizi yang di-approved.</p>
                    @endforelse
                </div>
            </div>

            {{-- ======================== PENCEGAHAN ========================= --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10">
                        <span class="text-red-900">Data Pencegahan</span> <span class="text-sm text-gray-400">(Siap Publish / Sudah Publish)</span>
                    </h3>
                    @forelse ($dataToReview['pencegahan'] as $data)
                        <div class="py-4 border-b border-white/10 last:border-0 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-red-900">{{ $data->judul_kegiatan }}</p>
                                <p class="text-sm text-gray-100/90 mt-1">{{ Str::limit($data->laporan_singkat, 80) }}</p>
                                <p class="text-xs text-gray-300 mt-2">
                                    Diinput oleh: <span class="font-medium text-gray-300">{{ $data->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    Dibuat pada: {{ $data->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                             <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                @if($data->status === 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @endif
                                <form action="{{ route('admin.publish') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="model" value="DataPencegahan">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                        {{ $data->status === 'published' ? 'Pull' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data Pencegahan yang di-approved.</p>
                    @endforelse
                </div>
            </div>
            
            {{-- ✅ PERBAIKAN: BLOK UNTUK PENANGGULANGAN DITAMBAHKAN --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10">
                        <span class="text-red-900">Data Penanggulangan</span> <span class="text-sm text-gray-400">(Siap Publish / Sudah Publish)</span>
                    </h3>
                    @forelse ($dataToReview['penanggulangan'] as $data)
                        <div class="py-4 border-b border-white/10 last:border-0 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-red-900">{{ $data->jenis_bencana }} ({{$data->jumlah_korban}} Korban)</p>
                                <p class="text-sm text-gray-100/90 mt-1">{{ Str::limit($data->tindakan, 80) }}</p>
                                <p class="text-xs text-gray-300 mt-2">
                                    Diinput oleh: <span class="font-medium text-gray-300">{{ $data->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    Dibuat pada: {{ $data->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                             <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                @if($data->status === 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @endif
                                <form action="{{ route('admin.publish') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="model" value="DataPenanggulangan">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                        {{ $data->status === 'published' ? 'Pull' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data Penanggulangan yang di-approved.</p>
                    @endforelse
                </div>
            </div>

            {{-- ✅ PERBAIKAN: BLOK UNTUK DESA DITAMBAHKAN --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 pb-2 border-b border-white/10">
                        <span class="text-red-900">Data Desa</span> <span class="text-sm text-gray-400">(Siap Publish / Sudah Publish)</span>
                    </h3>
                    @forelse ($dataToReview['desa'] as $data)
                        <div class="py-4 border-b border-white/10 last:border-0 flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-red-900">Penduduk: {{ number_format($data->jumlah_penduduk) }} | Luas: {{ number_format($data->luas_wilayah) }} m²</p>
                                <p class="text-sm text-gray-100/90 mt-1">{{ Str::limit($data->potensi_desa, 80) }}</p>
                                <p class="text-xs text-gray-300 mt-2">
                                    Diinput oleh: <span class="font-medium text-gray-300">{{ $data->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    Dibuat pada: {{ $data->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                             <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                @if($data->status === 'published')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                @endif
                                <form action="{{ route('admin.publish') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="model" value="DataDesa">
                                    <input type="hidden" name="id" value="{{ $data->id }}">
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-center text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">
                                        {{ $data->status === 'published' ? 'Pull' : 'Publish' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-300">Tidak ada data Desa yang di-approved.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>