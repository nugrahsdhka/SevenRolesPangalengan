<x-guest-layout>
    <x-slot name="title">
        RSP | Peta Fasilitas Kesehatan
    </x-slot>

    @include('layouts.nav_public')


    {{-- Header --}}
    <div class="p-1">
        <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-3">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            Peta Fasilitas Kesehatan
                        </h1>
                        <p class="hidden md:block mt-1 text-sm text-gray-500">
                            Peta Fasilitas Kesehatan di Kecamatan Pangalengan
                        </p>
                        <p class="block md:hidden mt-1 text-sm text-gray-500">
                            Peta Faskes di Kecamatan Pangalengan
                        </p>
                    </div>

                    {{-- Tombol Modal --}}
                    <button id="openInfoModalBtn"
                        class="p-2.5 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                                hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Konten Peta --}}
    <section id="tab-map">
        <div class="bg-white rounded-lg overflow-hidden flex items-center justify-center">
            <div class="p-1 w-full">
                {{-- Map Container --}}
                <div id="map" class="rounded-lg w-full h-[73vh] md:h-[70vh]"></div>
            </div>
        </div>

        {{-- Modal Keterangan Peta --}}
        <div id="infoModalOverlay"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
            <div class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0"
                id="infoModalContent">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <p class="text-lg font-bold text-gray-800">Keterangan Peta Fasilitas Kesehatan</p>
                    <button id="closeInfoModalBtnHeader"
                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 space-y-4 text-sm text-gray-700 max-h-[70vh] overflow-y-auto">
                    <ul class="list-disc list-inside space-y-3 pl-4">
                        <li>
                            <strong>Batas Wilayah:</strong> Area hijau semi-transparan pada peta
                            merepresentasikan batas desa di Kecamatan Pangalengan.
                        </li>
                        <li>
                            <strong>Marker Fasilitas Kesehatan:</strong> Setiap ikon pin merah pada peta menunjukkan
                            lokasi suatu fasilitas kesehatan (faskes), seperti puskesmas, klinik, atau rumah sakit.
                        </li>
                        <li>
                            <strong>Interaksi:</strong>
                            <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                                <li>• Klik marker untuk melihat nama faskes, jenis, alamat, dan koordinat.</li>
                                <li>• Anda dapat melakukan zoom in/out dan drag untuk menjelajahi wilayah.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Akurasi Lokasi:</strong> Titik koordinat berasal dari data yang diinput pada sistem.
                            Jika terdapat perbedaan dengan lokasi sebenarnya di lapangan, silakan lakukan pembaruan
                            data.
                        </li>
                    </ul>

                    <p class="mt-3 text-xs text-gray-500">
                        <em>*Peta menggunakan basemap OpenStreetMap dan batas administrasi desa Kecamatan
                            Pangalengan.</em>
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right rounded-b-xl">
                    <button id="closeInfoModalBtnFooter"
                        class="px-5 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </section>

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

                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.esri.com/en-us/home" target="_blank" rel="noopener noreferrer">Esri</a>'
                    }
                ).addTo(map);

                // Data faskes dari controller
                const faskes = @json($faskes);

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

                // 2) Definisikan icon custom berbasis SVG (pin merah)
                const faskesIcon = L.divIcon({
                    className: 'faskes-marker-icon',
                    iconSize: [30, 40], // lebar, tinggi
                    iconAnchor: [15, 38], // titik yang nempel ke koordinat (tengah bawah)
                    popupAnchor: [0, -32],
                    html: `
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24"
                             width="30" height="40">
                            <!-- badan pin -->
                            <path
                                d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"
                                fill="#ef4444"/>
                            <!-- lingkaran tengah -->
                            <circle cx="12" cy="9" r="4" fill="#b91c1c"/>
                        </svg>
                    `
                });

                // 3) Tambahkan marker faskes dengan icon SVG di atas
                faskes.forEach(item => {
                    const {
                        lat,
                        lng,
                        nama_faskes,
                        jenis,
                        alamat
                    } = item;
                    if (!lat || !lng) return;

                    const marker = L.marker([lat, lng], {
                        icon: faskesIcon
                    }).addTo(map);

                    marker.bindPopup(`
                        <div style="min-width: 220px">
                            <strong>${nama_faskes || 'Fasilitas Kesehatan'}</strong><br>
                            Jenis: <strong>${jenis || '-'}</strong><br>
                            Alamat: ${alamat || '-'}<br>
                            Koordinat: ${lat}, ${lng}
                        </div>
                    `);
                });
            });
        </script>

        {{-- Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalOverlay = document.getElementById('infoModalOverlay');
                const modalContent = document.getElementById('infoModalContent');
                const openBtn = document.getElementById('openInfoModalBtn');
                const closeBtnHeader = document.getElementById('closeInfoModalBtnHeader');
                const closeBtnFooter = document.getElementById('closeInfoModalBtnFooter');

                function openModal() {
                    if (modalOverlay) {
                        modalOverlay.classList.remove('hidden');
                        void modalContent.offsetWidth; // trigger reflow
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }
                }

                function closeModal() {
                    if (modalOverlay) {
                        modalContent.classList.add('scale-95', 'opacity-0');
                        modalContent.classList.remove('scale-100', 'opacity-100');
                        setTimeout(() => {
                            modalOverlay.classList.add('hidden');
                        }, 300);
                    }
                }

                if (openBtn) openBtn.addEventListener('click', openModal);
                if (closeBtnHeader) closeBtnHeader.addEventListener('click', closeModal);
                if (closeBtnFooter) closeBtnFooter.addEventListener('click', closeModal);

                if (modalOverlay) {
                    modalOverlay.addEventListener('click', function(event) {
                        if (event.target === modalOverlay) {
                            closeModal();
                        }
                    });
                }

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            });
        </script>
    @endpush

</x-guest-layout>
