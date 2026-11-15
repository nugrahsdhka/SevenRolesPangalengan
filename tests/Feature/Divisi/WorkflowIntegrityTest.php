<?php

namespace Tests\Feature\Divisi;

use App\Models\DataDesa;
use App\Models\DataGizi;
use App\Models\DataPencegahan;
use App\Models\DataPenanggulangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Pastikan user Divisi tidak bisa melewati alur Pending → Approved → Published
 * (mass assignment status/user_id) dan tidak bisa mengubah data yang sudah terkunci.
 */
class WorkflowIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (['Divisi Gizi', 'Divisi Pencegahan', 'Divisi Penanggulangan', 'Divisi Desa'] as $role) {
            Role::findOrCreate($role, 'web');
        }
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->create()->assignRole($role);
    }

    // ------------------------------------------------------------------
    // Pencegahan
    // ------------------------------------------------------------------

    private function pencegahanPayload(array $extra = []): array
    {
        // array_merge: nilai di $extra menimpa default (operator + justru kebalikannya).
        return array_merge([
            'periode'          => '2026-01',
            'judul_kegiatan'   => 'Penyuluhan ASI',
            'lokasi'           => 'Posyandu Melati',
            'tanggal_kegiatan' => '2026-01-10',
            'laporan_singkat'  => 'Berjalan lancar.',
        ], $extra);
    }

    public function test_pencegahan_store_ignores_status_and_user_id_from_input(): void
    {
        $actor = $this->userWithRole('Divisi Pencegahan');
        $other = $this->userWithRole('Divisi Desa');

        $this->actingAs($actor)->post(route('pencegahan.store'), $this->pencegahanPayload([
            'status'  => 'published',
            'user_id' => $other->id,
        ]))->assertRedirect();

        $row = DataPencegahan::firstOrFail();
        $this->assertSame('pending', $row->status);
        $this->assertSame($actor->id, $row->user_id);
    }

    public function test_pencegahan_update_cannot_change_status_or_owner(): void
    {
        $actor = $this->userWithRole('Divisi Pencegahan');
        $other = $this->userWithRole('Divisi Desa');

        $row = DataPencegahan::create($this->pencegahanPayload([
            'periode' => '2026-01-01', 'user_id' => $actor->id, 'status' => 'pending',
        ]));

        $this->actingAs($actor)->put(route('pencegahan.update', $row), $this->pencegahanPayload([
            'judul_kegiatan' => 'Judul Baru',
            'status'         => 'published',
            'user_id'        => $other->id,
        ]))->assertRedirect();

        $row->refresh();
        $this->assertSame('Judul Baru', $row->judul_kegiatan);
        $this->assertSame('pending', $row->status);
        $this->assertSame($actor->id, $row->user_id);
    }

    public function test_pencegahan_locked_rows_cannot_be_updated_or_deleted(): void
    {
        $actor = $this->userWithRole('Divisi Pencegahan');

        foreach (['approved', 'published'] as $status) {
            $row = DataPencegahan::create($this->pencegahanPayload([
                'periode' => '2026-01-01', 'user_id' => $actor->id, 'status' => $status,
            ]));

            $this->actingAs($actor)
                ->put(route('pencegahan.update', $row), $this->pencegahanPayload(['judul_kegiatan' => 'Diubah']))
                ->assertSessionHas('error');

            $this->actingAs($actor)
                ->delete(route('pencegahan.destroy', $row))
                ->assertSessionHas('error');

            $row->refresh();
            $this->assertSame('Penyuluhan ASI', $row->judul_kegiatan);
            $this->assertSame($status, $row->status);
            $this->assertDatabaseHas('data_pencegahans', ['id' => $row->id]);
        }
    }

    // ------------------------------------------------------------------
    // Penanggulangan & Desa
    // ------------------------------------------------------------------

    public function test_penanggulangan_store_ignores_status_and_user_id(): void
    {
        $actor = $this->userWithRole('Divisi Penanggulangan');
        $other = $this->userWithRole('Divisi Desa');

        $this->actingAs($actor)->post(route('penanggulangan.store'), [
            'periode'       => '2026-01',
            'jenis_bencana' => 'Banjir',
            'jumlah_korban' => 3,
            'tindakan'      => 'Evakuasi',
            'status'        => 'published',
            'user_id'       => $other->id,
        ])->assertRedirect();

        $row = DataPenanggulangan::firstOrFail();
        $this->assertSame('pending', $row->status);
        $this->assertSame($actor->id, $row->user_id);
    }

    public function test_desa_store_ignores_status_and_user_id(): void
    {
        $actor = $this->userWithRole('Divisi Desa');
        $other = $this->userWithRole('Divisi Pencegahan');

        $this->actingAs($actor)->post(route('desa.store'), [
            'periode'         => '2026-01',
            'nama_desa'       => 'Margamukti',
            'jumlah_penduduk' => 1000,
            'luas_wilayah'    => 50,
            'potensi_desa'    => 'Teh',
            'status'          => 'published',
            'user_id'         => $other->id,
        ])->assertRedirect();

        $row = DataDesa::firstOrFail();
        $this->assertSame('pending', $row->status);
        $this->assertSame($actor->id, $row->user_id);
        $this->assertSame(0, (int) $row->jumlah_kasus_stunting);
    }

    // ------------------------------------------------------------------
    // Gizi
    // ------------------------------------------------------------------

    public function test_gizi_store_ignores_workflow_fields_from_input(): void
    {
        $actor = $this->userWithRole('Divisi Gizi');
        $other = $this->userWithRole('Divisi Desa');

        $this->actingAs($actor)->post(route('gizi.store'), [
            'period'         => '2026-01',
            'desa_kelurahan' => 'pangalengan',
            'berat'          => 10,
            'tinggi'         => 80,
            'zs_tb_u'        => -2.5,
            'status'         => 'published',
            'is_published'   => 1,
            'user_id'        => $other->id,
        ])->assertRedirect();

        $row = DataGizi::firstOrFail();
        $this->assertSame('pending', $row->status);
        $this->assertFalse((bool) $row->is_published);
        $this->assertSame($actor->id, $row->user_id);
        $this->assertSame('PANGALENGAN', $row->desa_kelurahan);
        $this->assertSame('2026-01-01', $row->period->format('Y-m-d'));
    }

    public function test_gizi_store_flags_out_of_range_zscore_for_review(): void
    {
        $actor = $this->userWithRole('Divisi Gizi');

        $this->actingAs($actor)->post(route('gizi.store'), [
            'period' => '2026-01', 'desa_kelurahan' => 'Pangalengan', 'zs_tb_u' => -9,
        ])->assertRedirect();

        $this->assertSame('need_review', DataGizi::firstOrFail()->status);
    }

    public function test_gizi_update_is_blocked_for_published_and_approved_rows(): void
    {
        $actor = $this->userWithRole('Divisi Gizi');

        foreach (['published', 'approved'] as $status) {
            $row = DataGizi::create([
                'user_id' => $actor->id, 'period' => '2026-01-01',
                'desa_kelurahan' => 'PANGALENGAN', 'status' => $status, 'zs_tb_u' => -2.5,
            ]);

            $this->actingAs($actor)->put(route('gizi.update', $row), [
                'desa_kelurahan' => 'PANGALENGAN', 'zs_tb_u' => 0.5,
            ])->assertSessionHas('error');

            $row->refresh();
            $this->assertSame($status, $row->status);
            $this->assertEquals(-2.5, (float) $row->zs_tb_u);
        }
    }

    public function test_gizi_update_is_forbidden_for_rows_of_another_role(): void
    {
        $actor = $this->userWithRole('Divisi Gizi');
        $owner = $this->userWithRole('Divisi Desa');

        $row = DataGizi::create([
            'user_id' => $owner->id, 'period' => '2026-01-01',
            'desa_kelurahan' => 'PANGALENGAN', 'status' => 'pending', 'zs_tb_u' => -1,
        ]);

        $this->actingAs($actor)->put(route('gizi.update', $row), [
            'desa_kelurahan' => 'PANGALENGAN', 'zs_tb_u' => 1,
        ])->assertForbidden();

        $this->assertEquals(-1, (float) $row->fresh()->zs_tb_u);
    }

    public function test_gizi_update_still_works_for_pending_rows_of_same_role(): void
    {
        $actor = $this->userWithRole('Divisi Gizi');

        $row = DataGizi::create([
            'user_id' => $actor->id, 'period' => '2026-01-01',
            'desa_kelurahan' => 'PANGALENGAN', 'status' => 'need_review', 'zs_tb_u' => -9,
        ]);

        $this->actingAs($actor)->put(route('gizi.update', $row), [
            'desa_kelurahan' => 'PANGALENGAN', 'zs_tb_u' => -2.1,
        ])->assertSessionHas('success');

        $row->refresh();
        $this->assertSame('pending', $row->status);
        $this->assertEquals(-2.1, (float) $row->zs_tb_u);
    }
}
