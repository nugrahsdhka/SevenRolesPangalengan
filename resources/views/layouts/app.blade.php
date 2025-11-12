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
    <x-confirm-modal />

    <x-loading
    img="{{ asset('img/logo-kab-bandung.png') }}"
    ring="true"
    mode="dual"
    size="70"
    logo="40"
    ringThickness="3"
    ringColor="#8B0000"
    ringTrail="#E5E7EB"
    speed="1.0s"
    />

    <div class="min-h-screen bg-slate-50">
        {{-- @include('layouts.navigation') --}}

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

    </div>

    {{-- Go To Top button --}}
    <x-goToTop />

    @stack('scripts')
</body>
</html>
