@props(['data'])

@php
    // Dapatkan nama parameter halaman yang unik (cth: 'giziPage', 'desaPage')
    $pageName = $data->getPageName();
@endphp

@if ($data->hasPages())
    <div class="mt-6 sm:mt-8 px-4 sm:px-0">
        {{-- Mobile Version: Compact Pagination --}}
        <div class="flex sm:hidden flex-col gap-3">
            <div class="flex items-center justify-center">
                <nav class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                    {{-- Previous Button --}}
                    @if ($data->onFirstPage())
                        <span
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $data->previousPageUrl() }}"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif

                    {{-- First Page --}}
                    @if ($data->currentPage() == 1)
                        <span
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                            1
                        </span>
                    @else
                        <a href="{{ $data->url(1) }}"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                            1
                        </a>
                    @endif

                    {{-- Left Ellipsis --}}
                    @if ($data->currentPage() > 2)
                        <button onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                            ...
                        </button>
                    @endif

                    {{-- Current Page (if not first or last) --}}
                    @if ($data->currentPage() != 1 && $data->currentPage() != $data->lastPage())
                        <span
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                            {{ $data->currentPage() }}
                        </span>
                    @endif

                    {{-- Right Ellipsis --}}
                    @if ($data->currentPage() < $data->lastPage() - 1)
                        <button onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                            ...
                        </button>
                    @endif

                    {{-- Last Page --}}
                    @if ($data->lastPage() > 1)
                        @if ($data->currentPage() == $data->lastPage())
                            <span
                                class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                                {{ $data->lastPage() }}
                            </span>
                        @else
                            <a href="{{ $data->url($data->lastPage()) }}"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                                {{ $data->lastPage() }}
                            </a>
                        @endif
                    @endif

                    {{-- Next Button --}}
                    @if ($data->hasMorePages())
                        <a href="{{ $data->nextPageUrl() }}"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span
                            class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </nav>
            </div>

            {{-- Mobile Go To --}}
            <div class="flex items-center justify-center gap-2">
                <span class="text-sm text-gray-500">Go to:</span>
                <form method="GET" action="" class="flex items-center gap-2"
                    onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $data->lastPage() }}) window.location.href = '{{ $data->url(1) }}'.replace(/page=\d+/, 'page=' + page);">
                    <input type="number" name="page" min="1" max="{{ $data->lastPage() }}"
                        placeholder="{{ $data->currentPage() }}"
                        class="w-16 px-3 py-2 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">
                        Go
                    </button>
                </form>
            </div>
        </div>

        {{-- Desktop/Tablet Version: Compact Pagination --}}
        <div class="hidden sm:flex flex-col items-center gap-3">
            <nav class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                {{-- Previous Button --}}
                @if ($data->onFirstPage())
                    <span
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $data->previousPageUrl() }}"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- First Page --}}
                @if ($data->currentPage() == 1)
                    <span
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                        1
                    </span>
                @else
                    <a href="{{ $data->url(1) }}"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                        1
                    </a>
                @endif

                {{-- Left Ellipsis --}}
                @if ($data->currentPage() > 3)
                    <button onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                        ...
                    </button>
                @endif

                {{-- Previous Adjacent Page --}}
                @if ($data->currentPage() > 2)
                    <a href="{{ $data->url($data->currentPage() - 1) }}"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                        {{ $data->currentPage() - 1 }}
                    </a>
                @endif

                {{-- Current Page (if not first or last) --}}
                @if ($data->currentPage() != 1 && $data->currentPage() != $data->lastPage())
                    <span
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                        {{ $data->currentPage() }}
                    </span>
                @endif

                {{-- Next Adjacent Page --}}
                @if ($data->currentPage() < $data->lastPage() - 1)
                    <a href="{{ $data->url($data->currentPage() + 1) }}"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                        {{ $data->currentPage() + 1 }}
                    </a>
                @endif

                {{-- Right Ellipsis --}}
                @if ($data->currentPage() < $data->lastPage() - 2)
                    <button onclick="document.getElementById('pageJumpModal').classList.remove('hidden')"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                        ...
                    </button>
                @endif

                {{-- Last Page --}}
                @if ($data->lastPage() > 1)
                    @if ($data->currentPage() == $data->lastPage())
                        <span
                            class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">
                            {{ $data->lastPage() }}
                        </span>
                    @else
                        <a href="{{ $data->url($data->lastPage()) }}"
                            class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">
                            {{ $data->lastPage() }}
                        </a>
                    @endif
                @endif

                {{-- Next Button --}}
                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}"
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span
                        class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </nav>

            {{-- Desktop Go To --}}
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Go to page:</span>
                <form method="GET" action="" class="flex items-center gap-2"
                    onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $data->lastPage() }}) window.location.href = '{{ $data->url(1) }}'.replace(/page=\d+/, 'page=' + page);">
                    <input type="number" name="page" min="1" max="{{ $data->lastPage() }}"
                        placeholder="{{ $data->currentPage() }}"
                        class="w-20 px-3 py-1.5 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <button type="submit"
                        class="px-4 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">
                        Go
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Text (Optional) --}}
        <div class="mt-3 sm:mt-4 text-center">
            <p class="text-xs sm:text-sm text-gray-500">
                Menampilkan <span class="font-medium text-gray-700">{{ $data->firstItem() }}</span>
                sampai <span class="font-medium text-gray-700">{{ $data->lastItem() }}</span>
                dari <span class="font-medium text-gray-700">{{ $data->total() }}</span> hasil
            </p>
        </div>
    </div>
@endif
