<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use App\Models\Role;
use App\Models\Setting;
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

        User::query()->updateOrCreate([
            'role_id' => Role::query()->first()->id,
            'name' => 'Pranto',
            'email' => 'prantoabir1@gmail.com',
            'phone_number' => '01710750665',
            'password' => bcrypt('11223344'),
        ]);

        Role::factory(100)->create();
        User::factory(100)->create();
        Category::factory(200)->create();
        Company::factory(200)->create();
        Unit::factory(200)->create();
        Product::factory(10000)->create();

        Setting::query()->updateOrCreate([
            'type' => 'general',
            'settings_info' => [
                "date_format" => "m d, Y",
                "time_format" => "H:i:s A",
                "currency_symbol" => '৳',
                "currency_symbol_position" => "before_amount",
                "pagination" => "10",
                "notification_sound" => "on",
                "notification_show_position" => "topRight",
            ]
        ]);

        Setting::query()->updateOrCreate([
            'type' => 'app_setting',
            'settings_info' => [
                "name" => "Chief Drug Agency",
                "email" => "ferozsarker28@gmail.com",
                "mobile" => '+88001718143428',
                "address" => "Thana Road ,Bot tola,Pirgonj,Rangpur",
            ]
        ]);

    }
}
