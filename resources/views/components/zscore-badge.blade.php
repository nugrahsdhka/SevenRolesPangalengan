@props(['value' => null])

@php
    $isNull    = is_null($value) || $value === '';
    $isNumeric = !$isNull && is_numeric($value);
    $numeric   = $isNumeric ? (float) $value : null;

    // Default
    $classes = 'bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 text-white shadow-lg shadow-gray-400/50';
    $label   = 'N/A';

    if ($isNull) {
        $classes = 'bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 text-white shadow-lg shadow-gray-400/50';
        $label   = 'N/A';
    } elseif ($isNumeric) {
        // ===== CABANG ANGKA (Z-SCORE) =====
        if ($numeric < -3) {
            $classes = 'bg-gradient-to-br from-red-500 via-red-600 to-rose-700 text-white shadow-lg shadow-red-500/50';
        } elseif ($numeric < -2) {
            $classes = 'bg-gradient-to-br from-orange-400 via-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/50';
        } elseif ($numeric <= 2) {
            $classes = 'bg-gradient-to-br from-green-400 via-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/50';
        } else {
            $classes = 'bg-gradient-to-br from-blue-400 via-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/50';
        }

        $label = number_format($numeric, 2);
    } else {
        $text  = (string) $value;
        $lower = mb_strtolower($text);

        // --- Merah (severe) ---
        if (str_contains($lower, 'sangat kurang') ||
            str_contains($lower, 'resiko lebih') ||
            str_contains($lower, 'sangat pendek') ||
            str_contains($lower, 'risiko gizi kurang') ||
            str_contains($lower, 'obesitas')) {

            $classes = 'bg-gradient-to-br from-red-500 via-red-600 to-rose-700 text-white shadow-lg shadow-red-500/50';

        // --- Orange ---
        } elseif (str_contains($lower, 'kurang') && !str_contains($lower, 'sangat kurang')) {
            $classes = 'bg-gradient-to-br from-orange-400 via-orange-500 to-amber-600 text-white shadow-lg shadow-orange-500/50';

        } elseif (str_contains($lower, 'pendek') && !str_contains($lower, 'sangat pendek')) {
            $classes = 'bg-gradient-to-br from-orange-400 via-orange-500 to-amber-600 text-white shadow-lg shadow-orange-500/50';

        // --- Kuning ---
        } elseif (str_contains($lower, 'risiko gizi lebih') || str_contains($lower, 'risiko lebih')) {
            $classes = 'bg-gradient-to-br from-yellow-300 via-yellow-400 to-amber-500 text-gray-900 shadow-lg shadow-yellow-400/50';

        // --- Hijau ---
        } elseif (str_contains($lower, 'normal') ||
                str_contains($lower, 'gizi baik')) {

            $classes = 'bg-gradient-to-br from-green-400 via-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/50';

        // --- Biru ---
        } elseif (str_contains($lower, 'tinggi') ||
                str_contains($lower, 'gizi lebih')) {

            $classes = 'bg-gradient-to-br from-blue-400 via-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/50';

        // --- Default (tidak terpetakan) ---
        } else {
            $classes = 'bg-gradient-to-br from-slate-400 via-slate-500 to-slate-700 text-white shadow-lg shadow-slate-500/50';
        }

        $label = $text;
    }
@endphp

<span
    class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase transition-all duration-300 hover:scale-105 hover:shadow-xl {{ $classes }}">
    {{ $label }}
</span>