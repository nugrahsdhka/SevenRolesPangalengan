@props(['active'])

@if ($active ?? false)
    {{-- TAMPILAN JIKA LINK SEDANG AKTIF --}}
    <a
        {{ $attributes->merge(['class' => 'group relative inline-flex items-center px-5 py-5 text-sm font-semibold text-red-600 transition-all duration-300']) }}>
        <span class="relative z-10">{{ $slot }}</span>
        {{-- Garis bawah minimalis dengan animasi --}}
        <div class="absolute bottom-3 left-5 right-5 h-0.5 bg-red-600"></div>
    </a>
@else
    {{-- TAMPILAN JIKA LINK TIDAK AKTIF --}}
    <a
        {{ $attributes->merge(['class' => 'group relative inline-flex items-center px-5 py-5 text-sm font-medium text-gray-500 hover:text-gray-900 transition-all duration-300']) }}>
        <span class="relative z-10">{{ $slot }}</span>
        {{-- Garis bawah muncul saat hover dengan smooth animation --}}
        <div
            class="absolute bottom-3 left-5 right-5 h-0.5 bg-gradient-to-r from-red-600 to-rose-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 ease-out">
        </div>
        {{-- Subtle background hover effect --}}
        {{-- <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div> --}}
    </a>
@endif
