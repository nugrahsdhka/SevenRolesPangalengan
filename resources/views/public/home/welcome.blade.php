<main class="w-full px-4 sm:px-6 lg:px-8 py-6">
    {{-- Grid Kartu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">

        {{-- 1. Kartu Data Stunting --}}
        <a href="{{ route('data-stunting') }}"
           class="group relative bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-0.5 flex flex-col">

            {{-- Decorative Element --}}
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-primary-50 rounded-full opacity-50 transition-transform group-hover:scale-150 duration-700">
            </div>

            <div class="p-8 relative z-10">
                <div class="flex items-start justify-between">
                    <div class="p-4 bg-primary-500 rounded-2xl shadow-lg shadow-primary-200 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-primary-500 bg-primary-50 px-3 py-1 rounded-full uppercase tracking-wider">
                        Statistik Vital
                    </span>
                </div>

                <div class="mt-6">
                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary-500 transition-colors">
                        Data Stunting
                    </h3>
                    <p class="text-gray-500 mt-2 leading-relaxed">
                        Analisis mendalam mengenai status gizi anak berdasarkan indikator standar WHO.
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-2xl p-4 transition-colors group-hover:bg-primary-50/50">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Kasus Stunting</p>
                        <p class="text-3xl font-black text-primary-500 mt-1">{{ number_format($total_stunting) }}</p>
                        <p class="text-xs text-gray-500 font-medium">Anak Terdata</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4 transition-colors group-hover:bg-primary-50/50">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Total Terukur</p>
                        <p class="text-3xl font-black text-gray-800 mt-1">{{ number_format($total_anak) }}</p>
                        <p class="text-xs text-gray-500 font-medium">Populasi Sampel</p>
                    </div>
                </div>
            </div>

            <div class="mt-auto px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between group-hover:bg-primary-500 transition-colors duration-500">
                <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">
                    Eksplorasi Data Lengkap
                </span>
                <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-all transform group-hover:translate-x-2"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>

        {{-- 2. Kartu Data Wilayah --}}
        <a href="{{ route('data-wilayah') }}"
           class="group relative bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-0.5 flex flex-col">

            <div class="p-8 relative z-10">
                <div class="flex items-start justify-between">
                    <div class="p-4 bg-primary-500 rounded-2xl shadow-lg shadow-primary-200 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-primary-500 bg-primary-50 px-3 py-1 rounded-full uppercase tracking-wider">
                        Rata-rata Z-Score
                    </span>
                </div>

                <div class="mt-6">
                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary-500 transition-colors">
                        Data Wilayah
                    </h3>
                    <p class="text-gray-500 mt-2 leading-relaxed">
                        Rangkuman performa kesehatan Kecamatan Pangalengan berdasarkan nilai Z-Score.
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="bg-gray-50 rounded-xl p-3 text-center transition-all group-hover:bg-white group-hover:shadow-md border border-transparent group-hover:border-primary-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">BB/U</p>
                        <p class="text-xl font-black text-gray-800">
                            {{ number_format($avgZScores->avg_bbu ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="bg-primary-500 rounded-xl p-3 text-center shadow-lg shadow-primary-200">
                        <p class="text-[10px] font-bold text-primary-100 uppercase">TB/U</p>
                        <p class="text-xl font-black text-white">
                            {{ number_format($avgZScores->avg_tbu ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center transition-all group-hover:bg-white group-hover:shadow-md border border-transparent group-hover:border-primary-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">BB/TB</p>
                        <p class="text-xl font-black text-gray-800">
                            {{ number_format($avgZScores->avg_bbtb ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-auto px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between group-hover:bg-primary-500 transition-colors duration-500">
                <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">
                    Lihat Detail Wilayah
                </span>
                <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-all transform group-hover:translate-x-2"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>

        {{-- 3. Kartu Analisis Hotspot --}}
        <a href="{{ route('analisis-hotspot') }}"
           class="group relative bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-0.5 flex flex-col">

            <div class="p-8 relative z-10">
                <div class="flex items-start justify-between">
                    <div class="p-4 bg-primary-500 rounded-2xl shadow-lg shadow-primary-200 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-primary-500 bg-primary-50 px-3 py-1 rounded-full uppercase tracking-wider">
                        Geo-Mapping
                    </span>
                </div>

                <div class="mt-6">
                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary-500 transition-colors">
                        Analisis Hotspot
                    </h3>
                    <p class="text-gray-500 mt-2 leading-relaxed">
                        Identifikasi wilayah prioritas yang membutuhkan intervensi kesehatan segera.
                    </p>
                </div>

                <div class="mt-8">
                    @if ($desaTerawan)
                        <div class="relative overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-5 shadow-inner">
                            <div class="relative z-10">
                                <p class="text-xs font-bold text-primary-200 uppercase tracking-widest">
                                    Desa Paling Rawan
                                </p>
                                <p class="text-2xl font-black text-white mt-1 truncate">
                                    {{ $desaTerawan->desa_kelurahan }}
                                </p>
                                <div class="flex items-center mt-2">
                                    <span class="flex h-2 w-2 rounded-full bg-white animate-ping mr-2"></span>
                                    <p class="text-sm font-medium text-primary-50">
                                        Rata-rata ZS: {{ number_format($desaTerawan->avg_zs_tbu, 2) }} SD
                                    </p>
                                </div>
                            </div>
                            <svg class="absolute right-[-10%] bottom-[-20%] w-32 h-32 text-primary-500 opacity-20"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 20l-1.45-1.32C3.4 13.99 0 10.92 0 7.17 0 4.1 2.42 1.67 5.5 1.67c1.74 0 3.41.81 4.5 2.09 1.09-1.28 2.76-2.09 4.5-2.09 3.08 0 5.5 2.43 5.5 5.5 0 3.75-3.4 6.82-8.55 11.51L10 20z" />
                            </svg>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200 text-center">
                            <p class="text-sm text-gray-500 italic">Data sebaran hotspot belum dikalkulasi.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-auto px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between group-hover:bg-primary-500 transition-colors duration-500">
                <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">
                    Buka Peta Sebaran
                </span>
                <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-all transform group-hover:translate-x-2"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>

        {{-- 4. Kartu Peta Faskes --}}
        <a href="{{ route('peta-faskes') }}"
           class="group relative bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-0.5 flex flex-col">

            <div class="p-8 relative z-10">
                <div class="flex items-start justify-between">
                    <div class="p-4 bg-primary-500 rounded-2xl shadow-lg shadow-primary-200 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="12" cy="10" r="3" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></circle>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-primary-500 bg-primary-50 px-3 py-1 rounded-full uppercase tracking-wider">
                        Akses Layanan
                    </span>
                </div>

                <div class="mt-6">
                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-primary-500 transition-colors">
                        Peta Faskes
                    </h3>
                    <p class="text-gray-500 mt-2 leading-relaxed">
                        Lokasi jaringan Puskesmas, dan Posyandu di wilayah Kecamatan Pangalengan.
                    </p>
                </div>

                <div class="mt-8 flex items-center bg-gray-50 rounded-2xl p-5 border border-gray-100 group-hover:bg-primary-50/30 transition-colors">
                    <div class="mr-4">
                        <p class="text-4xl font-black text-primary-500">{{ number_format($total_faskes) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 uppercase tracking-tight">Fasilitas Terdaftar</p>
                        <p class="text-xs text-gray-500">Siap melayani masyarakat</p>
                    </div>
                </div>
            </div>

            <div class="mt-auto px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between group-hover:bg-primary-500 transition-colors duration-500">
                <span class="text-sm font-bold text-gray-600 group-hover:text-white transition-colors">
                    Cari Fasilitas Terdekat
                </span>
                <svg class="w-5 h-5 text-primary-500 group-hover:text-white transition-all transform group-hover:translate-x-2"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </div>
        </a>

    </div>
    
    <x-chatbot />
</main>