<?php

namespace Database\Seeders;

use App\Models\People\Customer;
use App\Models\People\Supplier;
use App\Models\Permission;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseProduct;
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

        $this->command->info('Super admin permission set successful.');

        User::query()->updateOrCreate([
            'role_id' => Role::query()->first()->id,
            'name' => 'Pranto',
            'email' => 'prantoabir1@gmail.com',
            'phone_number' => '01710750665',
            'password' => bcrypt('11223344'),
        ]);

        User::query()->updateOrCreate([
            'role_id' => Role::query()->first()->id,
            'name' => 'Feroz Sarkar',
            'email' => 'ferozsarker28@gmail.com',
            'phone_number' => '01718143428',
            'password' => bcrypt('feroz@112233'),
        ]);

        $this->command->info('Super admin user created successful.');

        Customer::query()->updateOrCreate([
            'name' => 'Walk-In',
        ]);

        $this->command->info('Walk in customer created successful.');

        Role::factory(100)->create();

        $this->command->info('Role seeder created successful.');

        User::factory(100)->create();

        $this->command->info('User seeder created successful.');

        Category::factory(200)->create();

        $this->command->info('Category seeder created successful.');

        Company::factory(200)->create();

        $this->command->info('Company seeder created successful.');

        Unit::factory(200)->create();

        $this->command->info('Unit seeder created successful.');

        Product::factory(1000)->create();

        $this->command->info('Product seeder created successful.');

        Supplier::factory(100)->create();

        $this->command->info('Supplier seeder created successful.');

        Customer::factory(100)->create();

        $this->command->info('Customer seeder created successful.');

        Purchase::factory(200)->create();

        PurchaseProduct::factory(1200)->create();

        $this->command->info('Purchase seeder created successful.');

        Setting::query()->updateOrCreate([
            'type' => 'app_setting',
            'settings_info' => [
                "name" => "Chief Drug Agency",
                "email" => "ferozsarker28@gmail.com",
                "mobile" => '+88001718143428',
                "address" => "Thana Road ,Bot tola,Pirgonj,Rangpur",
            ]
        ]);

        $this->command->info('App Setting created successful.');

        Setting::query()->updateOrCreate([
            'type' => 'general',
            'settings_info' => [
                "date_format" => "F d, Y",
                "time_format" => "H:i:s A",
                "currency_symbol" => '৳',
                "currency_symbol_position" => "before_amount",
                "pagination" => "10",
                "notification_sound" => "on",
                "notification_show_position" => "topRight",
            ]
        ]);

        $this->command->info('General Setting created successful.');

        $this->command->info('Seeder successfully end.');
    }
}
