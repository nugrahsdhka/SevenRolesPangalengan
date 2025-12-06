<x-app-layout>
    <x-slot name="title">
        Proses Import Data Gizi
    </x-slot>

    <div class="max-w-xl mx-auto mt-10 bg-white rounded-xl shadow p-6 space-y-4">
        <h1 class="text-lg font-semibold text-gray-800">
            Import Data Gizi Sedang Diproses
        </h1>

        <p class="text-sm text-gray-600">
            ID Import:
            <span class="font-mono">{{ $job->id }}</span><br>
            Periode:
            <span class="font-mono">{{ $job->period }}</span><br>
            Total baris (perkiraan):
            <span class="font-mono">{{ $job->total_rows }}</span>
        </p>

        <div class="mt-4">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
                <span>Status: <span id="status-label">{{ $job->status }}</span></span>
                <span id="percent-label">0%</span>
            </div>
            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                <div id="progress-bar"
                     class="h-full bg-blue-500 rounded-full transition-all duration-300"
                     style="width: 0%">
                </div>
            </div>
        </div>

        <p id="extra-info" class="text-xs text-gray-500 mt-2">
            Jangan tutup halaman ini. Anda dapat membuka tab baru untuk aktivitas lain
            sambil menunggu import selesai.
        </p>

        <div id="done-actions" class="hidden mt-4">
            <a href="{{ route('gizi.index', ['period' => $job->period]) }}"
               class="inline-flex px-4 py-2 rounded-md bg-green-600 text-white text-sm hover:bg-green-700">
                Lihat Data Gizi
            </a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const progressBar  = document.getElementById('progress-bar');
        const percentLabel = document.getElementById('percent-label');
        const statusLabel  = document.getElementById('status-label');
        const extraInfo    = document.getElementById('extra-info');
        const doneActions  = document.getElementById('done-actions');

        const jobId = {{ $job->id }};
        const url   = "{{ route('gizi.import.status', ':id') }}".replace(':id', jobId);

        function pollStatus() {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const pct = data.percent ?? 0;

                    progressBar.style.width = pct + '%';
                    percentLabel.textContent = pct + '%';
                    statusLabel.textContent  = data.status;

                    if (data.status === 'success') {
                        extraInfo.textContent = 'Import selesai. Anda dapat melihat data pada halaman Data Gizi.';
                        doneActions.classList.remove('hidden');
                        return; // stop polling
                    }

                    if (data.status === 'failed') {
                        extraInfo.textContent = 'Import gagal: ' + (data.error_message || 'Terjadi kesalahan.');
                        return; // stop polling
                    }

                    // kalau masih queued / running → lanjut polling
                    setTimeout(pollStatus, 2000);
                })
                .catch(() => {
                    // Kalau error jaringan, coba lagi lebih lambat
                    setTimeout(pollStatus, 4000);
                });
        }

        pollStatus();
    });
    </script>
</x-app-layout>
