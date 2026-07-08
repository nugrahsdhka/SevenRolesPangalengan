@props(['value' => null])

@php
    $isNull = is_null($value) || trim((string) $value) === '';
    $status = $isNull ? null : mb_strtolower(trim((string) $value));

    // ── Base classes (soft, clean, minimal) ──
    $base = 'inline-flex items-center px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase transition-all duration-300 hover:scale-105';

    // ── Default / N/A ──
    $classes = 'bg-gradient-to-br from-white via-slate-50 to-slate-100 text-slate-500 shadow-sm shadow-slate-200/30 ring-1 ring-slate-200/60';
    $label = 'N/A';

    if (!$isNull) {

        switch ($status) {

            // ── Published (Hijau) ──
            case 'published':
                $classes = 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-emerald-200 text-emerald-700 shadow-sm shadow-emerald-200/30 ring-1 ring-emerald-200/60';
                break;

            // ── Approved (Biru) ──
            case 'approved':
                $classes = 'bg-gradient-to-br from-sky-50 via-sky-100 to-sky-200 text-sky-700 shadow-sm shadow-sky-200/30 ring-1 ring-sky-200/60';
                break;

            // ── Default (Orange) ──
            default:
                $classes = 'bg-gradient-to-br from-orange-50 via-orange-100 to-orange-200 text-orange-700 shadow-sm shadow-orange-200/30 ring-1 ring-orange-200/60';
                break;
        }

        // Tampilkan label yang lebih rapi
        $label = ucwords(str_replace('_', ' ', $status));
    }
@endphp

<span class="{{ $base }} {{ $classes }}">
    {{ $label }}
</span>