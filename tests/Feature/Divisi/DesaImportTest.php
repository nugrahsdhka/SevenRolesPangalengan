<?php

namespace Tests\Feature\Divisi;

use App\Imports\DataDesaImport;
use App\Models\DataDesa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Import Divisi Desa: nama desa harus unik per PERIODE (bukan global),
 * sama seperti form manual di DivisiDesaController.
 */
class DesaImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('Divisi Desa', 'web');
    }

    private function actor(): User
    {
        return User::factory()->create()->assignRole('Divisi Desa');
    }

    /** @param array<int, array{0:string,1:string}> $rows [periode, nama_desa] */
    private function csvFile(array $rows): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'desa');
        $fh   = fopen($path, 'w');

        fputcsv($fh, ['periode', 'nama_desa', 'jumlah_penduduk', 'luas_wilayah', 'potensi_desa', 'jumlah_kasus_stunting']);
        foreach ($rows as [$periode, $nama]) {
            fputcsv($fh, [$periode, $nama, 1000, 250, 'Teh', 3]);
        }
        fclose($fh);

        return new UploadedFile($path, 'desa.csv', 'text/csv', null, true);
    }

    private function seedExisting(User $user, string $periode, string $nama): void
    {
        DataDesa::create([
            'user_id'         => $user->id,
            'periode'         => $periode . '-01',
            'nama_desa'       => $nama,
            'jumlah_penduduk' => 1,
            'luas_wilayah'    => 1,
            'potensi_desa'    => 'x',
            'status'          => 'pending',
        ]);
    }

    public function test_same_village_in_a_different_period_can_be_imported(): void
    {
        $actor = $this->actor();
        $this->seedExisting($actor, '2026-01', 'Margamukti');

        $this->actingAs($actor);
        Excel::import(new DataDesaImport(), $this->csvFile([['2026-02', 'Margamukti']]));

        $this->assertSame(2, DataDesa::where('nama_desa', 'Margamukti')->count());
    }

    public function test_same_village_same_period_by_same_division_is_rejected(): void
    {
        $actor = $this->actor();
        $peer  = $this->actor();
        $this->seedExisting($peer, '2026-01', 'Margamukti');

        $this->actingAs($actor);

        $this->expectException(ValidationException::class);
        Excel::import(new DataDesaImport(), $this->csvFile([['2026-01', 'margamukti']]));
    }

    public function test_duplicate_village_and_period_inside_one_file_is_rejected(): void
    {
        $this->actingAs($this->actor());

        $this->expectException(ValidationException::class);
        Excel::import(new DataDesaImport(), $this->csvFile([
            ['2026-03', 'Warnasari'],
            ['2026-03', 'Warnasari'],
        ]));
    }
}
