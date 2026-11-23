<x-guest-layout>
    <x-slot name="title">
        RSP | Cek Status Pengaduan
    </x-slot>

    @include('layouts.nav_public')

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section class="max-w-3xl mx-auto px-4 py-6">

            <div class="mb-6 text-center md:text-left">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                    Cek Status <span class="text-primary-500">Pengaduan</span>
                </h1>
                <p class="mt-1 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                    Masukkan kode tiket yang Anda terima saat mengirim pengaduan
                </p>
            </div>

            @include('Message.flash-message')

            {{-- Form cari kode --}}
            <form method="GET" action="{{ route('pengaduan.track') }}"
                  class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 md:p-6 flex flex-col sm:flex-row gap-3">
                <input type="text" name="kode" value="{{ $kode }}" maxlength="20" required
                       class="flex-1 rounded-xl border-gray-300 text-sm font-mono uppercase tracking-wider focus:border-primary-500 focus:ring-primary-500"
                       placeholder="Contoh: PGD-7K2M9QXA">
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-black uppercase tracking-widest hover:bg-primary-500 transition-all duration-500">
                    Cek
                </button>
            </form>

            {{-- Hasil --}}
            @if ($searched)
                @if ($complaint)
                    <div class="mt-6 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode Tiket</p>
                                <p class="font-mono font-bold text-gray-900 text-lg">{{ $complaint->ticket_code }}</p>
                            </div>
                            <x-complaint-status :status="$complaint->status" class="!text-sm !px-3 !py-1" />
                        </div>

                        <div class="border-t border-gray-100 pt-5 space-y-3">
                            <h2 class="font-bold text-gray-900 break-words">{{ $complaint->judul }}</h2>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                <span>{{ $complaint->kategori_label }}</span>
                                <span>&middot;</span>
                                <span>{{ ucwords(strtolower($complaint->desa_kelurahan)) }}</span>
                                @if ($complaint->posyandu)
                                    <span>&middot;</span>
                                    <span>Posyandu {{ $complaint->posyandu }}</span>
                                @endif
                                <span>&middot;</span>
                                <span>Dikirim {{ $complaint->created_at->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                            <p class="text-sm text-gray-700 whitespace-pre-line break-words">{{ $complaint->isi }}</p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggapan Petugas</p>
                            @if ($complaint->tanggapan)
                                <p class="text-sm text-gray-800 whitespace-pre-line break-words">{{ $complaint->tanggapan }}</p>
                                <p class="mt-2 text-xs text-gray-400">
                                    {{ $complaint->ditanggapi_at?->translatedFormat('d F Y, H:i') }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500">Belum ada tanggapan. Pengaduan Anda akan segera ditinjau.</p>
                            @endif
                        </div>

                        @if ($complaint->hapusOtomatisPada())
                            <p class="text-xs text-gray-400">
                                Data pengaduan ini disimpan sampai {{ $complaint->hapusOtomatisPada()->translatedFormat('d F Y') }}, setelah itu dihapus otomatis.
                            </p>
                        @endif
                    </div>
                @else
                    <div class="mt-6 rounded-2xl bg-red-50 ring-1 ring-red-200 p-4 text-sm text-red-800">
                        Kode tiket <span class="font-mono font-bold">{{ $kode }}</span> tidak ditemukan.
                        Periksa kembali penulisannya.
                    </div>
                @endif
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('pengaduan.create') }}"
                   class="text-sm font-semibold text-gray-500 hover:text-primary-500 transition">
                    &larr; Buat pengaduan baru
                </a>
            </div>
        </section>
    </div>
</x-guest-layout>
