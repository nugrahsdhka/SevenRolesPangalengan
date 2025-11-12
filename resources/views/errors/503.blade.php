<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Maintenance</title>

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

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }

        .delay-4 {
            animation-delay: 0.8s;
        }

        .delay-5 {
            animation-delay: 1.0s;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center bg-white px-5 overflow-hidden">

        <div class="max-w-2xl w-full text-center">

            {{-- Logo (optional - uncomment jika ingin menampilkan logo) --}}
            <img src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung"
                class="animate-fade delay-1 max-w-[130px] h-auto mx-auto mb-6">

            {{-- Pre-title --}}
            <p class="animate-fade delay-1 text-sm font-semibold text-red-600 uppercase tracking-widest mb-2.5">
                Rumah Stunting Pangalengan
            </p>

            {{-- Main Title --}}
            <h1 class="animate-fade delay-2 text-5xl md:text-6xl font-bold text-gray-900 mt-0 mb-5">
                Situs Sedang Dalam Perbaikan
            </h1>

            {{-- Divider --}}
            <hr class="animate-fade delay-3 w-16 h-0.5 bg-red-600 border-0 mx-auto mb-6">

            {{-- Description --}}
            <p class="animate-fade delay-4 text-lg text-gray-600 mb-0 leading-relaxed">
                Kami sedang melakukan pembaruan untuk meningkatkan layanan.
                <br>
                Mohon maaf atas ketidaknyamanannya.
            </p>

        </div>
    </div>
</body>

</html>
