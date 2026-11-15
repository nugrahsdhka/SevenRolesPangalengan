<?php

namespace Tests\Feature\Camat;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Regresi: parameter `period` di dashboard Camat dulu disambung mentah ke SQL.
 * Sekarang harus divalidasi (Y-m) dan dikirim lewat parameter binding.
 */
class CamatPeriodInjectionTest extends TestCase
{
    use RefreshDatabase;

    private const PAYLOAD = "2026-01' OR '1'='1";

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('Camat', 'web');

        $this->registerMysqlFunctionsForSqlite();
    }

    /**
     * Query produksi memakai fungsi khusus MySQL; test berjalan di SQLite.
     * Emulasi minimal supaya halaman bisa dirender saat test.
     */
    private function registerMysqlFunctionsForSqlite(): void
    {
        $connection = DB::connection();

        if ($connection->getDriverName() !== 'sqlite') {
            return;
        }

        $pdo = $connection->getPdo();

        $pdo->sqliteCreateFunction('DATE_FORMAT', function ($date, $format) {
            if ($date === null) {
                return null;
            }

            return date(strtr($format, ['%Y' => 'Y', '%m' => 'm', '%d' => 'd']), strtotime($date));
        }, 2);

        $pdo->sqliteCreateFunction('FIELD', function (...$args) {
            $needle = array_shift($args);
            $pos = array_search($needle, $args, true);

            return $pos === false ? 0 : $pos + 1;
        }, -1);
    }

    private function camat(): User
    {
        return User::factory()->create()->assignRole('Camat');
    }

    public function test_malicious_period_is_ignored_and_never_reaches_sql(): void
    {
        DB::enableQueryLog();

        $this->actingAs($this->camat())
            ->get(route('camat.review', ['period' => self::PAYLOAD]))
            ->assertOk();

        $stored = session('camat_gizi_period');
        $this->assertMatchesRegularExpression('/^\d{4}-(0[1-9]|1[0-2])$/', $stored);
        $this->assertNotSame(self::PAYLOAD, $stored);

        foreach (DB::getQueryLog() as $query) {
            $this->assertStringNotContainsString("OR '1'='1", $query['query']);
            $this->assertStringNotContainsString("2026-01'", $query['query']);
        }
    }

    public function test_poisoned_session_value_is_ignored(): void
    {
        DB::enableQueryLog();

        $this->actingAs($this->camat())
            ->withSession(['camat_gizi_period' => self::PAYLOAD])
            ->get(route('camat.review'))
            ->assertOk();

        $this->assertMatchesRegularExpression('/^\d{4}-(0[1-9]|1[0-2])$/', session('camat_gizi_period'));

        foreach (DB::getQueryLog() as $query) {
            $this->assertStringNotContainsString("OR '1'='1", $query['query']);
        }
    }

    public function test_valid_period_is_still_honoured(): void
    {
        $this->actingAs($this->camat())
            ->get(route('camat.review', ['period' => '2025-11']))
            ->assertOk();

        $this->assertSame('2025-11', session('camat_gizi_period'));
    }

    public function test_status_actions_reject_malformed_period(): void
    {
        $camat = $this->camat();

        $this->actingAs($camat)
            ->post(route('camat.status'), [
                'model'          => 'DataGizi',
                'new_status'     => 'approved',
                'active_tab'     => 'gizi',
                'desa_kelurahan' => 'PANGALENGAN',
                'period'         => self::PAYLOAD,
            ])
            ->assertSessionHasErrors('period');

        $this->actingAs($camat)
            ->post(route('camat.bulk_status'), [
                'model_type' => 'gizi',
                'action'     => 'approve_all',
                'active_tab' => 'gizi',
                'period'     => 'bukan-periode',
            ])
            ->assertSessionHasErrors('period');
    }
}
