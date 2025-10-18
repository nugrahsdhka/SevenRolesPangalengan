@props(['active'])

@if ($active ?? false)
    {{-- TAMPILAN JIKA LINK SEDANG AKTIF --}}
    <a {{ $attributes->merge(['class' => 'group relative inline-flex items-center px-6 pt-1 text-sm font-bold text-red-700 transition-all duration-300']) }}>
        <span class="relative z-10">{{ $slot }}</span>
        {{-- Garis bawah yang selalu terlihat --}}
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-rose-600 rounded-full"></div>
        {{-- Latar belakang redup --}}
        <div class="absolute inset-0 bg-red-50 rounded-t-xl opacity-50"></div>
    </a>
@else
    {{-- TAMPILAN JIKA LINK TIDAK AKTIF --}}
    <a {{ $attributes->merge(['class' => 'group relative inline-flex items-center px-6 pt-1 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-all duration-300']) }}>
        <span class="relative z-10">{{ $slot }}</span>
        {{-- Garis bawah yang hanya muncul saat hover --}}
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-rose-600 rounded-full scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-center"></div>
    </a>
@endif