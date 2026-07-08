    <x-slot name="header">
    <div class="flex justify-between items-center gap-4" x-data="{ openSidebar: false }">

        {{-- ================= KIRI: HAMBURGER + JUDUL ================= --}}
        <div class="flex items-center gap-3 min-w-0 flex-1">
            {{-- Tombol Toggle Sidebar --}}
            <button
                @click="openSidebar = !openSidebar"
                class="flex items-center justify-center h-10 w-10 rounded-lg 
                       bg-gradient-to-br from-red-500 to-red-600 text-white
                       hover:from-red-600 hover:to-red-700
                       focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2
                       transition-all hover:scale-105 shadow-md hover:shadow-lg flex-shrink-0"
                type="button"
                title="Menu Admin"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 transition-transform duration-300"
                    :class="openSidebar ? 'rotate-90' : ''"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Judul Halaman Admin --}}
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight whitespace-nowrap">
                {{ __('Dashboard Admin') }}
            </h2>
        </div>

        {{-- ================= KANAN: DROPDOWN USER ================= --}}
        <div class="relative" x-data="{ open: false }">
            {{-- Tombol Trigger Dropdown (Avatar Bulat) --}}
            <button
                @click="open = !open"
                @click.away="open = false"
                class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white 
                       font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 
                       focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                type="button"
                aria-expanded="false"
                aria-haspopup="true"
                title="{{ auth()->user()->name }}"
            >
                {{-- Inisial Nama User --}}
                @php
                    $words = explode(' ', trim(auth()->user()->name));
                    $initials = '';
                    foreach ($words as $word) {
                        if (!empty($word)) {
                            $initials .= strtoupper(substr($word, 0, 1));
                        }
                    }
                    if (strlen($initials) > 2) {
                        $initials = substr($initials, 0, 1) . substr($initials, -1);
                    }
                @endphp
                {{ $initials }}
            </button>

            {{-- Dropdown Menu --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @keydown.escape.window="open = false"
                class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                style="display: none;"
            >
                <div class="py-1">
                    {{-- Info User Header --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    {{-- Menu Profil --}}
                    <a href="{{ route('profile.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/>
                        </svg>
                        <span class="font-medium">Profil Saya</span>
                    </a>

                    {{-- Divider --}}
                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Kelola User --}}
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                        </svg>
                        <span class="font-medium">Kelola User</span>
                    </a>

                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Kelola Faskes --}}
                    <a href="{{ route('admin.faskes.index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M19 3H5c-1.1 0-1.99.9-1.99 2L3 19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/>
                        </svg>
                        <span class="font-medium">Kelola Faskes</span>
                    </a>

                    {{-- Divider --}}
                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Home --}}
                    <a href="{{ route('index') }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5 flex-shrink-0"
                             xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 20 20"
                             fill="currentColor">
                            <path
                                d="M10.707 2.293a1 1 0 0 0-1.414 0l-7 7A1 1 0 0 0 3 11h1v6a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-6h1a1 1 0 0 0 .707-1.707l-7-7Z" />
                        </svg>
                        <span class="font-medium">Home</span>
                    </a>

                    {{-- Divider --}}
                    <div class="border-t border-gray-100 my-1"></div>

                    {{-- Log Out --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="h-5 w-5 flex-shrink-0"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0-4-4m4 4H9m4 4v1a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v1" />
                            </svg>
                            <span class="font-medium">Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ================= BACKDROP SIDEBAR ================= --}}
        <div
            x-show="openSidebar"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-gray-900/40 backdrop-blur-sm"
            style="display: none;"
            @click="openSidebar = false"
        ></div>

        {{-- ================= SIDEBAR ADMIN (PUBLISHED) ================= --}}
        <aside
            x-show="openSidebar"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0"
            style="display: none;"
            class="fixed left-0 top-0 bottom-0 w-80 max-w-full z-50
                   bg-white/95 backdrop-blur-xl border-r border-gray-200
                   shadow-[0_10px_40px_rgba(15,23,42,0.35)] transform"
        >
            <div class="p-6 space-y-6">
                {{-- Header Sidebar --}}
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl
                                       bg-gradient-to-br from-red-500 to-red-600 shadow-lg shadow-red-500/40">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414A1 1 0 0 1 19 9v8a2 2 0 0 1-2 2Z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Menu Admin</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Kelola data yang akan dipublikasikan</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Close --}}
                    <button
                        @click="openSidebar = false"
                        class="p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @php
                    // aktif tab untuk data terpublikasi (gizi, pencegahan, penanggulangan, desa)
                    $activeTab = $activeTab ?? 'gizi';

                    // Tab yang DISABLED (dikunci)
                    $disabledTabs = ['pencegahan', 'penanggulangan', 'desa'];
                @endphp

                {{-- NAVIGASI MENU ADMIN (PUBLISHED) --}}
                <nav class="space-y-2">

                    {{-- Data Gizi (AKTIF & BISA DIKLIK) --}}
                    <a href="{{ route('admin.published.index', ['active_tab' => 'gizi']) }}"
                       class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                              transition-all duration-200 overflow-hidden
                              {{ $activeTab === 'gizi'
                                  ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/50'
                                  : 'bg-white/90 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-300 hover:shadow-md' }}">
                        <span
                            class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                   transition-all duration-200
                                   {{ $activeTab === 'gizi'
                                       ? 'bg-white/20 text-white'
                                       : 'bg-red-100 text-red-600' }}">
                            <svg class="w-5 h-5"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <span class="relative">
                            Data Gizi
                        </span>
                        @if ($activeTab === 'gizi')
                            <span class="relative ml-auto">
                                <span class="flex h-2 w-2 rounded-full bg-white"></span>
                            </span>
                        @endif
                    </a>

                    {{-- MENU LAIN (DISABLED / LOCKED) --}}
                    @foreach ([
                        'pencegahan'     => 'Data Pencegahan',
                        'penanggulangan' => 'Data Penanggulangan',
                        'desa'           => 'Data Desa',
                    ] as $key => $label)
                        @php
                            $isDisabled = in_array($key, $disabledTabs, true);
                        @endphp

                        <a
                            class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                                   transition-all duration-200 overflow-hidden border border-gray-200
                                   {{ $isDisabled
                                       ? 'bg-gray-100 text-gray-400 cursor-default pointer-events-none hover:bg-gray-100 hover:text-gray-400'
                                       : ($activeTab === $key
                                           ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/50'
                                           : 'bg-white/90 text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-300 hover:shadow-md') }}">
                            
                            {{-- ICON --}}
                            <span
                                class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                       transition-all duration-200
                                       {{ $isDisabled
                                           ? 'bg-gray-300 text-gray-500'
                                           : ($activeTab === $key ? 'bg-white/20 text-white' : 'bg-red-100 text-red-600') }}">
                                
                                {{-- Gembok kalau disabled --}}
                                @if ($isDisabled)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 11c-1.105 0-2 .895-2 2v3h4v-3c0-1.105-.895-2-2-2zm-4 2V9a4 4 0 118 0v4m-6 4h4a2 2 0 002-2v-2H6v2a2 2 0 002 2z" />
                                    </svg>
                                @else
                                    @if ($key === 'pencegahan')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 8c-1.657 0-3 .895-3 2v6h6v-6c0-1.105-1.343-2-3-2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 10a7 7 0 0114 0v6a3 3 0 01-3 3H8a3 3 0 01-3-3v-6z" />
                                        </svg>
                                    @elseif ($key === 'penanggulangan')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 1010 10A10.011 10.011 0 0012 2z" />
                                        </svg>
                                    @elseif ($key === 'desa')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M3 12l9-9 9 9M4 10v10h5v-6h6v6h5V10" />
                                        </svg>
                                    @endif
                                @endif
                            </span>

                            {{-- LABEL + Subtext if disabled --}}
                            <div class="flex flex-col">
                                <span class="{{ $isDisabled ? 'text-gray-500' : '' }}">
                                    {{ $label }}
                                </span>

                                @if ($isDisabled)
                                    <span class="text-[10px] text-gray-400 mt-0.5 italic">
                                        Dalam proses pengembangan
                                    </span>
                                @endif
                            </div>

                            {{-- Indicator aktif (kalau nanti diaktifkan) --}}
                            @if (! $isDisabled && $activeTab === $key)
                                <span class="relative ml-auto">
                                    <span class="flex h-2 w-2 rounded-full bg-white"></span>
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
                {{-- Footer Info --}}
                <div class="mt-4 p-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border border-gray-300">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-500 text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700 mb-1">Tips</p>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Periksa kembali data yang di published!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</x-slot>
