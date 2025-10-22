<x-app-layout>
    <x-slot name="title">
        Manajemen User
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            {{-- Kiri: Judul Divisi + Nama User --}}
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight whitespace-nowrap">
                    {{ __('Admin') }}
                </h2>
            </div>

            {{-- Kanan: Dropdown Menu User (Lingkaran Profile) --}}
            <a href="{{ route('admin.published.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50" x-data="{ openModal: false, selectedUser: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert (Sudah Responsif) --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="mb-6 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="flex-1"><p class="text-sm font-medium text-green-900">{{ session('success') }}</p></div>
                </div>
            @endif
             @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                    <div class="flex-1"><p class="text-sm font-medium text-red-900">{{ session('error') }}</p></div>
                </div>
            @endif

            {{-- Main Card --}}
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- ✅ PERBAIKAN: flex-col sm:flex-row agar menumpuk di mobile --}}
                <div class="flex flex-col sm:flex-row items-start justify-between mb-6 gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Daftar User</h3>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-red-600 to-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-red-700 hover:to-rose-700 hover:shadow-lg flex-shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah User Baru
                    </a>
                </div>

                <div class="space-y-8">
                    @foreach ($roles as $role)
                        @if ($role->users->isNotEmpty())
                            <div>
                                <h4 class="text-md font-bold text-red-800 pb-2 border-b border-gray-200">{{ $role->name }}</h4>
                                <div class="mt-4 space-y-3">
                                    @foreach ($role->users as $user)
                                        {{-- ✅ PERBAIKAN: flex-col sm:flex-row dan gap-y-3 untuk mobile --}}
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-y-3 rounded-lg p-3 ring-1 {{ $user->trashed() ? 'bg-gray-200 ring-gray-300' : 'bg-gray-50 ring-gray-200/50' }}">
                                            <div class="flex items-center gap-3 {{ $user->trashed() ? 'opacity-50' : '' }}">
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                </div>
                                                @if($user->email_verified_at)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700"><span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Terverifikasi</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-700"><span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>Belum Verifikasi</span>
                                                @endif
                                            </div>
                                            
                                            @if($user->id !== auth()->id())
                                                {{-- ✅ PERBAIKAN: flex-shrink-0 agar tidak turun baris --}}
                                                <div class="flex items-center gap-2 flex-shrink-0">
                                                    @if($user->trashed())
                                                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-md bg-green-600 hover:bg-green-700 transition">Aktifkan</button>
                                                        </form>
                                                    @else
                                                        <button @click="openModal = true; selectedUser = {{ Js::from($user) }}" class="px-3 py-1 text-xs font-medium text-white rounded-md bg-blue-600 hover:bg-blue-700 transition">Ganti Password</button>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin MENONAKTIFKAN user ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-3 py-1 text-xs font-medium text-white rounded-md bg-red-600 hover:bg-red-700 transition">Nonaktifkan</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                             <span class="text-xs font-medium text-gray-400 flex-shrink-0">(Akun Anda)</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        </div>

        {{-- Modal Ganti Password (Sudah Responsif) --}}
        <div x-show="openModal" @keydown.escape.window="openModal = false" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="openModal" @click="openModal = false" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="openModal" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <template x-if="selectedUser">
                        <form :action="`/admin/users/${selectedUser.id}/password`" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Ganti Password</h3>
                                        <p class="text-sm text-gray-500 mt-1">Anda akan mengganti password untuk user <strong x-text="selectedUser.name"></strong>.</p>
                                        <div class="mt-6 space-y-4">
                                            <div>
                                                <x-input-label for="password" value="Password Baru" />
                                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                            <div>
                                                <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <x-primary-button class="bg-red-600 hover:bg-red-700">Simpan Password Baru</x-primary-button>
                                <button @click="openModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>