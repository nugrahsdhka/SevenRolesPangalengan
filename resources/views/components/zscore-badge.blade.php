@props(['value' => null])

@php
    $isNull    = is_null($value) || $value === '';
    $isNumeric = !$isNull && is_numeric($value);
    $numeric   = $isNumeric ? (float) $value : null;

    // ── Base classes (soft, clean, minimal) ──
    $base = 'inline-flex items-center px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase transition-all duration-300 hover:scale-105';

    // ── Default / N/A ──
    $classes = 'bg-gradient-to-br from-white via-slate-50 to-slate-100 text-slate-500 shadow-sm shadow-slate-200/30 ring-1 ring-slate-200/60';
    $label   = 'N/A';

    if ($isNull) {
        // tetap default
    } elseif ($isNumeric) {
        // ═══════════════════════════════════════
        //  CABANG ANGKA (Z-SCORE)
        // ═══════════════════════════════════════
        if ($numeric < -3) {
            // Sangat rendah ── soft red
            $classes = 'bg-gradient-to-br from-rose-50 via-rose-100 to-rose-200 text-rose-700 shadow-sm shadow-rose-200/30 ring-1 ring-rose-200/60';
        } elseif ($numeric < -2) {
            // Rendah ── soft orange
            $classes = 'bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 text-orange-700 shadow-sm shadow-orange-200/30 ring-1 ring-orange-200/60';
        } elseif ($numeric <= 2) {
            // Normal ── soft green
            $classes = 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-emerald-200 text-emerald-700 shadow-sm shadow-emerald-200/30 ring-1 ring-emerald-200/60';
        } else {
            // Tinggi ── soft blue
            $classes = 'bg-gradient-to-br from-sky-50 via-sky-100 to-sky-200 text-sky-700 shadow-sm shadow-sky-200/30 ring-1 ring-sky-200/60';
        }

        $label = number_format($numeric, 2);

    } else {
        $text  = (string) $value;
        $lower = mb_strtolower($text);

        // ═══════════════════════════════════════
        //  CABANG TEKS (STATUS GIZI)
        // ═══════════════════════════════════════

        // ── Merah (severe) ──
        if (str_contains($lower, 'sangat kurang') ||
            str_contains($lower, 'resiko lebih') ||
            str_contains($lower, 'sangat pendek') ||
            str_contains($lower, 'risiko gizi kurang') ||
            str_contains($lower, 'obesitas')) {

            $classes = 'bg-gradient-to-br from-rose-50 via-rose-100 to-rose-200 text-rose-700 shadow-sm shadow-rose-200/30 ring-1 ring-rose-200/60';

        // ── Orange ──
        } elseif (str_contains($lower, 'kurang') && !str_contains($lower, 'sangat kurang')) {
            $classes = 'bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 text-orange-700 shadow-sm shadow-orange-200/30 ring-1 ring-orange-200/60';

        } elseif (str_contains($lower, 'pendek') && !str_contains($lower, 'sangat pendek')) {
            $classes = 'bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 text-orange-700 shadow-sm shadow-orange-200/30 ring-1 ring-orange-200/60';

        // ── Kuning ──
        } elseif (str_contains($lower, 'risiko gizi lebih') || str_contains($lower, 'risiko lebih')) {
            $classes = 'bg-gradient-to-br from-amber-50 via-amber-100 to-amber-200 text-amber-700 shadow-sm shadow-amber-200/30 ring-1 ring-amber-200/60';

        // ── Hijau ──
        } elseif (str_contains($lower, 'normal') ||
                   str_contains($lower, 'gizi baik')) {

            $classes = 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-emerald-200 text-emerald-700 shadow-sm shadow-emerald-200/30 ring-1 ring-emerald-200/60';

        // ── Biru ──
        } elseif (str_contains($lower, 'tinggi') ||
                   str_contains($lower, 'gizi lebih')) {

            $classes = 'bg-gradient-to-br from-sky-50 via-sky-100 to-sky-200 text-sky-700 shadow-sm shadow-sky-200/30 ring-1 ring-sky-200/60';

        // ── Default (tidak terpetakan) ──
        } else {
            $classes = 'bg-gradient-to-br from-white via-slate-50 to-slate-100 text-slate-500 shadow-sm shadow-slate-200/30 ring-1 ring-slate-200/60';
        }

        $label = $text;
    }
@endphp

<span class="{{ $base }} {{ $classes }}">
    {{ $label }}
</span>