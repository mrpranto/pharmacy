<?php

namespace Database\Seeders;

use App\Models\Permission;
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
        $this->call(PermissionTableSeeder::class);

        $permissions = Permission::query()->pluck('id');

        $super_admin_roles = [
            'name' => 'Super Admin',
            'description' => 'This is an admin role it\'s not delete able',
            'is_delete_able' => false
        ];

        Role::query()
            ->updateOrCreate($super_admin_roles)
            ->permissions()
            ->sync($permissions);

        User::query()->create([
            'role_id' => Role::query()->first()->id,
            'name' => 'Pranto',
            'email' => 'prantoabir1@gmail.com',
            'phone_number' => '01710750665',
            'password' => bcrypt('11223344'),
        ]);

    }
}
