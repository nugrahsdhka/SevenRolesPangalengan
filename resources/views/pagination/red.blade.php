@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
    @endphp

    <nav role="navigation" aria-label="Pagination"
         class="flex flex-col justify-center md:flex-row items-center md:justify-between gap-3 mt-4">

        {{-- Info kiri --}}
        @if ($paginator->firstItem())
            <div class="text-xs sm:text-sm text-gray-600">
                Menampilkan
                <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-semibold">{{ $paginator->total() }}</span>
                data
            </div>
        @else
            <div class="text-xs sm:text-sm text-gray-600">
                Menampilkan {{ $paginator->count() }} data
            </div>
        @endif

        {{-- Links kanan --}}
        <ul class="inline-flex items-center gap-1 text-xs sm:text-sm">

            {{-- Tombol Previous "<" --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400 cursor-default">
                        &lt;
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                        &lt;
                    </a>
                </li>
            @endif

            {{-- === MODE SEDERHANA: kalau total halaman sedikit, tampilkan semua === --}}
            @if ($last <= 5)
                @for ($page = 1; $page <= $last; $page++)
                    @if ($page == $current)
                        <li>
                            <span
                                class="px-3 py-1 rounded-lg border border-red-600 bg-red-600 text-white font-semibold shadow-sm">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->url($page) }}"
                               class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endfor
            @else
                {{-- === MODE "1 ... [3 tengah] ... last" === --}}

                {{-- Halaman pertama: "1" --}}
                @if ($current == 1)
                    <li>
                        <span
                            class="px-3 py-1 rounded-lg border border-red-600 bg-red-600 text-white font-semibold shadow-sm">
                            1
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url(1) }}"
                           class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                            1
                        </a>
                    </li>
                @endif

                {{-- Ellipsis kiri (kalau window mulai lebih dari 2) --}}
                @php
                    // Window 3 halaman di sekitar current: (current-1, current, current+1)
                    $start = max(2, $current - 1);
                    $end = min($last - 1, $current + 1);
                @endphp

                @if ($start > 2)
                    <li>
                        <span class="px-2 sm:px-3 py-1 text-gray-500">
                            ...
                        </span>
                    </li>
                @endif

                {{-- 3 halaman di tengah --}}
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $current)
                        <li>
                            <span
                                class="px-3 py-1 rounded-lg border border-red-600 bg-red-600 text-white font-semibold shadow-sm">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->url($page) }}"
                               class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endfor

                {{-- Ellipsis kanan (kalau window berakhir sebelum last-1) --}}
                @if ($end < $last - 1)
                    <li>
                        <span class="px-2 sm:px-3 py-1 text-gray-500">
                            ...
                        </span>
                    </li>
                @endif

                {{-- Halaman terakhir: "last" --}}
                @if ($last > 1)
                    @if ($current == $last)
                        <li>
                            <span
                                class="px-3 py-1 rounded-lg border border-red-600 bg-red-600 text-white font-semibold shadow-sm">
                                {{ $last }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->url($last) }}"
                               class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                                {{ $last }}
                            </a>
                        </li>
                    @endif
                @endif
            @endif

            {{-- Tombol Next ">" --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-red-50 hover:border-red-500 hover:text-red-600 transition">
                        &gt;
                    </a>
                </li>
            @else
                <li>
                    <span
                        class="px-3 py-1 rounded-lg border border-gray-200 text-gray-400 cursor-default">
                        &gt;
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
