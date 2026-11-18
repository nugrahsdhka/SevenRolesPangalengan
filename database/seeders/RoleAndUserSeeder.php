<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // VALIDASI KONFIGURASI SEEDER
        // ==========================================

        // Nama file konfigurasi: config/seeder.php  → kunci "seeder"
        $accounts = config('seeder.accounts', []);

        if (empty($accounts)) {
            throw new RuntimeException(
                "Konfigurasi 'seeder.accounts' tidak ditemukan. Pastikan config/seeder.php ada."
            );
        }

        foreach ($accounts as $key => $account) {
            if (
                empty($account['email']) ||
                empty($account['password'])
            ) {
                throw new RuntimeException(
                    "Konfigurasi akun '{$key}' belum lengkap. Periksa file .env."
                );
            }
        }

        // ==========================================
        // BUAT ROLES
        // ==========================================

        $roleGizi = Role::firstOrCreate([
            'name' => 'Divisi Gizi',
            'guard_name' => 'web',
        ]);

        $rolePencegahan = Role::firstOrCreate([
            'name' => 'Divisi Pencegahan',
            'guard_name' => 'web',
        ]);

        $rolePenanggulangan = Role::firstOrCreate([
            'name' => 'Divisi Penanggulangan',
            'guard_name' => 'web',
        ]);

        $roleDesa = Role::firstOrCreate([
            'name' => 'Divisi Desa',
            'guard_name' => 'web',
        ]);

        $roleCamat = Role::firstOrCreate([
            'name' => 'Camat',
            'guard_name' => 'web',
        ]);

        $roleAdmin = Role::firstOrCreate([
            'name' => 'IT Admin',
            'guard_name' => 'web',
        ]);

        // ==========================================
        // BUAT / AMBIL USER
        // ==========================================

        $this->createUser(
            'gizi_pangalengan',
            'Puskesmas Pangalengan',
            $roleGizi,
            'PANGALENGAN DTP'
        );

        $this->createUser(
            'gizi_warnasari',
            'Puskesmas Warnasari',
            $roleGizi,
            'WARNASARI'
        );

        $this->createUser(
            'gizi_sukamanah',
            'Puskesmas Sukamanah',
            $roleGizi,
            'SUKAMANAH'
        );

        // Divisi Pencegahan dan Penanggulangan
        // Divisi Desa
        // Role sudah dibuat, akun dapat ditambahkan sesuai kebutuhan.

        $this->createUser(
            'camat',
            'Camat Pangalengan',
            $roleCamat
        );

        $this->createUser(
            'admin',
            'Admin RSP',
            $roleAdmin
        );

        // Reset permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Create user if not exists and synchronize role.
     */
    private function createUser(
        string $key,
        string $name,
        Role $role,
        ?string $puskesmas = null
    ): User {
        $account = config("seeder.accounts.{$key}");

        $user = User::firstOrCreate(
            ['email' => $account['email']],
            [
                'name' => $name,
                'password' => Hash::make($account['password']),
                'email_verified_at' => now(),
            ]
        );

        if ($puskesmas) {
            $user->forceFill(['puskesmas' => $puskesmas])->save();
        }

        $user->syncRoles([$role]);

        return $user;
    }
}