<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Roles
        $roleGizi = Role::create(['name' => 'Divisi Gizi']);
        $rolePencegahan = Role::create(['name' => 'Divisi Pencegahan']);
        $rolePenanggulangan = Role::create(['name' => 'Divisi Penanggulangan']);
        $roleDesa = Role::create(['name' => 'Divisi Desa']);
        $roleCamat = Role::create(['name' => 'Camat']);
        $roleAdmin = Role::create(['name' => 'IT Admin']);

        // Buat User untuk setiap role
        $userGizi = User::create([
            'name' => 'User Gizi',
            'email' => 'gizi@test.com',
            'password' => Hash::make('123456')
        ]);
        $userGizi->assignRole($roleGizi);

        $userPencegahan = User::create([
            'name' => 'User Pencegahan',
            'email' => 'pencegahan@test.com',
            'password' => Hash::make('123456')
        ]);
        $userPencegahan->assignRole($rolePencegahan);

        $userPenanggulangan = User::create([
            'name' => 'User Penanggulangan',
            'email' => 'penanggulangan@test.com',
            'password' => Hash::make('123456')
        ]);
        $userPenanggulangan->assignRole($rolePenanggulangan);

        $userDesa = User::create([
            'name' => 'User Desa',
            'email' => 'desa@test.com',
            'password' => Hash::make('123456')
        ]);
        $userDesa->assignRole($roleDesa);

        $userCamat = User::create([
            'name' => 'User Camat',
            'email' => 'camat@test.com',
            'password' => Hash::make('123456')
        ]);
        $userCamat->assignRole($roleCamat);

        $userAdmin = User::create([
            'name' => 'User IT Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456')
        ]);
        $userAdmin->assignRole($roleAdmin);
    }
}