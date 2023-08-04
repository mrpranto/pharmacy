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
                'module_id' => $this->module('Setting'),
                'name' => 'Access Setting',
                'slug' => 'app.setting'
            ],
            [
                'module_id' => $this->module('Users'),
                'name' => 'User List',
                'slug' => 'app.user.index'
            ],
            [
                'module_id' => $this->module('Users'),
                'name' => 'Add New User',
                'slug' => 'app.user.create'
            ],
            [
                'module_id' => $this->module('Users'),
                'name' => 'Edit User',
                'slug' => 'app.user.edit'
            ],
            [
                'module_id' => $this->module('Users'),
                'name' => 'Show User',
                'slug' => 'app.user.show'
            ],
            [
                'module_id' => $this->module('Users'),
                'name' => 'Delete User',
                'slug' => 'app.user.delete'
            ],
            [
                'module_id' => $this->module('Roles'),
                'name' => 'Role List',
                'slug' => 'app.roles.index'
            ],
            [
                'module_id' => $this->module('Roles'),
                'name' => 'Add New Role',
                'slug' => 'app.roles.create'
            ],
            [
                'module_id' => $this->module('Roles'),
                'name' => 'Edit Role',
                'slug' => 'app.roles.edit'
            ],
            [
                'module_id' => $this->module('Roles'),
                'name' => 'Show Role',
                'slug' => 'app.roles.show'
            ],
            [
                'module_id' => $this->module('Roles'),
                'name' => 'Delete Role',
                'slug' => 'app.roles.delete'
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
