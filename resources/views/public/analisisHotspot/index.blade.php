<x-guest-layout>
    <x-slot name="title">
        RSP | Analisis Hotspot
    </x-slot>

    @include('layouts.nav_public')

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section id="tab-map" class="max-w-7xl mx-auto px-2">

            {{-- ========== HEADER: TITLE + FILTER ========== --}}
            <div class="py-2">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">

                            {{-- Title Section --}}
                            <div class="flex flex-col md:flex-row items-center gap-4 text-center md:text-left">
                                <div>
                                    <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                                        Analisis <span class="text-primary-500">Hotspot</span>
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
                                                    class="w-full px-4 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all cursor-pointer appearance-none hover:bg-white">
                                                @forelse ($availablePeriods as $p)
                                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                                    <option value="{{ $ym }}">
                                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                                    </option>
                                                @empty
                                                    <option value="{{ now()->format('Y-m') }}">{{ now()->isoFormat('MMMM Y') }}</option>
                                                @endforelse
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary-500 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex gap-2 w-full sm:w-auto">
                                        <button type="button" id="applyPeriodeBtn"
                                                class="flex-1 sm:flex-none px-8 py-3 bg-primary-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-600 hover:shadow-lg hover:shadow-primary-200/50 active:scale-95 transition-all flex items-center justify-center gap-2">
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
            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-xl border border-white/50 overflow-hidden relative">
                <div class="p-2">
                    <div id="map" class="rounded-[2rem] w-full h-[65vh] md:h-[80vh] z-10"></div>
                </div>

                {{-- Legend Overlay (Desktop) --}}
                <div class="absolute bottom-8 right-8 z-[20] hidden md:block">
                    <div class="bg-white/30 backdrop-blur-xl p-5 rounded-3xl shadow-2xl border border-white/40">
                        <p class="text-[9px] font-black text-white uppercase tracking-widest mb-3 text-center border-b border-white/30 pb-2">Legenda ZS TB/U</p>
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-blue-500/80 shadow-sm ring-2 ring-white/50"></span>
                                <span class="text-[10px] font-bold text-white uppercase">Tinggi (> 2)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-green-500/80 shadow-sm ring-2 ring-white/50"></span>
                                <span class="text-[10px] font-bold text-white uppercase">Normal (-2 s/d 2)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-orange-500/80 shadow-sm ring-2 ring-white/50"></span>
                                <span class="text-[10px] font-bold text-white uppercase">Pendek (-3 s/d < -2)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full bg-primary-500/80 shadow-sm ring-2 ring-white/50"></span>
                                <span class="text-[10px] font-bold text-white uppercase">Sgt Pendek (< -3)</span>
                            </div>
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
            .glass-popup-container .leaflet-popup-content-wrapper {
                background: transparent !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
            }

            .glass-popup-container .leaflet-popup-content {
                margin: 0 !important;
                width: auto !important;
            }

            .glass-popup-container .leaflet-popup-tip {
                background: rgba(255,255,255,0.15) !important;
                backdrop-filter: blur(12px) !important;
                border: 1px solid rgba(255,255,255,0.2) !important;
                width: 10px !important;
                height: 10px !important;
            }

            .leaflet-popup { animation: popupIn 0.25s ease; }

            @keyframes popupIn {
                from { opacity: 0; transform: translateY(8px); }
                to { opacity: 1; transform: translateY(0); }
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
                        maxZoom: 20,
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
                            if (zs === null || zs === undefined || isNaN(zs)) return 'rgba(148, 163, 184, 0.25)';  // slate-400
                            if (zs < -3) return 'rgba(239, 68, 68, 0.30)';   // primary-500
                            if (zs < -2) return 'rgba(249, 115, 22, 0.30)';  // orange-500
                            if (zs <= 2) return 'rgba(16, 185, 129, 0.30)';  // emerald-500
                            return 'rgba(14, 165, 233, 0.30)';                // sky-500
                        }

                        geoLayer = L.geoJSON(geojson, {
                            style: feature => {
                                const rawName = feature.properties.NAME_4?.trim().toUpperCase() ?? '';
                                const item = stats[rawName] ?? null;
                                const zs = item ? item.avg_zs_tbu : null;

                                return {
                                    color: 'rgba(255, 255, 255, 0.21)',      // Border putih transparan
                                    weight: 1.5,
                                    fillColor: getColor(zs),                  // Warna transparan
                                    fillOpacity: 1,                           // Opacity 1 karena sudah alpha di warna
                                    opacity: 0.8,
                                    className: 'desa-polygon'                 // Class untuk CSS filter
                                };
                            },
                            onEachFeature: (feature, layer) => {
                                const rawName = feature.properties.NAME_4?.trim().toUpperCase() ?? '';
                                const item = stats[rawName] ?? null;

                                const getColor = (zs) => {
                                    if (!zs && zs !== 0) return '#94a3b8';
                                    if (zs < -3) return '#fb7185';
                                    if (zs < -2) return '#fb923c';
                                    if (zs <= 2) return '#34d399';
                                    return '#38bdf8';
                                };

                                const c = item ? getColor(item.avg_zs_tbu) : '#94a3b8';

                                const html = item ? `
                                    <div style="background:rgba(255,255,255,0.12);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.2);border-radius:16px;min-width:220px;font-family:system-ui,sans-serif;box-shadow:0 8px 24px rgba(0,0,0,0.3)">
                                        <div style="padding:10px 14px;border-bottom:1px solid rgba(255,255,255,0.1)">
                                            <h3 style="color:#fff;font-weight:900;font-size:13px;margin:0">${rawName}</h3>
                                        </div>
                                        <div style="padding:10px 14px;display:flex;flex-direction:column;gap:6px">
                                            <div style="display:flex;justify-content:space-between;align-items:center">
                                                <span style="color:rgba(255,255,255,0.5);font-size:11px">Status</span>
                                                <span style="background:${c}22;color:${c};padding:2px 8px;border-radius:999px;font-size:10px;font-weight:900">${item.kategori}</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;align-items:center">
                                                <span style="color:rgba(255,255,255,0.5);font-size:11px">ZS TB/U</span>
                                                <span style="color:${c};font-weight:900;font-size:12px">${item.avg_zs_tbu}</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;align-items:center">
                                                <span style="color:rgba(255,255,255,0.5);font-size:11px">Stunting</span>
                                                <span style="color:#fb7185;font-weight:900;font-size:12px">${item.total_kasus_stunting}</span>
                                            </div>
                                            <div style="display:flex;justify-content:space-between;align-items:center">
                                                <span style="color:rgba(255,255,255,0.5);font-size:11px">Anak</span>
                                                <span style="color:#fff;font-weight:900;font-size:12px">${item.total_anak}</span>
                                            </div>
                                        </div>
                                    </div>
                                ` : `
                                    <div style="background:rgba(255,255,255,0.12);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.2);border-radius:16px;padding:16px;text-align:center;min-width:180px">
                                        <p style="color:rgba(255,255,255,0.5);font-size:12px;font-weight:600;margin:0">Data belum tersedia</p>
                                    </div>
                                `;

                                layer.bindPopup(html, {
                                    maxWidth: 260,
                                    className: 'glass-popup-container',
                                    closeButton: false,
                                    offset: [0, -8]
                                });
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