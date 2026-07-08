@props(['value' => null])

@php
    $isNull    = is_null($value) || $value === '';
    $isNumeric = !$isNull && is_numeric($value);
    $numeric   = $isNumeric ? (float) $value : null;

    // Base classes
    $base = 'inline-flex items-center px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase transition-all duration-300 hover:scale-105';

    // Default
    $classes = 'bg-gradient-to-br from-white via-slate-50 to-slate-100 text-slate-500 shadow-sm shadow-slate-200/30 ring-1 ring-slate-200/60';
    $label = 'N/A';

    if ($isNumeric) {

        if ($numeric > 20) {
            // Tinggi (Merah)
            $classes = 'bg-gradient-to-br from-rose-50 via-rose-100 to-rose-200 text-rose-700 shadow-sm shadow-rose-200/30 ring-1 ring-rose-200/60';

        } elseif ($numeric > 10) {
            // Sedang (Orange)
            $classes = 'bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 text-orange-700 shadow-sm shadow-orange-200/30 ring-1 ring-orange-200/60';

        } else {
            // Rendah (Hijau)
            $classes = 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-emerald-200 text-emerald-700 shadow-sm shadow-emerald-200/30 ring-1 ring-emerald-200/60';
        }

        $label = number_format($numeric, 2) . '%';
    }
@endphp

<span class="{{ $base }} {{ $classes }}">
    {{ $label }}
</span>