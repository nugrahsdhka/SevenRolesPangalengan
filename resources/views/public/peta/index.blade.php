<x-guest-layout>
    <x-slot name="title">
        RSP | Peta Fasilitas Kesehatan
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
                            <div class="flex flex-col justify-center items-center md:items-start md:justify-start">
                                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                                    Peta <span class="text-primary-500">Faskes</span>
                                </h1>
                                <p class="hidden md:block mt-1 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                                    Sebaran Fasilitas Kesehatan Kecamatan Pangalengan
                                </p>
                            </div>

                            {{-- Filter Section --}}
                            <div class="w-full lg:w-auto">
                                <div class="flex flex-col sm:flex-row items-center gap-4 bg-gray-50/80 backdrop-blur-md p-2 rounded-[2rem] border border-gray-100/80">
                                    
                                    {{-- Filter Checkboxes --}}
                                    <div class="flex items-center gap-4 px-4 py-2">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <div class="relative flex items-center">
                                                <input id="filterPuskesmas" type="checkbox" checked
                                                       class="w-5 h-5 text-primary-500 border-gray-300 rounded-lg focus:ring-primary-500 focus:ring-offset-0 transition-all cursor-pointer">
                                            </div>
                                            <span class="text-xs font-black text-gray-600 uppercase tracking-widest group-hover:text-primary-500 transition-colors">Puskesmas</span>
                                        </label>
                                        
                                        <div class="w-px h-4 bg-gray-300"></div>

                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <div class="relative flex items-center">
                                                <input id="filterPosyandu" type="checkbox" checked
                                                       class="w-5 h-5 text-primary-500 border-gray-300 rounded-lg focus:ring-primary-500 focus:ring-offset-0 transition-all cursor-pointer">
                                            </div>
                                            <span class="text-xs font-black text-gray-600 uppercase tracking-widest group-hover:text-primary-500 transition-colors">Posyandu</span>
                                        </label>
                                    </div>
                                </div>
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
            </div>
        </section>
    </div>


    @push('styles')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

        <style>
            /* Fix supaya map tidak menutupi navbar & modal */
            .leaflet-container {
                z-index: 0 !important;
            }

            /* Hindari Tailwind ngerusak style image Leaflet */
            .leaflet-container img {
                max-width: none !important;
            }

            #map {
                width: 100%;
                /* Tinggi diatur via Tailwind: h-[60vh] md:h-[600px] */
            }

            /* Hilangin outline kotak saat klik layer/marker */
            .leaflet-container .leaflet-interactive:focus,
            .leaflet-container .leaflet-interactive:focus-visible,
            .leaflet-container:focus,
            .leaflet-container:focus-visible {
                outline: none;
            }

            /* Icon marker faskes (divIcon) */
            .faskes-marker-icon {
                background: transparent;
                border: none;
            }

            .faskes-marker-icon svg {
                display: block;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        {{-- Leaflet --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const map = L.map('map');

                // Basemap: Esri World Imagery
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.esri.com/en-us/home" target="_blank" rel="noopener noreferrer">Esri</a>'
                    }
                ).addTo(map);

                // Data faskes dari controller (Laravel → JS)
                const faskes = @json($faskes);

                // Simpan semua marker untuk kebutuhan filter
                const markers = [];

                // Ambil elemen checkbox filter
                const filterPuskesmas = document.getElementById('filterPuskesmas');
                const filterPosyandu = document.getElementById('filterPosyandu');

                // 1) Tambahkan batas wilayah Kecamatan Pangalengan
                fetch('{{ asset('maps/PetaDesaKecPangalengan.geojson') }}')
                    .then(r => r.json())
                    .then(geojson => {
                        const batasLayer = L.geoJSON(geojson, {
                            style: {
                                color: '#fff',
                                weight: 0.25,
                                fillColor: '#16a34a', // hijau (faskes context)
                                fillOpacity: 0.15
                            }
                        }).addTo(map);

                        const bounds = batasLayer.getBounds();
                        map.fitBounds(bounds, {
                            padding: [20, 20]
                        });

                        const minZoom = 12;
                        if (map.getZoom() < minZoom) {
                            map.setZoom(minZoom);
                        }
                    })
                    .catch(err => {
                        console.error('Gagal memuat batas wilayah:', err);
                        map.setView([-7.16, 107.57], 11);
                    });

                // 2) Peta jenis faskes → icon SVG
                const iconMap = {
                    Posyandu: "{{ asset('img/icons/posyandu-pin.svg') }}",
                    Puskesmas: "{{ asset('img/icons/puskesmas-pin.svg') }}",
                    Klinik: "{{ asset('img/icons/klinik-pin.svg') }}",
                    'Rumah Sakit': "{{ asset('img/icons/rs-pin.svg') }}",
                    'Praktik Dokter': "{{ asset('img/icons/praktik-dokter-pin.svg') }}",
                    Lainnya: "{{ asset('img/icons/lainnya.svg') }}",
                };

                // 3) Generator icon berdasarkan jenis
                function getFaskesIcon(jenis) {
                    const url = iconMap[jenis] ?? "{{ asset('img/icons/default-pin.svg') }}";

                    // Jika Puskesmas → icon lebih besar
                    const size = (jenis === 'Puskesmas') ? [18, 28] : [14, 24];
                    const anchor = (jenis === 'Puskesmas') ? [11, 36] : [7, 24];
                    const popupYOffset = (jenis === 'Puskesmas') ? -28 : -20;

                    return L.icon({
                        iconUrl: url, // path icon SVG
                        iconSize: size, // [width, height] dalam px
                        iconAnchor: anchor, // titik bawah tengah pin
                        popupAnchor: [0, popupYOffset] // popup muncul sedikit di atas icon
                    });
                }

                // 4) Tambahkan marker faskes dengan icon sesuai jenis
                faskes.forEach(item => {
                    const {
                        lat,
                        lng,
                        nama_faskes,
                        jenis,
                        alamat
                    } = item;

                    // Skip jika koordinat tidak valid
                    if (!lat || !lng) return;

                    const j = jenis || 'Lainnya';

                    const marker = L.marker([lat, lng], {
                        icon: getFaskesIcon(j),
                        zIndexOffset: (j === 'Puskesmas') ? 1000 : 0
                    }).addTo(map);

                    marker.bindPopup(`
                        <div style="min-width: 220px">
                            <strong>${nama_faskes || 'Fasilitas Kesehatan'}</strong><br>
                            Jenis: <strong>${j || '-'}</strong><br>
                            Alamat: ${alamat || '-'}<br>
                            Koordinat: ${lat}, ${lng}
                        </div>
                    `);

                    // Simpan ke array markers
                    markers.push({
                        marker,
                        jenis: j
                    });
                });

                // 5) Fungsi apply filter berdasarkan checkbox
                function applyFaskesFilter() {
                    if (!filterPuskesmas || !filterPosyandu) return;

                    // Pastikan minimal satu checkbox terpilih
                    if (!filterPuskesmas.checked && !filterPosyandu.checked) {
                        filterPuskesmas.checked = true; // Paksa centang Puskesmas jika kosong
                    }

                    const showPuskesmas = filterPuskesmas.checked;
                    const showPosyandu = filterPosyandu.checked;

                    markers.forEach(item => {
                        const jenisLower = (item.jenis || '').toLowerCase();
                        let shouldShow = false;

                        // Logika: Hanya tampilkan jika jenisnya sesuai dengan checkbox yang dicentang
                        if (jenisLower === 'puskesmas') {
                            shouldShow = showPuskesmas;
                        } else if (jenisLower === 'posyandu') {
                            shouldShow = showPosyandu;
                        } else {
                            // Jenis lain (Klinik, RS, dll) akan disembunyikan jika filter aktif
                            shouldShow = false; 
                        }

                        // Toggle layer di peta
                        if (shouldShow && !map.hasLayer(item.marker)) {
                            item.marker.addTo(map);
                        } else if (!shouldShow && map.hasLayer(item.marker)) {
                            map.removeLayer(item.marker);
                        }
                    });
                }

                // 6) Event listener untuk checkbox dengan logic auto-toggle
                if (filterPuskesmas && filterPosyandu) {
                    const handleFilterChange = (e) => {
                        // Cek apakah keduanya jadi tidak dicentang
                        if (!filterPuskesmas.checked && !filterPosyandu.checked) {
                            // Jika checkbox yang baru saja di-klik adalah Puskesmas, 
                            // maka paksakan Posyandu yang aktif (atau sebaliknya)
                            if (e.target === filterPuskesmas) {
                                filterPosyandu.checked = true;
                            } else {
                                filterPuskesmas.checked = true;
                            }
                        }
                        
                        // Jalankan filter setelah pengecekan
                        applyFaskesFilter();
                    };

                    filterPuskesmas.addEventListener('change', handleFilterChange);
                    filterPosyandu.addEventListener('change', handleFilterChange);
                }
            });
        </script>
    @endpush
</x-guest-layout>