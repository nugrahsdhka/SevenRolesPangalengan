<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>403 Forbidden</title>
        <link rel="icon" href="{{ asset('img/logo-kab-bandung.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade {
                opacity: 0;
                animation: fadeInUp 0.3s ease-out forwards;
            }

            .delay-1 { animation-delay: 0.2s; }
            .delay-2 { animation-delay: 0.4s; }
            .delay-3 { animation-delay: 0.6s; }
            .delay-4 { animation-delay: 0.8s; }
            .delay-5 { animation-delay: 1.0s; }
            .delay-6 { animation-delay: 1.2s; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center bg-white px-5 overflow-hidden">
            
            <div class="max-w-2xl w-full text-center">
                
                {{-- Logo --}}
                <img src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung" class="animate-fade delay-1 max-w-[130px] h-auto mx-auto mb-6">
                
                {{-- Pre-title --}}
                <p class="animate-fade delay-2 text-sm font-semibold text-red-600 uppercase tracking-widest mb-2.5">
                    Rumah Stunting Pangalengan
                </p>

                {{-- Main Title --}}
                <h1 class="animate-fade delay-3 text-5xl md:text-6xl font-bold text-gray-900 mt-0 mb-5">
                    Akses Ditolak
                </h1>

                {{-- Divider --}}
                <hr class="animate-fade delay-4 w-16 h-0.5 bg-red-600 border-0 mx-auto mb-6">

                {{-- Description --}}
                <p class="animate-fade delay-5 text-lg text-gray-600 mb-8 leading-relaxed">
                    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
                    <br>
                    Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
                </p>

                {{-- Action Button --}}
                <div class="animate-fade delay-6">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center justify-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke Home
                    </a>
                </div>

            </div>
        </div>
    </body>
</html>