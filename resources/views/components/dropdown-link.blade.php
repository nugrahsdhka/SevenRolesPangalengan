@props(['active'])

@if ($active ?? false)
    {{-- TAMPILAN JIKA LINK SEDANG AKTIF --}}
    {{-- ✅ PERBAIKAN: Padding diubah dari px-6 menjadi pl-8 pr-6 --}}
    <a {{ $attributes->merge(['class' => 'w-full group relative inline-flex items-center py-2 pl-8 pr-6 pt-1 mt-1 text-md font-bold text-red-700 transition-all duration-300']) }}>
        
        <span class="relative z-10 whitespace-nowrap">{{ $slot }}</span>
        
        {{-- ✅ PERBAIKAN: Garis indikator sekarang ada di kiri --}}
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-red-600 to-rose-600 rounded-r-full"></div>
        
        {{-- ✅ PERBAIKAN: Latar belakang sekarang tidak membulat di atas --}}
        <div class="absolute inset-y-0 left-0 right-0 bg-red-50 opacity-50"></div>
    </a>
@else
    {{-- TAMPILAN JIKA LINK TIDAK AKTIF --}}
    {{-- ✅ PERBAIKAN: Padding diubah dari px-6 menjadi pl-8 pr-6 --}}
    <a {{ $attributes->merge(['class' => 'w-full group relative inline-flex items-center py-2 pl-8 pr-6 pt-1 mt-1 text-md font-semibold text-gray-600 hover:text-gray-900 transition-all duration-300']) }}>
        
        <span class="relative z-10 whitespace-nowrap">{{ $slot }}</span>
        
        {{-- ✅ PERBAIKAN: Garis indikator sekarang ada di kiri dan beranimasi secara vertikal --}}
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-red-600 to-rose-600 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-center rounded-r-full"></div>
    </a>
@endif