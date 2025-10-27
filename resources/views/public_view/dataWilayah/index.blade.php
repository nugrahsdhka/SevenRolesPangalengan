<x-guest-layout>
    <x-slot name="title">
        Data Wilayah Publik
    </x-slot>

    {{-- Menyertakan navigasi publik Anda --}}
    @include('layouts.nav_public')

    {{-- Konten Halaman --}}
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-4">Data Wilayah Terpublikasi</h1>
            <p class="text-sm text-gray-600 mb-6">Data berikut merupakan data agregat per desa yang telah disetujui dan dipublikasi oleh admin.</p>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Desa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Anak</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasus Stunting</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-Rata ZS TB/U</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faskes Terdekat (Dummy)</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($dataWilayah as $data)
                            @php
                                // Hitung rata-rata ZS TB/U
                                $avgZsTbU = ($data->total_anak > 0 && !is_null($data->sum_zs_tbu)) ? ($data->sum_zs_tbu / $data->total_anak) : null;
                                // Data Faskes Dummy
                                $faskes = ['Puskesmas ' . $data->desa_kelurahan, 'RSUD Terdekat', 'Klinik Sehat'];
                                $dummyFaskes = $faskes[array_rand($faskes)];
                            @endphp
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $data->desa_kelurahan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $data->total_anak }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $data->total_kasus_stunting > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $data->total_kasus_stunting }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ !is_null($avgZsTbU) && $avgZsTbU < -2 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ !is_null($avgZsTbU) ? number_format($avgZsTbU, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $dummyFaskes }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    {{-- Link ke Google Maps berdasarkan nama Faskes dan Desa --}}
                                    <a href="https://www.google.com/maps/search/{{ urlencode($dummyFaskes . ' ' . $data->desa_kelurahan) }}" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-900 hover:underline">
                                        Lihat Lokasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Belum ada data wilayah yang dipublikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Paginasi -->
            <div class="mt-6">
                {{ $dataWilayah->links() }}
            </div>
        </div>
    </div>

</x-guest-layout>

