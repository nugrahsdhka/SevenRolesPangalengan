<x-guest-layout>
    <x-slot name="title">
        Verifikasi Email
    </x-slot>

    <div class="w-screen h-screen bg-gradient-to-r from-red-600 via-red-800 to-red-600 flex justify-center items-center">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="rounded-2xl bg-white p-8 shadow-lg sm:p-10">
                {{-- Header --}}
                <div class="mb-8 text-center">
                    <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-red-600 shadow-md">
                        <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-gray-900">Verifikasi Alamat Email Anda</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Satu langkah lagi! Silakan cek email Anda untuk menemukan link verifikasi dan mengaktifkan akun Anda.
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </div>
                @endif

                <div class="mt-6 flex items-center justify-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <x-primary-button class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                            {{ __('Kirim Ulang Email Verifikasi') }}
                        </x-primary-button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class.blade.php" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>