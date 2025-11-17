<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ====== BUAT ROLES (AMAN DI-RUN BERKALI-KALI) ======
        $roleGizi          = Role::firstOrCreate(['name' => 'Divisi Gizi',           'guard_name' => 'web']);
        $rolePencegahan    = Role::firstOrCreate(['name' => 'Divisi Pencegahan',     'guard_name' => 'web']);
        $rolePenanggulangan= Role::firstOrCreate(['name' => 'Divisi Penanggulangan', 'guard_name' => 'web']);
        $roleDesa          = Role::firstOrCreate(['name' => 'Divisi Desa',           'guard_name' => 'web']);
        $roleCamat         = Role::firstOrCreate(['name' => 'Camat',                 'guard_name' => 'web']);
        $roleAdmin         = Role::firstOrCreate(['name' => 'IT Admin',              'guard_name' => 'web']);

        // ====== BUAT / UPDATE USER UNTUK SETIAP ROLE ======

        // Divisi Gizi
        $userGizi = User::updateOrCreate(
            ['email' => 'gizi@test.com'],
            [
                'name'              => 'User Gizi',
                'password'          => Hash::make('gizi123456'),
                'email_verified_at' => now(),
            ]
        );
        $userGizi->syncRoles([$roleGizi->name]);

        // Divisi Pencegahan
        $userPencegahan = User::updateOrCreate(
            ['email' => 'pencegahan@test.com'],
            [
                'name'              => 'User Pencegahan',
                'password'          => Hash::make('pencegahan123456'),
                'email_verified_at' => now(),
            ]
        );
        $userPencegahan->syncRoles([$rolePencegahan->name]);

        // Divisi Penanggulangan
        $userPenanggulangan = User::updateOrCreate(
            ['email' => 'penanggulangan@test.com'],
            [
                'name'              => 'User Penanggulangan',
                'password'          => Hash::make('penanggulangan123456'),
                'email_verified_at' => now(),
            ]
        );
        $userPenanggulangan->syncRoles([$rolePenanggulangan->name]);

        // Divisi Desa
        $userDesa = User::updateOrCreate(
            ['email' => 'desa@test.com'],
            [
                'name'              => 'User Desa',
                'password'          => Hash::make('desa123456'),
                'email_verified_at' => now(),
            ]
        );
        $userDesa->syncRoles([$roleDesa->name]);

        // Camat
        $userCamat = User::updateOrCreate(
            ['email' => 'camat@test.com'],
            [
                'name'              => 'User Camat',
                'password'          => Hash::make('camat123456'),
                'email_verified_at' => now(),
            ]
        );
        $userCamat->syncRoles([$roleCamat->name]);

        // IT Admin
        $userAdmin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'              => 'User IT Admin',
                'password'          => Hash::make('admin123456'),
                'email_verified_at' => now(),
            ]
        );
        $userAdmin->syncRoles([$roleAdmin->name]);
    }
}
