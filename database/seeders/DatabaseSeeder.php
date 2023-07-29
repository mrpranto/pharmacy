<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [[
                'name' => 'Admin',
                'description' => 'This is an admin role it\'s not delete able',
                'is_delete_able' => false
            ]];

        foreach ($roles as $role){
            Role::query()->create($role);
        }

        User::query()->create([
            'role_id' => Role::query()->first()->id,
            'name' => 'Pranto',
            'email' => 'prantoabir1@gmail.com',
            'phone_number' => '01710750665',
            'password' => bcrypt('11223344'),
        ]);
    }
}
