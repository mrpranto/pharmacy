<?php

namespace Database\Seeders;

use App\Models\People\Customer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class LiveAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
                "time_format" => "h:i:s A",
                "currency_symbol" => '৳',
                "currency_symbol_position" => "before_with_space_amount",
                "pagination" => "10",
            ]
        ]);

        Setting::query()->updateOrCreate([
            'type' => 'notification',
            'settings_info' => [
                "notification_sound" => "on",
                "notification_show_position" => "topRight",
            ]
        ]);

        Setting::query()->updateOrCreate([
            'type' => 'system',
            'settings_info' => [
                "pos_design" => "list_design",
                "variant" => "no",
                "opening_stock" => "no",
            ]
        ]);

        $this->command->info('General Setting created successful.');

        $this->command->info('Seeder successfully end.');
    }
}
