<x-guest-layout>
    <x-slot name="title">
        Verifikasi Email
    </x-slot>

    <div class="min-h-screen flex items-center justify-center bg-red-50 px-4 py-12">
        <div class="w-full max-w-lg rounded-2xl bg-white p-8 shadow-lg">
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Email Diperlukan</h1>

            <p class="mt-4 text-sm leading-6 text-gray-600">
                Akun Anda belum terverifikasi. Untuk melanjutkan, keluar dari sesi ini,
                kemudian buka menu verifikasi email dan masukkan alamat email akun Anda.
                Sistem akan mengarahkan Anda ke proses verifikasi yang sesuai.
            </p>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Keluar dan Lanjutkan Verifikasi
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
