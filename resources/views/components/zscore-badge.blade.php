@props(['value' => null])

@php
    $isNull    = is_null($value) || $value === '';
    $isNumeric = !$isNull && is_numeric($value);
    $numeric   = $isNumeric ? (float) $value : null;

    // Default
    $classes = 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
    $label   = 'N/A';

    if ($isNull) {
        $classes = 'bg-gradient-to-r from-gray-400 to-gray-500 text-white';
        $label   = 'N/A';
    } elseif ($isNumeric) {
        // ===== CABANG ANGKA (Z-SCORE) =====
        if ($numeric < -3) {
            $classes = 'bg-gradient-to-r from-red-500 to-red-600 text-white';
        } elseif ($numeric < -2) {
            $classes = 'bg-gradient-to-r from-orange-500 to-orange-600 text-white';
        } elseif ($numeric <= 2) {
            $classes = 'bg-gradient-to-r from-green-500 to-green-600 text-white';
        } else {
            $classes = 'bg-gradient-to-r from-blue-500 to-blue-600 text-white';
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

            $classes = 'bg-gradient-to-r from-red-600 to-red-700 text-white';

        // --- Orange ---
        } elseif (str_contains($lower, 'kurang') && !str_contains($lower, 'sangat kurang')) {
            // "kurang" tapi bukan "sangat kurang"
            $classes = 'bg-gradient-to-r from-orange-400 to-orange-500 text-white';

        } elseif (str_contains($lower, 'pendek') && !str_contains($lower, 'sangat pendek')) {
            $classes = 'bg-gradient-to-r from-orange-400 to-orange-500 text-white';

        // --- Kuning ---
        } elseif (str_contains($lower, 'risiko gizi lebih')) {
            $classes = 'bg-gradient-to-r from-yellow-300 to-yellow-400 text-gray-900';

        // --- Hijau ---
        } elseif (str_contains($lower, 'normal') ||
                str_contains($lower, 'gizi baik')) {

            $classes = 'bg-gradient-to-r from-green-500 to-green-600 text-white';

        // --- Biru ---
        } elseif (str_contains($lower, 'tinggi') ||
                str_contains($lower, 'gizi lebih')) {

            $classes = 'bg-gradient-to-r from-blue-500 to-blue-600 text-white';

        // --- Default (tidak terpetakan) ---
        } else {
            $classes = 'bg-gradient-to-r from-slate-400 to-slate-600 text-white';
        }

        $label = $text;
    }
@endphp

<span
    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm {{ $classes }}">
    {{ $label }}
</span>
