<x-app-layout>
    <x-slot name="title">
        Review dan Approval Data
    </x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            {{-- Kiri: Judul dan Nama User --}}
            <div class="flex gap-1 justify-center items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Camat') }}
                </h2>
                <span class="text-gray-300">|</span>
                <h2 class="font-semibold text-xs text-gray-800 leading-tight">
                    {{ auth()->user()->name }}
                </h2>
            </div>
            
            {{-- Kanan: Grup Tombol Aksi --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9.75L12 3l9 6.75M4.5 10.5V21h15V10.5M9 21v-6h6v6" /></svg>
                    <span class="hidden sm:inline">Home</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span class="hidden sm:inline">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if (session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 3000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-6 flex items-start sm:items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-green-900 truncate">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- ✅ PERBAIKAN: Kembalikan state modal --}}
            <div x-data="{ activeTab: '{{ session('active_tab', 'gizi') }}', openModal: false, modalData: null, modalTitle: '' }" class="bg-white shadow-lg rounded-2xl ring-1 ring-black/5 overflow-hidden">
                
                {{-- Navigasi Tab (scrollable di mobile, tetap rata di desktop) --}}
                <div class="border-b border-gray-200">
                    <div class="flex justify-center items-center py-2 border border-b ">
                        <h1 class="text-md font-bold text-red-700">Review dan Approval Data</h1>
                    </div>
                    <nav class="-mb-px flex overflow-x-auto no-scrollbar" aria-label="Tabs">
                        <div class="flex w-full sm:w-auto sm:flex-1">
                            <button
                                @click="activeTab = 'gizi'"
                                :class="{ 'border-red-600 text-red-600': activeTab === 'gizi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gizi' }"
                                class="flex-1 sm:w-1/4 py-3 sm:py-4 px-3 sm:px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 min-w-[10rem] sm:min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <span class="truncate">Data Gizi</span>
                            </button>
                            <button
                                @click="activeTab = 'pencegahan'"
                                :class="{ 'border-red-600 text-red-600': activeTab === 'pencegahan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pencegahan' }"
                                class="flex-1 sm:w-1/4 py-3 sm:py-4 px-3 sm:px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 min-w-[10rem] sm:min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                <span class="truncate">Data Pencegahan</span>
                            </button>
                            <button
                                @click="activeTab = 'penanggulangan'"
                                :class="{ 'border-red-600 text-red-600': activeTab === 'penanggulangan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'penanggulangan' }"
                                class="flex-1 sm:w-1/4 py-3 sm:py-4 px-3 sm:px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 min-w-[10rem] sm:min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                <span class="truncate">Data Penanggulangan</span>
                            </button>
                            <button
                                @click="activeTab = 'desa'"
                                :class="{ 'border-red-600 text-red-600': activeTab === 'desa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'desa' }"
                                class="flex-1 sm:w-1/4 py-3 sm:py-4 px-3 sm:px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 min-w-[10rem] sm:min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" /></svg>
                                <span class="truncate">Data Desa</span>
                            </button>
                        </div>
                    </nav>
                </div>

                {{-- Konten Tab --}}
                <div class="p-4 sm:p-6 lg:p-8">
                    {{-- Konten Gizi --}}
                    <div x-show="activeTab === 'gizi'">
                        @forelse ($allData['gizi'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->judul }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Gizi'" class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    
                                    @if($data->status === 'published')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending</span>
                                    @endif
                                    
                                    @if($data->status !== 'published')
                                        @if($data->status === 'pending')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataGizi">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="approved">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                            </form>
                                        @elseif($data->status === 'approved')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataGizi">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="pending">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');" class="inline">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="model" value="DataGizi">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Gizi untuk direview.</p>
                        @endforelse
                    </div>

                    {{-- Konten Pencegahan --}}
                    <div x-show="activeTab === 'pencegahan'" style="display: none;">
                        @forelse ($allData['pencegahan'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->judul_kegiatan }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Pencegahan'" class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    
                                    @if($data->status === 'published')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending</span>
                                    @endif
                                    
                                    @if($data->status !== 'published')
                                        @if($data->status === 'pending')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataPencegahan">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="approved">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                            </form>
                                        @elseif($data->status === 'approved')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataPencegahan">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="pending">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');" class="inline">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="model" value="DataPencegahan">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Pencegahan untuk direview.</p>
                        @endforelse
                    </div>

                    {{-- Konten Penanggulangan --}}
                    <div x-show="activeTab === 'penanggulangan'" style="display: none;">
                        @forelse ($allData['penanggulangan'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->jenis_bencana }} ({{$data->jumlah_korban}} korban)</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Penanggulangan'" class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    
                                    @if($data->status === 'published')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending</span>
                                    @endif
                                    
                                    @if($data->status !== 'published')
                                        @if($data->status === 'pending')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataPenanggulangan">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="approved">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                            </form>
                                        @elseif($data->status === 'approved')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataPenanggulangan">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="pending">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');" class="inline">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="model" value="DataPenanggulangan">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Penanggulangan untuk direview.</p>
                        @endforelse
                    </div>

                    {{-- Konten Desa --}}
                    <div x-show="activeTab === 'desa'" style="display: none;">
                        @forelse ($allData['desa'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->nama_desa }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Desa'" class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    
                                    @if($data->status === 'published')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700"><span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Pending</span>
                                    @endif
                                    
                                    @if($data->status !== 'published')
                                        @if($data->status === 'pending')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataDesa">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="approved">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button>
                                            </form>
                                        @elseif($data->status === 'approved')
                                            <form action="{{ route('camat.status') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="model" value="DataDesa">
                                                <input type="hidden" name="id" value="{{ $data->id }}">
                                                <input type="hidden" name="new_status" value="pending">
                                                <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                                <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');" class="inline">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="model" value="DataDesa">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Desa untuk direview.</p>
                        @endforelse
                    </div>
                </div>

                {{-- ✅ PERBAIKAN: Kembalikan Struktur Modal --}}
                <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                        {{-- Backdrop --}}
                        <div x-show="openModal" @click="openModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        
                        {{-- Modal Panel --}}
                        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg sm:my-8 sm:align-middle sm:max-w-2xl">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title" x-text="modalTitle"></h3>
                                        <div class="mt-4 text-sm text-gray-600 space-y-4">
                                            <template x-if="modalData">
                                                <div class="space-y-3 max-h-[70vh] sm:max-h-[60vh] overflow-y-auto pr-1 sm:pr-4">
                                                    {{-- Detail untuk Data Gizi --}}
                                                    <template x-if="modalTitle === 'Detail Data Gizi'">
                                                        <div>
                                                            <p class="font-semibold text-gray-800">Judul:</p>
                                                            <p x-text="modalData.judul"></p>
                                                            <p class="font-semibold text-gray-800 mt-2">Deskripsi:</p>
                                                            <p class="preserve-lines" x-text="modalData.deskripsi"></p>
                                                        </div>
                                                    </template>
                                                    {{-- Detail untuk Data Pencegahan --}}
                                                    <template x-if="modalTitle === 'Detail Data Pencegahan'">
                                                        <div>
                                                            <p><span class="font-semibold text-gray-800">Judul Kegiatan:</span> <span x-text="modalData.judul_kegiatan"></span></p>
                                                            <p><span class="font-semibold text-gray-800">Lokasi:</span> <span x-text="modalData.lokasi"></span></p>
                                                            <p><span class="font-semibold text-gray-800">Tanggal Kegiatan:</span> <span x-text="new Date(modalData.tanggal_kegiatan).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span></p>
                                                            <p class="font-semibold text-gray-800 mt-2">Laporan Singkat:</p>
                                                            <p class="preserve-lines" x-text="modalData.laporan_singkat"></p>
                                                        </div>
                                                    </template>
                                                    {{-- Detail untuk Data Penanggulangan --}}
                                                    <template x-if="modalTitle === 'Detail Data Penanggulangan'">
                                                        <div>
                                                            <p><span class="font-semibold text-gray-800">Jenis Bencana:</span> <span x-text="modalData.jenis_bencana"></span></p>
                                                            <p><span class="font-semibold text-gray-800">Jumlah Korban:</span> <span x-text="modalData.jumlah_korban"></span></p>
                                                            <p class="font-semibold text-gray-800 mt-2">Tindakan:</p>
                                                            <p class="preserve-lines" x-text="modalData.tindakan"></p>
                                                        </div>
                                                    </template>
                                                    {{-- Detail untuk Data Desa --}}
                                                    <template x-if="modalTitle === 'Detail Data Desa'">
                                                        <div>
                                                            <p><span class="font-semibold text-gray-800">Nama Desa:</span> <span x-text="modalData.nama_desa"></span></p>
                                                            <p><span class="font-semibold text-gray-800">Jumlah Penduduk:</span> <span x-text="new Intl.NumberFormat('id-ID').format(modalData.jumlah_penduduk)"></span></p>
                                                            <p><span class="font-semibold text-gray-800">Luas Wilayah:</span> <span x-text="new Intl.NumberFormat('id-ID').format(modalData.luas_wilayah)"></span> m²</p>
                                                            <p><span class="font-semibold text-gray-800">Jumlah Kasus Stunting:</span> <span x-text="modalData.jumlah_kasus_stunting" class="font-bold text-red-600"></span></p>
                                                            <p class="font-semibold text-gray-800 mt-2">Potensi Desa:</p>
                                                            <p class="preserve-lines" x-text="modalData.potensi_desa"></p>
                                                        </div>
                                                    </template>
                                                    
                                                    <div class="border-t pt-3 mt-3">
                                                        <p><span class="font-semibold text-gray-800">Periode Laporan:</span> <span x-text="new Date(modalData.periode).toLocaleString('id-ID', { month: 'long', year: 'numeric' })"></span></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button @click="openModal = false" type="button" class="mt-3 sm:mt-0 sm:ml-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Optional: sembunyikan scrollbar mobile (tidak wajib, tidak mengubah UI) --}}
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .preserve-lines { white-space: pre-line; }
    </style>
</x-app-layout>
