<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->permissions() as $key => $permission) {
            Permission::query()->updateOrCreate($permission);
        }
    }

    /**
     * @return array[]
     */
    private function permissions(): array
    {
        return [
            [
                'module_id' => $this->module('Admin Dashboard'),
                'name' => 'Access Dashboard',
                'slug' => 'app.dashboard'
            ],
            [
                'module_id' => $this->module('Users'),
                'name' => 'User List',
                'slug' => 'app.user.index'
            ],
            [
                'module_id' => $this->module('Roles'),
                'name' => 'Role List',
                'slug' => 'app.roles.index'
            ],
        ];
    }

    /**
     * @param $name
     * @return mixed
     */
    private function module($name): mixed
    {
        return Module::query()->updateOrCreate([
            'name' => $name
        ])->id;
    }
}
