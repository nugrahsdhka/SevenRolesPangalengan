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
        // ===== CABANG TEKS (STATUS) =====
        $text  = (string) $value;
        $lower = mb_strtolower($text);

        // Mapping warna berdasarkan kata kunci di status
        if (str_contains($lower, 'normal')) {
            $classes = 'bg-gradient-to-r from-green-500 to-emerald-600 text-white';
        } elseif (str_contains($lower, 'risiko') || str_contains($lower, 'ringan')) {
            $classes = 'bg-gradient-to-r from-yellow-400 to-amber-500 text-gray-900';
        } elseif (str_contains($lower, 'kurang') || str_contains($lower, 'stunting') || str_contains($lower, 'buruk')) {
            $classes = 'bg-gradient-to-r from-red-500 to-red-600 text-white';
        } elseif (str_contains($lower, 'gemuk') || str_contains($lower, 'obes')) {
            $classes = 'bg-gradient-to-r from-blue-500 to-blue-600 text-white';
        } else {
            // Status lain yang tidak terpetakan
            $classes = 'bg-gradient-to-r from-slate-400 to-slate-600 text-white';
        }

        $label = $text; // Tampilkan teks status apa adanya
    }
@endphp

<span
    class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm {{ $classes }}">
    {{ $label }}
</span>
