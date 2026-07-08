<x-app-layout>
    <x-slot name="title">
        Rumah Stunting Pangalengan
    </x-slot>

    {{-- 1. HEADER (Bagian Atas Merah) --}}
    @include('public.home.header')

    {{-- 2. NAV PUBLIC (Sticky di antara header & konten) --}}
    @include('layouts.nav_public')

    {{-- 3. WELCOME / KONTEN UTAMA (Kartu-kartu) --}}
    @include('public.home.welcome')

</x-app-layout>