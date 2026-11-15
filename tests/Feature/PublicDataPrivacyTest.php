<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Halaman publik hanya boleh menampilkan data agregat.
 * Data per-anak (nama, tgl lahir, status gizi) tidak boleh punya endpoint publik.
 */
class PublicDataPrivacyTest extends TestCase
{
    public function test_public_child_level_detail_route_is_removed(): void
    {
        $this->assertFalse(Route::has('data-wilayah-detail-gizi'));

        $this->get('/data-wilayah/detail?desa=PANGALENGAN')->assertNotFound();
        $this->get('/data-wilayah/detail?desa=PANGALENGAN&period=2026-01&rt=01&rw=01')->assertNotFound();
    }

    public function test_no_guest_accessible_route_points_to_child_level_detail(): void
    {
        $leaks = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => in_array('GET', $r->methods(), true))
            ->filter(fn ($r) => ! collect($r->gatherMiddleware())->contains(
                fn ($m) => is_string($m) && (str_starts_with($m, 'auth') || str_starts_with($m, 'role'))
            ))
            ->filter(fn ($r) => str_contains($r->uri(), 'detail'))
            ->map(fn ($r) => $r->uri())
            ->values()
            ->all();

        $this->assertSame([], $leaks, 'Route publik berisi "detail": ' . implode(', ', $leaks));
    }
} 
