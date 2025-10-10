<x-app-layout>
    <x-slot name="title">
        Review dan Approval Data
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Review dan Approval Data') }}
            </h2>
            
            <div class="flex gap-2">
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Home
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi dengan Alpine.js --}}
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

            <div x-data="{ activeTab: '{{ session('active_tab', 'gizi') }}' }" class="bg-white shadow-lg rounded-2xl ring-1 ring-black/5 overflow-hidden">
                
                {{-- Navigasi Tab --}}
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        <button @click="activeTab = 'gizi'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'gizi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gizi' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            Data Gizi
                        </button>

                        <button @click="activeTab = 'pencegahan'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'pencegahan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pencegahan' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            Data Pencegahan
                        </button>

                         <button @click="activeTab = 'penanggulangan'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'penanggulangan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'penanggulangan' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            Data Penanggulangan
                        </button>

                         <button @click="activeTab = 'desa'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'desa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'desa' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" /></svg>
                            Data Desa
                        </button>
                    </nav>
                </div>

                {{-- Konten Tab --}}
                <div class="p-6 sm:p-8">
                    {{-- Konten Gizi --}}
                    <div x-show="activeTab === 'gizi'">
                         @forelse ($allData['gizi'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->judul }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                    @if($data->status === 'pending')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataGizi"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="approved"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button> </form>
                                    @elseif($data->status === 'approved')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataGizi"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="pending"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button> </form>
                                    @endif
                                    <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');"> @csrf @method('DELETE') <input type="hidden" name="model" value="DataGizi"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button> </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Gizi untuk direview.</p>
                        @endforelse
                    </div>

                    {{-- Konten Pencegahan --}}
                    <div x-show="activeTab === 'pencegahan'" style="display: none;">
                         @forelse ($allData['pencegahan'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->judul_kegiatan }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                    @if($data->status === 'pending')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataPencegahan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="approved"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button> </form>
                                    @elseif($data->status === 'approved')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataPencegahan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="pending"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button> </form>
                                    @endif
                                    <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');"> @csrf @method('DELETE') <input type="hidden" name="model" value="DataPencegahan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button> </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Pencegahan untuk direview.</p>
                        @endforelse
                    </div>

                    {{-- Konten Penanggulangan --}}
                    <div x-show="activeTab === 'penanggulangan'" style="display: none;">
                        @forelse ($allData['penanggulangan'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->jenis_bencana }} ({{$data->jumlah_korban}} korban)</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                    @if($data->status === 'pending')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataPenanggulangan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="approved"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button> </form>
                                    @elseif($data->status === 'approved')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataPenanggulangan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="pending"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button> </form>
                                    @endif
                                    <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');"> @csrf @method('DELETE') <input type="hidden" name="model" value="DataPenanggulangan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button> </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Penanggulangan untuk direview.</p>
                        @endforelse
                    </div>

                    {{-- Konten Desa --}}
                    <div x-show="activeTab === 'desa'" style="display: none;">
                        @forelse ($allData['desa'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">Penduduk: {{ number_format($data->jumlah_penduduk) }} | Luas: {{ number_format($data->luas_wilayah) }} m²</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                    @if($data->status === 'pending')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataDesa"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="approved"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-green-600 hover:bg-green-700">Approve</button> </form>
                                    @elseif($data->status === 'approved')
                                        <form action="{{ route('camat.status') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataDesa"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="new_status" value="pending"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-yellow-600 hover:bg-yellow-700">Tarik</button> </form>
                                    @endif
                                    <form action="{{ route('camat.destroy') }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');"> @csrf @method('DELETE') <input type="hidden" name="model" value="DataDesa"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg bg-red-600 hover:bg-red-700">Hapus</button> </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Desa untuk direview.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>