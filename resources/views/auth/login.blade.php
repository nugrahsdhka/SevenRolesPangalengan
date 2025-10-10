<x-guest-layout>
    <x-slot name="title">
        Login
    </x-slot>

    <div class="w-full max-w-md bg-white/10 backdrop-blur-xl rounded-2xl shadow-xl ring-1 ring-white/20 px-8 py-10">
        {{-- Logo & Judul --}}
        <div class="text-center mb-6">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20 mb-3">
                <svg viewBox="0 0 24 24" class="h-6 w-6 text-red-900" fill="currentColor">
                    <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2Zm0 3.5a6.5 6.5 0 1 1 0 13 6.5 6.5 0 0 1 0-13Z"/>
                    <path d="M7.5 12a4.5 4.5 0 0 1 9 0h-9Z"/>
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-red-900 mb-1">Sistem Informasi Stunting</h1>
            <p class="text-sm text-red-900/90">Kecamatan Pangalengan</p>
        </div>
    
        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-red-900" :status="session('status')" />
    
        <form method="POST" action="{{ route('login') }}">
            @csrf
    
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-red-900"/>
                <x-text-input id="email"
                    class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-900" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="text-red-900"/>
                <x-text-input id="password"
                    class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                    type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-900" />
            </div>
    
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-red-400 bg-white/20 text-red-600 shadow-sm focus:ring-red-500"
                           name="remember">
                    <span class="ms-2 text-sm text-red-900">{{ __('Remember me') }}</span>
                </label>
            </div>
    
            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-red-900 hover:text-red-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
    
                <x-primary-button class="ms-3 bg-red-600 hover:bg-red-700 focus:ring-red-500">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
