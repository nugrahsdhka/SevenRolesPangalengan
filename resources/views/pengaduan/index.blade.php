{{-- resources/views/pengaduan/index.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        Pengaduan Warga
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Pengaduan Warga
            </h2>

            <div class="flex items-center gap-2">
                @if (! is_null($trashCount))
                    <a href="{{ route('complaints.trash') }}"
                       class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Sampah
                        @if ($trashCount > 0)
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-bold text-gray-600">{{ $trashCount }}</span>
                        @endif
                    </a>
                @endif

                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-2 space-y-4">

            @include('Message.flash-message')

            {{-- Ringkasan status --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach ($statuses as $key => $label)
                    <a href="{{ route('complaints.index', ['status' => $key]) }}"
                       class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition {{ request('status') === $key ? 'ring-2 ring-red-500' : '' }}">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $label }}</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $counts[$key] ?? 0 }}</p>
                    </a>
                @endforeach
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                {{-- Filter --}}
                <form method="GET" action="{{ route('complaints.index') }}"
                      class="px-3 sm:px-6 py-3 sm:py-4 flex flex-col md:flex-row gap-2 md:items-center border-b border-gray-100">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, judul, desa, posyandu, atau nama..."
                           class="flex-1 rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">

                    <select name="status" class="rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Semua status</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="kategori" class="rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Semua kategori</option>
                        @foreach ($kategori as $key => $label)
                            <option value="{{ $key }}" @selected(request('kategori') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow-sm hover:bg-red-700 transition">
                            Filter
                        </button>
                        @if (request()->hasAny(['q', 'status', 'kategori']))
                            <a href="{{ route('complaints.index') }}"
                               class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr class="uppercase text-[10px] sm:text-xs font-semibold text-gray-600 tracking-wide">
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Pengaduan</th>
                                <th class="px-4 py-3 text-left">Desa</th>
                                <th class="px-4 py-3 text-left">Kategori</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-left">Masuk</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse ($complaints as $c)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 align-top font-mono text-xs text-gray-700 whitespace-nowrap">
                                        {{ $c->ticket_code }}
                                    </td>
                                    <td class="px-4 py-3 align-top max-w-xs">
                                        <div class="font-medium text-gray-900 break-words">{{ $c->judul }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $c->nama ?: 'Anonim' }}</div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        {{ ucwords(strtolower($c->desa_kelurahan)) }}
                                        @if ($c->posyandu)
                                            <div class="text-xs text-gray-500">{{ $c->posyandu }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-xs text-gray-600">{{ $c->kategori_label }}</td>
                                    <td class="px-4 py-3 align-top text-center">
                                        <x-complaint-status :status="$c->status" />
                                    </td>
                                    <td class="px-4 py-3 align-top text-xs text-gray-500 whitespace-nowrap">
                                        {{ $c->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('complaints.show', $c) }}"
                                               class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                                Detail
                                            </a>
                                            @if ($c->isStatusAkhir() && auth()->user()->canManageComplaintTrash($c))
                                                <form method="POST" action="{{ route('complaints.trash.move', $c) }}"
                                                      onsubmit="return confirm('Pindahkan pengaduan ini ke sampah? Warga tetap bisa mengeceknya lewat kode tiket.')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                                        Buang
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                        Belum ada pengaduan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($complaints->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $complaints->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
