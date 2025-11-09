<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Data Stunting — Pangalengan') }}</title>
        <link rel="icon" href="{{ asset('img/logo-kab-bandung.png') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @stack('styles')

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- Content --}}
        <div class="relative z-10">
            {{ $slot }}
        </div>

        @php
            // Route yang tidak perlu footer
            $hideFooterRoutes = [
                'login',
                'register.complete.show',
                'verification.token.show',
            ];
        @endphp

        @if (!in_array(Route::currentRouteName(), $hideFooterRoutes))
            <footer class="text-xs text-gray-500 text-center mb-4">
                &copy; {{ date('Y') }} Rumah Stunting Pangalengan. All rights reserved.
            </footer>
        @endif

        <x-goToTop />
        @stack('scripts')   
    </body>
</html>