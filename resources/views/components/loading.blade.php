<div id="app-loader" x-data="loadingComponent()" x-init="init()" x-show="show" aria-live="polite" role="status"
     class="fixed inset-0 w-screen h-screen z-[9999] bg-white/25 backdrop-blur-md saturate-[180%] m-0 p-0 overflow-hidden"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center gap-5">

        <div class="relative flex items-center justify-center"
             style="width: {{ $size }}px; height: {{ $size }}px;">

            @if ($ring)
                {{-- Ring 1 --}}
                <div class="absolute inset-0 rounded-full box-border opacity-0"
                     style="border: {{ $ringThickness }}px solid {{ $ringColor }}; animation: ld-pulse 2.2s ease-out infinite 0s; animation-fill-mode: both;">
                </div>
                {{-- Ring 2 --}}
                <div class="absolute inset-0 rounded-full box-border opacity-0"
                     style="border: {{ $ringThickness }}px solid {{ $ringColor }}; animation: ld-pulse 2.2s ease-out infinite 0.75s; animation-fill-mode: both;">
                </div>
                {{-- Ring 3 --}}
                <div class="absolute inset-0 rounded-full box-border opacity-0"
                     style="border: {{ $ringThickness }}px solid {{ $ringColor }}; animation: ld-pulse 2.2s ease-out infinite 1.5s; animation-fill-mode: both;">
                </div>
            @endif

            {{-- Logo di tengah, z-index lebih tinggi --}}
            @if ($img)
                <img src="{{ $img }}" alt="{{ $alt }}"
                     class="relative z-[2] object-contain"
                     style="width: {{ $logo }}px; height: {{ $logo }}px;">
            @else
                <div class="relative z-[2] rounded-full border-[3px] border-gray-200 border-t-gray-900 animate-spin"
                     style="width: {{ min(40, $logo) }}px; height: {{ min(40, $logo) }}px; animation-duration: 0.9s;">
                </div>
            @endif
        </div>

        <span class="text-[11px] tracking-[0.12em] text-gray-400 uppercase animate-pulse">
            loading
        </span>
    </div>

    <style>
        @keyframes ld-pulse {
            0% { transform: scale(0.3); opacity: 0.85; }
            100% { transform: scale(1); opacity: 0; }
        }
        html, body { overscroll-behavior: none; }
    </style>
</div>