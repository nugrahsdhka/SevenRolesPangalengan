<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PengaduanSampahTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, ?string $puskesmas = null): User
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

        $user = User::factory()->create(['email_verified_at' => now(), 'puskesmas' => $puskesmas]);
        $user->assignRole($role);

        return $user;
    }

    private function complaint(array $override = []): Complaint
    {
        return Complaint::create(array_merge([
            'ticket_code'    => Complaint::generateTicketCode(),
            'puskesmas'      => 'WARNASARI',
            'desa_kelurahan' => 'WARNASARI',
            'posyandu'       => 'MAWAR 1',
            'kategori'       => 'layanan_kesehatan',
            'judul'          => 'Posyandu jarang buka',
            'isi'            => 'Posyandu di RW 05 sudah dua bulan tidak buka sehingga balita tidak bisa ditimbang.',
            'status'         => 'selesai',
            'status_diperbarui_at' => now(),
        ], $override));
    }

    public function test_finished_complaint_can_be_trashed_and_leaves_main_list_but_guest_can_still_track(): void
    {
        $c = $this->complaint();
        $admin = $this->user('IT Admin');

        $this->actingAs($admin)->patch(route('complaints.trash.move', $c))
            ->assertRedirect(route('complaints.index'));

        $c->refresh();
        $this->assertNotNull($c->dibuang_at);
        $this->assertSame($admin->id, $c->dibuang_oleh);

        $this->actingAs($admin)->get(route('complaints.index'))->assertDontSee($c->ticket_code);
        $this->actingAs($admin)->get(route('complaints.trash'))->assertOk()->assertSee($c->ticket_code);

        auth()->logout();
        $this->get(route('pengaduan.track', ['kode' => $c->ticket_code]))
            ->assertOk()->assertSee($c->judul);
    }

    public function test_unfinished_complaint_cannot_be_trashed(): void
    {
        $c = $this->complaint(['status' => 'diproses']);

        $this->actingAs($this->user('IT Admin'))->patch(route('complaints.trash.move', $c))
            ->assertSessionHas('error');

        $this->assertNull($c->fresh()->dibuang_at);
    }

    public function test_camat_and_own_puskesmas_staff_can_trash_but_other_roles_cannot(): void
    {
        $camat = $this->user('Camat');
        $c1 = $this->complaint();
        $this->actingAs($camat)->patch(route('complaints.trash.move', $c1));
        $this->assertNotNull($c1->fresh()->dibuang_at);

        $c2 = $this->complaint();
        $ownStaff = $this->user('Divisi Gizi', 'WARNASARI');
        $this->actingAs($ownStaff)->patch(route('complaints.trash.move', $c2));
        $this->assertNotNull($c2->fresh()->dibuang_at);

        $c3 = $this->complaint();
        $otherStaff = $this->user('Divisi Gizi', 'SUKAMANAH');
        $this->actingAs($otherStaff)->patch(route('complaints.trash.move', $c3))->assertForbidden();
        $this->assertNull($c3->fresh()->dibuang_at);

        $viewOnly = $this->user('Divisi Desa', 'WARNASARI');
        $this->actingAs($viewOnly)->patch(route('complaints.trash.move', $c3))->assertForbidden();
        $this->actingAs($viewOnly)->get(route('complaints.trash'))->assertForbidden();
    }

    public function test_force_delete_requires_complaint_in_trash(): void
    {
        $admin = $this->user('IT Admin');
        $c = $this->complaint();

        $this->actingAs($admin)->delete(route('complaints.trash.destroy', $c))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('complaints', ['id' => $c->id]);

        $c->forceFill(['dibuang_at' => now()])->save();
        $this->actingAs($admin)->delete(route('complaints.trash.destroy', $c))
            ->assertRedirect(route('complaints.trash'));
        $this->assertDatabaseMissing('complaints', ['id' => $c->id]);
    }

    public function test_restore_brings_complaint_back(): void
    {
        $c = $this->complaint(['dibuang_at' => now()]);

        $this->actingAs($this->user('Camat'))->patch(route('complaints.trash.restore', $c));

        $this->assertNull($c->fresh()->dibuang_at);
    }

    public function test_status_change_sets_status_diperbarui_at(): void
    {
        $c = $this->complaint(['status' => 'baru', 'status_diperbarui_at' => null]);
        $staff = $this->user('Divisi Gizi', 'WARNASARI');

        $this->actingAs($staff)->patch(route('complaints.update', $c), ['status' => 'selesai', 'tanggapan' => 'Sudah beres.']);

        $this->assertNotNull($c->fresh()->status_diperbarui_at);
    }

    public function test_purge_command_deletes_only_expired_final_complaints(): void
    {
        $old       = $this->complaint(['status_diperbarui_at' => now()->subMonths(3)->subDay()]);
        $oldTrash  = $this->complaint(['status_diperbarui_at' => now()->subMonths(4), 'dibuang_at' => now()]);
        $recent    = $this->complaint(['status_diperbarui_at' => now()->subMonths(2)]);
        $oldActive = $this->complaint(['status' => 'diproses', 'status_diperbarui_at' => now()->subMonths(6)]);

        $this->artisan('complaints:purge-expired')->assertSuccessful();

        $this->assertDatabaseMissing('complaints', ['id' => $old->id]);
        $this->assertDatabaseMissing('complaints', ['id' => $oldTrash->id]);
        $this->assertDatabaseHas('complaints', ['id' => $recent->id]);
        $this->assertDatabaseHas('complaints', ['id' => $oldActive->id]);
    }
}
