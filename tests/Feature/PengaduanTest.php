<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\Faskes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PengaduanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Data wilayah contoh: puskesmas -> desa -> posyandu
        foreach ([
            ['WARNASARI', 'WARNASARI', 'MAWAR 1'],
            ['WARNASARI', 'SUKALUYU', 'ASTER 1'],
            ['SUKAMANAH', 'SUKAMANAH', 'MUTIARA 01'],
        ] as [$puskesmas, $desa, $nama]) {
            Faskes::create([
                'nama_faskes'    => $nama,
                'jenis'          => 'Posyandu',
                'desa_kelurahan' => $desa,
                'puskesmas'      => $puskesmas,
                'lat'            => -7.2,
                'lng'            => 107.5,
                'is_active'      => true,
            ]);
        }
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'nama'           => 'Warga Contoh',
            'kontak'         => '081234567890',
            'puskesmas'      => 'WARNASARI',
            'desa_kelurahan' => 'WARNASARI',
            'posyandu'       => 'MAWAR 1',
            'kategori'       => 'layanan_kesehatan',
            'judul'          => 'Posyandu jarang buka',
            'isi'            => 'Posyandu di RW 05 sudah dua bulan tidak buka sehingga balita tidak bisa ditimbang.',
        ], $override);
    }

    private function userWithRole(string $role): User
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

        $user = User::factory()->create(['email_verified_at' => now()]);
        $user->assignRole($role);

        return $user;
    }

    public function test_guest_can_open_form_and_submit_complaint(): void
    {
        $this->get(route('pengaduan.create'))
            ->assertOk()
            ->assertSee('Pilih puskesmas')
            ->assertSee('SUKAMANAH')
            ->assertSee('MUTIARA 01');

        $response = $this->post(route('pengaduan.store'), $this->payload());

        $complaint = Complaint::firstOrFail();
        $this->assertMatchesRegularExpression('/^PGD-[A-Z2-9]{8}$/', $complaint->ticket_code);
        $this->assertSame('baru', $complaint->status);
        $this->assertSame('WARNASARI', $complaint->puskesmas);
        $this->assertSame('WARNASARI', $complaint->desa_kelurahan);
        $this->assertSame('MAWAR 1', $complaint->posyandu);
        $response->assertRedirect(route('pengaduan.track', ['kode' => $complaint->ticket_code]));
    }

    public function test_complaint_can_be_anonymous(): void
    {
        $this->post(route('pengaduan.store'), $this->payload(['nama' => null, 'kontak' => null]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('complaints', 1);
    }

    public function test_validation_rejects_bad_input(): void
    {
        $this->post(route('pengaduan.store'), $this->payload(['kategori' => 'ngawur', 'isi' => 'pendek']))
            ->assertSessionHasErrors(['kategori', 'isi']);

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_puskesmas_desa_and_posyandu_are_required(): void
    {
        $this->post(route('pengaduan.store'), $this->payload([
            'puskesmas' => null, 'desa_kelurahan' => null, 'posyandu' => null,
        ]))->assertSessionHasErrors(['puskesmas', 'desa_kelurahan', 'posyandu']);

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_posyandu_must_belong_to_selected_desa(): void
    {
        // ASTER 1 ada di desa SUKALUYU, bukan WARNASARI
        $this->post(route('pengaduan.store'), $this->payload(['posyandu' => 'ASTER 1']))
            ->assertSessionHasErrors('posyandu');

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_desa_must_belong_to_selected_puskesmas(): void
    {
        // Desa WARNASARI ada di bawah puskesmas WARNASARI, bukan SUKAMANAH
        $this->post(route('pengaduan.store'), $this->payload(['puskesmas' => 'SUKAMANAH']))
            ->assertSessionHasErrors('posyandu');

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_inactive_posyandu_cannot_be_chosen(): void
    {
        Faskes::where('nama_faskes', 'MAWAR 1')->update(['is_active' => false]);

        $this->post(route('pengaduan.store'), $this->payload())
            ->assertSessionHasErrors('posyandu');

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_honeypot_blocks_bots_silently(): void
    {
        $this->post(route('pengaduan.store'), $this->payload(['website' => 'http://spam.test']))
            ->assertRedirect(route('pengaduan.create'));

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_guest_can_track_by_ticket_without_seeing_personal_data(): void
    {
        $this->post(route('pengaduan.store'), $this->payload());
        $complaint = Complaint::firstOrFail();

        $this->get(route('pengaduan.track', ['kode' => strtolower($complaint->ticket_code)]))
            ->assertOk()
            ->assertSee($complaint->judul)
            ->assertDontSee('Warga Contoh')
            ->assertDontSee('081234567890');

        $this->get(route('pengaduan.track', ['kode' => 'PGD-TIDAKADA']))
            ->assertOk()
            ->assertSee('tidak ditemukan');
    }

    public function test_guest_cannot_access_staff_pages(): void
    {
        $this->get(route('complaints.index'))->assertRedirect(route('login'));
    }

    public function test_any_internal_role_can_view_but_only_admin_can_manage(): void
    {
        $complaint = Complaint::create($this->payload() + [
            'ticket_code' => Complaint::generateTicketCode(),
            'status'      => 'baru',
        ]);

        $camat = $this->userWithRole('Camat');
        $this->actingAs($camat)->get(route('complaints.index'))->assertOk();
        $this->actingAs($camat)->get(route('complaints.show', $complaint))->assertOk();
        $this->actingAs($camat)
            ->patch(route('admin.complaints.update', $complaint), ['status' => 'selesai'])
            ->assertForbidden();
        $this->actingAs($camat)
            ->delete(route('admin.complaints.destroy', $complaint))
            ->assertForbidden();

        $admin = $this->userWithRole('IT Admin');
        $this->actingAs($admin)
            ->patch(route('admin.complaints.update', $complaint), [
                'status'    => 'diproses',
                'tanggapan' => 'Sedang dikoordinasikan dengan puskesmas.',
            ])
            ->assertRedirect(route('complaints.show', $complaint));

        $complaint->refresh();
        $this->assertSame('diproses', $complaint->status);
        $this->assertSame($admin->id, $complaint->ditangani_oleh);
        $this->assertNotNull($complaint->ditanggapi_at);
    }
}
