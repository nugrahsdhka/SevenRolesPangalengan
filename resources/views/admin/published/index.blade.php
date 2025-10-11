<x-app-layout>
    <x-slot name="title">
        Penerbitan Data
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Penerbitan Data - Approved by Camat') }}
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
            {{-- Notifikasi --}}
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

            {{-- Kontainer Utama untuk Tab --}}
            <div x-data="{ activeTab: '{{ session('active_tab', 'gizi') }}' }" class="bg-white shadow-lg rounded-2xl ring-1 ring-black/5 overflow-hidden">
                
                {{-- Navigasi Tab --}}
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex" aria-label="Tabs">
                        {{-- Tab Gizi --}}
                        <button @click="activeTab = 'gizi'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'gizi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gizi' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                            Data Gizi
                        </button>

                        {{-- Tab Pencegahan --}}
                        <button @click="activeTab = 'pencegahan'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'pencegahan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pencegahan' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                             <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" /></svg>
                            Data Pencegahan
                        </button>

                         {{-- Tab Penanggulangan --}}
                         <button @click="activeTab = 'penanggulangan'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'penanggulangan', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'penanggulangan' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                             <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            Data Penanggulangan
                        </button>

                         {{-- Tab Desa --}}
                         <button @click="activeTab = 'desa'" 
                                :class="{ 'border-red-600 text-red-600': activeTab === 'desa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'desa' }"
                                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" /></svg>
                            Data Desa
                        </button>
                    </nav>
                </div>

                {{-- Konten Tab --}}
                <div class="p-6 sm:p-8">
                    {{-- Konten Gizi --}}
                    <div x-show="activeTab === 'gizi'">
                         @forelse ($dataToReview['gizi'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->judul }}</p>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->deskripsi }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Dibuat pada: {{ $data->created_at->format('d M Y') }} | Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                    @if($data->status === 'published')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif
                                    
                                    @if($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataGizi"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">{{ $data->status === 'published' ? 'Tarik' : 'Publish' }}</button></form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Gizi untuk diproses.</p>
                        @endforelse
                    </div>

                    {{-- Konten Pencegahan --}}
                    <div x-show="activeTab === 'pencegahan'" style="display: none;">
                        @forelse ($dataToReview['pencegahan'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->judul_kegiatan }}</p>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->deskripsi }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Dibuat pada: {{ $data->created_at->format('d M Y') }} | Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                    @if($data->status === 'published')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif

                                    @if($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataPencegahan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">{{ $data->status === 'published' ? 'Tarik' : 'Publish' }}</button></form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Pencegahan untuk diproses.</p>
                        @endforelse
                    </div>

                    {{-- Konten Penanggulangan --}}
                    <div x-show="activeTab === 'penanggulangan'" style="display: none;">
                        @forelse ($dataToReview['penanggulangan'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->jenis_bencana }} ({{$data->jumlah_korban}} Korban)</p>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->deskripsi }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Dibuat pada: {{ $data->created_at->format('d M Y') }} | Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                    @if($data->status === 'published')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif

                                    @if($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataPenanggulangan"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">{{ $data->status === 'published' ? 'Tarik' : 'Publish' }}</button></form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Penanggulangan untuk diproses.</p>
                        @endforelse
                    </div>

                    {{-- Konten Desa --}}
                    <div x-show="activeTab === 'desa'" style="display: none;">
                        @forelse ($dataToReview['desa'] as $data)
                            <div class="py-4 border-b border-black/10 last:border-0 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $data->nama_desa }} (Kasus Stunting: <span class="text-red-700">{{ $data->jumlah_kasus_stunting }}</span>)</p>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->deskripsi }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> | Dibuat pada: {{ $data->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex items-center space-x-4 flex-shrink-0 ml-4">
                                    @if($data->status === 'published')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Published</span>
                                    @elseif($data->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @endif

                                    @if($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST"> @csrf <input type="hidden" name="model" value="DataDesa"> <input type="hidden" name="id" value="{{ $data->id }}"> <input type="hidden" name="active_tab" x-bind:value="activeTab"> <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }}">{{ $data->status === 'published' ? 'Tarik' : 'Publish' }}</button></form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4">Tidak ada data Desa untuk diproses.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>