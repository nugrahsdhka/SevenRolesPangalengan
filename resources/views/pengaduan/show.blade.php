{{-- resources/views/pengaduan/show.blade.php --}}
<x-app-layout>
    <x-slot name="title">
        Pengaduan {{ $complaint->ticket_code }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Detail Pengaduan
            </h2>

            <a href="{{ route('complaints.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    @php $isAdmin = auth()->user()->hasRole('IT Admin'); @endphp

    <div class="py-2">
        <div class="max-w-4xl mx-auto px-2 space-y-4">

            @include('Message.flash-message')

            {{-- Detail pengaduan --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-4 sm:p-6 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <p class="font-mono font-bold text-gray-900">{{ $complaint->ticket_code }}</p>
                    <x-complaint-status :status="$complaint->status" />
                </div>

                <h3 class="text-lg font-semibold text-gray-900 break-words">{{ $complaint->judul }}</h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pelapor</dt>
                        <dd class="text-gray-900">{{ $complaint->nama ?: 'Anonim' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kontak</dt>
                        <dd class="text-gray-900 break-words">{{ $complaint->kontak ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Desa / Kelurahan</dt>
                        <dd class="text-gray-900">{{ ucwords(strtolower($complaint->desa_kelurahan)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</dt>
                        <dd class="text-gray-900">{{ $complaint->kategori_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu Masuk</dt>
                        <dd class="text-gray-900">{{ $complaint->created_at->translatedFormat('d F Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ditangani Oleh</dt>
                        <dd class="text-gray-900">{{ $complaint->penangan?->name ?? '-' }}</dd>
                    </div>
                </dl>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Isi Pengaduan</p>
                    <p class="text-sm text-gray-800 whitespace-pre-line break-words">{{ $complaint->isi }}</p>
                </div>
            </div>

            {{-- Tanggapan --}}
            @if ($isAdmin)
                <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}"
                      class="bg-white shadow-sm rounded-xl border border-gray-100 p-4 sm:p-6 space-y-4">
                    @csrf
                    @method('PATCH')

                    <p class="text-sm font-semibold text-gray-800">Tanggapi Pengaduan</p>

                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Status</label>
                        <select id="status" name="status"
                                class="w-full sm:w-60 rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                            @foreach ($statuses as $key => $label)
                                <option value="{{ $key }}" @selected(old('status', $complaint->status) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="tanggapan" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">
                            Tanggapan <span class="font-normal normal-case text-gray-400">(tampil ke pelapor lewat kode tiket)</span>
                        </label>
                        <textarea id="tanggapan" name="tanggapan" rows="5" maxlength="2000"
                                  class="w-full rounded-lg border-gray-300 text-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Tulis tanggapan atau tindak lanjut...">{{ old('tanggapan', $complaint->tanggapan) }}</textarea>
                        @error('tanggapan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow-sm hover:bg-red-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.complaints.destroy', $complaint) }}"
                      onsubmit="return confirm('Hapus pengaduan ini secara permanen?')"
                      class="flex justify-end">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-lg border border-red-200 bg-red-50 text-red-700 text-sm font-medium hover:bg-red-100 transition">
                        Hapus Pengaduan
                    </button>
                </form>
            @else
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-4 sm:p-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggapan Admin</p>
                    @if ($complaint->tanggapan)
                        <p class="text-sm text-gray-800 whitespace-pre-line break-words">{{ $complaint->tanggapan }}</p>
                    @else
                        <p class="text-sm text-gray-500">Belum ada tanggapan. Pengaduan dikelola oleh IT Admin.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
