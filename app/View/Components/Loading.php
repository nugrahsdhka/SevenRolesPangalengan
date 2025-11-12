<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loading extends Component
{
    public string $text;
    public ?string $img;
    public string $alt;

    // Ring options
    public bool $ring;
    public int $size;          // ukuran area (px) -> diameter total (logo + ring)
    public int $logo;          // ukuran logo (px)
    public int $ringThickness; // px
    public string $ringColor;  // warna utama ring
    public string $ringTrail;  // warna trail (bagian yg “pudar”)
    public string $speed;      // durasi 1 putaran, contoh "0.9s"
    public string $mode;       // "quarter" (seperti spinner Bootstrap) | "dual" (dua ring berlawanan)

    public function __construct(
        string $text = 'Memuat...',
        string $img = null,
        string $alt = 'Loading',
        bool $ring = true,
        int $size = 96,
        int $logo = 64,
        int $ringThickness = 4,
        string $ringColor = '#111827',   // gray-900
        string $ringTrail = '#E5E7EB',   // gray-200
        string $speed = '0.9s',
        string $mode = 'quarter'         // quarter | dual
    ) {
        $this->text = $text;
        $this->img = $img ?: null;
        $this->alt = $alt;

        $this->ring = $ring;
        $this->size = max($logo + $ringThickness*2 + 8, min($size, 320));
        $this->logo = max(16, min($logo, $this->size - 8));
        $this->ringThickness = max(2, min($ringThickness, 16));
        $this->ringColor = $ringColor;
        $this->ringTrail = $ringTrail;
        $this->speed = $speed;
        $this->mode = in_array($mode, ['quarter','dual']) ? $mode : 'quarter';
    }

    public function render(): View|Closure|string
    {
        return view('components.loading');
    }
}
