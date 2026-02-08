<x-guest-layout>
    <x-slot name="title">
        RSP | Analisis Hotspot
    </x-slot>

    @include('layouts.nav_public')

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section id="tab-map" class="max-w-7xl mx-auto px-2">
            
            {{-- ========== HEADER: TITLE + FILTER ========== --}}
            <div class="py-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                            
                            {{-- Title Section --}}
                            <div class="flex flex-col md:flex-row items-center gap-4 text-center md:text-left">
                                <div>
                                    <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                                        Analisis <span class="text-red-600">Hotspot</span>
                                    </h1>
                                    <p class="hidden md:block mt-1 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                                        Peta Sebaran Stunting Kecamatan Pangalengan
                                    </p>
                                </div>
                            </div>

                            {{-- Filter & Action Section --}}
                            <div class="w-full lg:w-auto">
                                <form id="periodeForm" class="flex flex-col sm:flex-row items-end gap-4">
                                    
                                    {{-- Periode Selector --}}
                                    <div class="w-full sm:w-64">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                                            Pilih Periode Data
                                        </label>
                                        <div class="relative group">
                                            <select id="periodeInput" 
                                                    class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all cursor-pointer appearance-none">
                                                @forelse ($availablePeriods as $p)
                                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                                    <option value="{{ $ym }}">
                                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                                    </option>
                                                @empty
                                                    <option value="{{ now()->format('Y-m') }}">{{ now()->isoFormat('MMMM Y') }}</option>
                                                @endforelse
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex gap-2 w-full sm:w-auto">
                                        <button type="button" id="applyPeriodeBtn" 
                                                class="flex-1 sm:flex-none px-8 py-3 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                            Tampilkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== MAP CONTAINER ========== --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="p-2">
                    <div id="map" class="rounded-[2rem] w-full h-[65vh] md:h-[80vh] z-10"></div>
                </div>
                
                {{-- Legend Overlay (Desktop) --}}
                <div class="absolute bottom-8 right-8 z-[20] hidden md:block">
                    <div class="bg-white/90 backdrop-blur-md p-4 rounded-3xl shadow-2xl border border-white/20">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3 text-center border-b pb-2">Legenda ZS TB/U</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-blue-600 shadow-sm"></span> <span class="text-[10px] font-bold text-gray-600 uppercase">Tinggi (> 2)</span></div>
                            <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-green-600 shadow-sm"></span> <span class="text-[10px] font-bold text-gray-600 uppercase">Normal (-2 s/d 2)</span></div>
                            <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-orange-500 shadow-sm"></span> <span class="text-[10px] font-bold text-gray-600 uppercase">Pendek (-3 s/d < -2)</span></div>
                            <div class="flex items-center gap-3"><span class="w-3 h-3 rounded-full bg-red-600 shadow-sm"></span> <span class="text-[10px] font-bold text-gray-600 uppercase">Sgt Pendek (< -3)</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('styles')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    
        <style>
            /* Fix untuk navbar & modal */
            .leaflet-container {
                z-index: 0 !important;
            }
    
            /* Hindari Tailwind ngerusak style image Leaflet */
            .leaflet-container img {
                max-width: none !important;
            }
    
            #map {
                width: 100%;
                /* tinggi diatur via Tailwind: h-[60vh] md:h-[600px] */
            }
    
            /* Popup style */
            .leaflet-popup-content-wrapper {
                border-radius: 16px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04);
                border: 1px solid #e5e7eb;
            }
    
            .leaflet-popup-content {
                margin: 16px;
                font-family: system-ui, -apple-system, sans-serif;
            }
    
            .leaflet-popup-tip {
                box-shadow: 0 3px 14px rgba(0, 0, 0, 0.1);
            }
    
            /* Hover efek batas desa */
            .leaflet-interactive {
                transition: all 0.2s ease;
            }
    
            .leaflet-interactive:hover {
                filter: brightness(1.1);
                stroke-width: 2;
            }
    
            /* Tooltip nama desa */
            .desa-tooltip {
                background: rgba(31, 41, 55, 0.9);
                /* gray-800 */
                color: #fff;
                border-radius: 9999px;
                padding: 4px 10px;
                border: none;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                font-size: 0.75rem;
                /* text-xs */
                font-weight: 600;
                /* semibold */
                pointer-events: none;
                /* biar gak ganggu klik */
            }
    
            .desa-tooltip::before {
                display: none;
            }
    
            /* Hilangin outline kotak saat diklik */
            .leaflet-container .leaflet-interactive:focus,
            .leaflet-container .leaflet-interactive:focus-visible {
                outline: none;
            }
    
            .leaflet-container:focus,
            .leaflet-container:focus-visible {
                outline: none;
            }
        </style>
    @endpush
    
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
        {{-- Leaflet --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
    
                let map = L.map('map');
                let geoLayer = null;
    
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    {
                        maxZoom: 19,
                        attribution: '&copy; Esri'
                    }
                ).addTo(map);
    
                const periodeInput = document.getElementById('periodeInput');
                const applyBtn = document.getElementById('applyPeriodeBtn');
    
                function loadHotspotData() {
                    const period = periodeInput.value;
    
                    Promise.all([
                        fetch(`{{ route('api.hotspot') }}?period=${period}`).then(r => r.json()),
                        fetch('{{ asset('maps/PetaDesaKecPangalengan.geojson') }}').then(r => r.json())
                    ])
                    .then(([stats, geojson]) => {
                        if (geoLayer) {
                            map.removeLayer(geoLayer);
                        }
    
                        function getColor(zs) {
                            if (zs === null || zs === undefined || isNaN(zs)) return '#9E9E9E';
                            if (zs < -3) return '#D32F2F';
                            if (zs < -2) return '#F57C00';
                            if (zs <= 2) return '#388E3C';
                            return '#1976D2';
                        }
    
                        geoLayer = L.geoJSON(geojson, {
                            style: feature => {
                                const rawName = feature.properties.NAME_4?.trim().toUpperCase() ?? '';
                                const item = stats[rawName] ?? null;
                                const zs = item ? item.avg_zs_tbu : null;
    
                                return {
                                    color: '#FFF',
                                    weight: 0.4,
                                    fillColor: getColor(zs),
                                    fillOpacity: 0.45
                                };
                            },
                            onEachFeature: (feature, layer) => {
                                const rawName = feature.properties.NAME_4?.trim().toUpperCase() ?? '';
                                const item = stats[rawName] ?? null;
    
                                let html = `
                                    <div class="space-y-3">
                                        <div class="border-b border-gray-200 pb-2">
                                            <h3 class="text-lg font-bold text-gray-900">${rawName}</h3>
                                        </div>
                                `;
    
                                if (item) {
                                    html += `
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Status Desa:</span>
                                                <span class="font-semibold bg-blue-50 px-3 py-1 rounded-full text-xs">${item.kategori}</span>
                                            </div>
    
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Rata-rata ZS TB/U:</span>
                                                <span class="font-bold text-sm" style="color:${getColor(item.avg_zs_tbu)}">
                                                    ${item.avg_zs_tbu}
                                                </span>
                                            </div>
    
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Kasus Stunting:</span>
                                                <span class="text-red-600 font-semibold text-sm">${item.total_kasus_stunting}</span>
                                            </div>
    
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Total Anak:</span>
                                                <span class="text-gray-900 font-semibold text-sm">${item.total_anak}</span>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    html += `
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-sm text-gray-600 text-center">
                                                Data belum tersedia
                                            </p>
                                        </div>
                                    `;
                                }
    
                                html += `</div>`;
                                layer.bindPopup(html);
                            }
                        }).addTo(map);
    
                        const bounds = geoLayer.getBounds();
                        map.fitBounds(bounds, { padding: [20, 20] });
    
                        const minZoom = 12;
                        if (map.getZoom() < minZoom) map.setZoom(minZoom);
    
                    }).catch(err => console.error(err));
                }
    
                // Load awal
                loadHotspotData();
    
                // Jika user klik tombol "Tampilkan"
                applyBtn.addEventListener('click', loadHotspotData);
            });
        </script>
    @endpush
    
</x-guest-layout>
