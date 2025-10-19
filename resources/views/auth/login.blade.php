<x-guest-layout>
    <x-slot name="title">
        Login
    </x-slot>

    <div class="w-screen h-screen bg-gradient-to-r from-red-600 via-red-800 to-red-600 flex justify-center items-center">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="rounded-2xl bg-white p-8 shadow-lg sm:p-10">
                {{-- Header --}}
                <div class="mb-8 text-center">
                    <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-red-600 shadow-md">
                        <svg viewBox="0 0 24 24" class="h-7 w-7 text-white" fill="currentColor">
                            <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm0 3.5a6.5 6.5 0 1 1 0 13 6.5 6.5 0 0 1 0-13Z"/>
                            <path d="M7.5 12a4.5 4.5 0 0 1 9 0h-9Z"/>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Login Admin</h1>
                    <p class="mt-1 text-sm text-gray-500">Sistem Informasi Stunting</p>
                    <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Akses Terbatas
                    </div>
                </div>
    
                {{-- Session Status --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />
    
                {{-- Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
    
                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700"/>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <x-text-input id="email"
                                class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-gray-900 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20"
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="admin@example.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
    
                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700"/>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <x-text-input id="password"
                                class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-gray-900 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20"
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
    
                    {{-- Submit --}}
                    <x-primary-button class="w-full justify-center rounded-lg bg-red-600 py-2.5 hover:bg-red-700 focus:ring-red-500">
                        {{ __('Log in') }}
                    </x-primary-button>
                </form>
    
                {{-- Back Link --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('welcome') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>