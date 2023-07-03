<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*User::create([
            'name' => 'Administador',
            'email' => 'stornblood6969@gmail.com',
            'is_admin' => true,
        ]);*/
        \App\Models\User::factory(1)->create([
            'name' => 'Administador',
            'email' => 'stornblood6969@gmail.com',
            'is_admin' => true,
        ]);

        $this->call([
            RoleSeeder::class,
        ]);
    }
}
