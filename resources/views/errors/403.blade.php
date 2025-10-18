<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Data Stunting — Pangalengan') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center relative bg-gradient-to-br from-red-600 via-red-500 to-rose-900 overflow-hidden">
            
            {{-- Gradient blur backgrounds --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-1/4 h-64 w-64 rounded-full bg-white blur-3xl"></div>
                <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-white blur-3xl"></div>
            </div>

            {{-- Geometric dots pattern --}}
            <div class="absolute inset-0 opacity-[0.08]">
                <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="dot-pattern" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1.5" fill="white"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dot-pattern)"/>
                </svg>
            </div>

            {{-- Geometric circles --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-40 -right-40 h-80 w-80 rounded-full border border-white/20"></div>
                <div class="absolute -top-32 -right-32 h-64 w-64 rounded-full border-2 border-white/10"></div>
                <div class="absolute top-1/2 -left-20 h-60 w-60 rounded-full border border-white/15"></div>
                <div class="absolute -bottom-32 -left-32 h-96 w-96 rounded-full border border-white/10"></div>
            </div>

            {{-- Geometric squares --}}
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute top-20 right-1/4 h-16 w-16 rotate-45 border border-white/20"></div>
                <div class="absolute bottom-32 left-1/3 h-12 w-12 rotate-12 border border-white/15"></div>
                <div class="absolute top-1/3 left-20 h-20 w-20 -rotate-12 border border-white/10"></div>
            </div>

            {{-- Geometric lines --}}
            <div class="pointer-events-none absolute inset-0">
                <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0" y1="20%" x2="30%" y2="0" stroke="white" stroke-opacity="0.1" stroke-width="1"/>
                    <line x1="70%" y1="100%" x2="100%" y2="80%" stroke="white" stroke-opacity="0.1" stroke-width="1"/>
                    <line x1="100%" y1="30%" x2="85%" y2="50%" stroke="white" stroke-opacity="0.08" stroke-width="1"/>
                </svg>
            </div>

            {{-- Content --}}
            <div class="relative z-10">
                <div class="max-w-md w-full text-center p-6">
                    <svg class="mx-auto h-20 w-20 text-slate-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                
                    <h1 class="mt-4 text-4xl font-extrabold text-slate-50 tracking-tight">403 | Akses Ditolak</h1>
                    <p class="mt-2 text-lg text-slate-50">
                        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                    </p>
                    <div class="mt-8 flex items-center justify-center space-x-4">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Kembali ke Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>