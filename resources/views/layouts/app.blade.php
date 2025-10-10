<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/logo-kab-bandung.png') }}" type="image/png">
    <title>{{ $title ?? config('app.name', 'Data Stunting — Pangalengan') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Main assets (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Per-page head injections (e.g., Leaflet CSS, meta tambahan) -->
    @stack('head')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        {{-- @include('layouts.navigation') --}}

        <!-- Page Heading (opsional) -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Per-page scripts (e.g., Chart.js, Leaflet JS, inisialisasi chart/map) -->
    @stack('scripts')
</body>
</html>
