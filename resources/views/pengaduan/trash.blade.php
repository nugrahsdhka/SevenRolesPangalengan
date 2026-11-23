{{-- resources/views/pengaduan/trash.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        Sampah Pengaduan
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Sampah Pengaduan
            </h2>

            <a href="{{ route('complaints.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Pengaduan Warga
            </a>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto px-2 space-y-4">

            @include('Message.flash-message')

            <div class="rounded-xl bg-amber-50 ring-1 ring-amber-200 p-4 text-sm text-amber-900">
                Pengaduan di sini tidak tampil di daftar pengaduan, tetapi warga masih bisa mengeceknya lewat kode tiket
                sampai dihapus permanen. Pengaduan Selesai/Ditolak juga dihapus permanen otomatis
                {{ \App\Models\Complaint::RETENSI_BULAN }} bulan setelah statusnya diperbarui.
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                <form method="GET" action="{{ route('complaints.trash') }}"
                      class="px-3 sm:px-6 py-3 sm:py-4 flex flex-col md:flex-row gap-2 md:items-center border-b border-gray-100">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode, judul, desa, posyandu, atau nama..."
                           class="flex-1 rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow-sm hover:bg-red-700 transition">
                            Cari
                        </button>
                        @if (request()->filled('q'))
                            <a href="{{ route('complaints.trash') }}"
                               class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] divide-y divide-gray-200 text-xs sm:text-sm">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr class="uppercase text-[10px] sm:text-xs font-semibold text-gray-600 tracking-wide">
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Pengaduan</th>
                                <th class="px-4 py-3 text-left">Desa</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-left">Dibuang</th>
                                <th class="px-4 py-3 text-left">Hapus Otomatis</th>
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
                                    <td class="px-4 py-3 align-top text-center">
                                        <x-complaint-status :status="$c->status" />
                                    </td>
                                    <td class="px-4 py-3 align-top text-xs text-gray-500 whitespace-nowrap">
                                        {{ $c->dibuang_at->translatedFormat('d M Y, H:i') }}
                                        @if ($c->pembuang)
                                            <div>oleh {{ $c->pembuang->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-xs text-gray-500 whitespace-nowrap">
                                        {{ $c->hapusOtomatisPada()?->translatedFormat('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('complaints.show', $c) }}"
                                               class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-300 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                                Detail
                                            </a>

                                            @if (auth()->user()->canManageComplaintTrash($c))
                                                <form method="POST" action="{{ route('complaints.trash.restore', $c) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                                                        Pulihkan
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('complaints.trash.destroy', $c) }}"
                                                      onsubmit="return confirm('Hapus pengaduan ini secara permanen? Tindakan ini tidak bisa dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-semibold text-red-700 hover:bg-red-100 transition">
                                                        Hapus Permanen
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                        Sampah kosong.
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
