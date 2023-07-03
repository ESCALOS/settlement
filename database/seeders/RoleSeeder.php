<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create([
            'name' => 'ADMINISTRADOR',
            'guard_name' => 'web',
        ]);

        Role::create([
            'name' => 'COLABORADOR',
            'guard_name' => 'web',
        ]);

        User::find(1)->assignRole($admin);
    }
}
