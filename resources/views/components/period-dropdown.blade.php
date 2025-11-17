@props([
    'availablePeriods' => collect(), // koleksi period "Y-m"
    'selectedPeriod' => null,        // period aktif "Y-m"
    'action' => null,                // form action, default: url()->current()
    'paramName' => 'period',         // nama query param (default: ?period=...)
    'variant' => 'light',            // 'light' (bg putih) atau 'red' (header merah)
])

@php
    $action = $action ?: url()->current();
    $hasData = $availablePeriods && $availablePeriods->count() > 0;

    // Default (light) – buat halaman dengan background putih
    $wrapperClass = 'flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3 py-2';
    $labelClass   = 'text-[11px] md:text-xs font-semibold text-gray-700 whitespace-nowrap';
    $selectClass  = 'appearance-none bg-white text-xs md:text-sm text-gray-800 font-medium
                     px-3 py-1.5 pr-8 rounded-lg shadow-sm border border-gray-300
                     focus:outline-none focus:ring-2 focus:ring-red-500/60 focus:border-red-500
                     cursor-pointer';
    $iconClass    = 'text-gray-500';

    // Variant khusus header merah di Home
    if ($variant === 'red') {
        $wrapperClass = 'flex items-center gap-2 bg-red-900/40 border border-red-600/60 rounded-xl px-3 py-2';
        $labelClass   = 'text-[11px] md:text-xs font-semibold text-red-50 whitespace-nowrap';
        $selectClass  = 'appearance-none bg-white text-xs md:text-sm font-semibold text-red-700
                         px-3 py-1.5 pr-8 rounded-lg shadow-sm border border-red-200
                         focus:outline-none focus:ring-2 focus:ring-red-400/70 focus:border-red-400
                         cursor-pointer';
        $iconClass    = 'text-red-500';
    }
@endphp

@if ($hasData)
    <form method="GET" action="{{ $action }}" class="{{ $wrapperClass }}">
        <label for="periodDropdown_{{ $paramName }}" class="{{ $labelClass }}">
            Periode
        </label>

        <div class="relative">
            <select id="periodDropdown_{{ $paramName }}" name="{{ $paramName }}"
                class="select-no-arrow {{ $selectClass }}"
                onchange="this.form.submit()">
                @foreach ($availablePeriods as $period)
                    @php
                        try {
                            $label = \Carbon\Carbon::createFromFormat('Y-m', $period)->isoFormat('MMMM Y');
                        } catch (\Exception $e) {
                            $label = $period;
                        }
                    @endphp
                    <option value="{{ $period }}"
                        {{ ($selectedPeriod ?? '') === $period ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            {{-- Icon chevron --}}
            <span class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                <svg class="w-3 h-3 md:w-4 md:h-4 {{ $iconClass }}" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>
    </form>
@endif
