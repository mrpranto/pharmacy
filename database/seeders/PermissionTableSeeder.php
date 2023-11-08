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


            /*
             * Role permission
             * */
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


            /*
             * Category permission
             * */
            [
                'module_id' => $this->module('Categories'),
                'name' => 'Category List',
                'slug' => 'app.category.index'
            ],
            [
                'module_id' => $this->module('Categories'),
                'name' => 'Add New Category',
                'slug' => 'app.category.create'
            ],
            [
                'module_id' => $this->module('Categories'),
                'name' => 'Edit Category',
                'slug' => 'app.category.edit'
            ],
            [
                'module_id' => $this->module('Categories'),
                'name' => 'Show Category',
                'slug' => 'app.category.show'
            ],
            [
                'module_id' => $this->module('Categories'),
                'name' => 'Delete Category',
                'slug' => 'app.category.delete'
            ],


            /*
             * Companies permission
             * */
            [
                'module_id' => $this->module('Companies'),
                'name' => 'Company List',
                'slug' => 'app.company.index'
            ],
            [
                'module_id' => $this->module('Companies'),
                'name' => 'Add New Company',
                'slug' => 'app.company.create'
            ],
            [
                'module_id' => $this->module('Companies'),
                'name' => 'Edit Company',
                'slug' => 'app.company.edit'
            ],
            [
                'module_id' => $this->module('Companies'),
                'name' => 'Show Company',
                'slug' => 'app.company.show'
            ],
            [
                'module_id' => $this->module('Companies'),
                'name' => 'Delete Company',
                'slug' => 'app.company.delete'
            ],


            /*
             * Unit permission
             * */
            [
                'module_id' => $this->module('Units'),
                'name' => 'Unit List',
                'slug' => 'app.unit.index'
            ],
            [
                'module_id' => $this->module('Units'),
                'name' => 'Add New Unit',
                'slug' => 'app.unit.create'
            ],
            [
                'module_id' => $this->module('Units'),
                'name' => 'Edit Unit',
                'slug' => 'app.unit.edit'
            ],
            [
                'module_id' => $this->module('Units'),
                'name' => 'Show Unit',
                'slug' => 'app.unit.show'
            ],
            [
                'module_id' => $this->module('Units'),
                'name' => 'Delete Unit',
                'slug' => 'app.unit.delete'
            ],

            /*
             * Product permission
             * */
            [
                'module_id' => $this->module('Products'),
                'name' => 'Product List',
                'slug' => 'app.product.index'
            ],
            [
                'module_id' => $this->module('Products'),
                'name' => 'Add New Product',
                'slug' => 'app.product.create'
            ],
            [
                'module_id' => $this->module('Products'),
                'name' => 'Edit Product',
                'slug' => 'app.product.edit'
            ],
            [
                'module_id' => $this->module('Products'),
                'name' => 'Show Product',
                'slug' => 'app.product.show'
            ],
            [
                'module_id' => $this->module('Products'),
                'name' => 'Delete Product',
                'slug' => 'app.product.delete'
            ],

            /*
             * Supplier permission
             * */
            [
                'module_id' => $this->module('Supplier'),
                'name' => 'Supplier List',
                'slug' => 'app.supplier.index'
            ],
            [
                'module_id' => $this->module('Supplier'),
                'name' => 'Add New Supplier',
                'slug' => 'app.supplier.create'
            ],
            [
                'module_id' => $this->module('Supplier'),
                'name' => 'Edit Supplier',
                'slug' => 'app.supplier.edit'
            ],
            [
                'module_id' => $this->module('Supplier'),
                'name' => 'Show Supplier',
                'slug' => 'app.supplier.show'
            ],
            [
                'module_id' => $this->module('Supplier'),
                'name' => 'Delete Supplier',
                'slug' => 'app.supplier.delete'
            ],

            /*
            * Customer permission
            * */
            [
                'module_id' => $this->module('Customer'),
                'name' => 'Customer List',
                'slug' => 'app.customer.index'
            ],
            [
                'module_id' => $this->module('Customer'),
                'name' => 'Add New Customer',
                'slug' => 'app.customer.create'
            ],
            [
                'module_id' => $this->module('Customer'),
                'name' => 'Edit Customer',
                'slug' => 'app.customer.edit'
            ],
            [
                'module_id' => $this->module('Customer'),
                'name' => 'Show Customer',
                'slug' => 'app.customer.show'
            ],
            [
                'module_id' => $this->module('Customer'),
                'name' => 'Delete Customer',
                'slug' => 'app.customer.delete'
            ],


            /*
            * Purchase permission
            * */
            [
                'module_id' => $this->module('Purchase'),
                'name' => 'Purchase List',
                'slug' => 'app.purchase.index'
            ],
            [
                'module_id' => $this->module('Purchase'),
                'name' => 'Add New Purchase',
                'slug' => 'app.purchase.create'
            ],
            [
                'module_id' => $this->module('Purchase'),
                'name' => 'Edit Purchase',
                'slug' => 'app.purchase.edit'
            ],
            [
                'module_id' => $this->module('Purchase'),
                'name' => 'Show Purchase',
                'slug' => 'app.purchase.show'
            ],
            [
                'module_id' => $this->module('Purchase'),
                'name' => 'Delete Purchase',
                'slug' => 'app.purchase.delete'
            ],

            /*
             * Stock Permission
             */
            [
                'module_id' => $this->module('Stock'),
                'name' => 'Stock List',
                'slug' => 'app.stock.index'
            ],

            /*
             * Sales Permission
             */
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Sales List',
                'slug' => 'app.sales.index'
            ],
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Add Sales',
                'slug' => 'app.sales.create'
            ],
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Show Sales',
                'slug' => 'app.sales.show'
            ],
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Edit Sales',
                'slug' => 'app.sales.edit'
            ],
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Delete Sales',
                'slug' => 'app.sales.delete'
            ],
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Change Sales Status',
                'slug' => 'app.sales.status-change'
            ],
            [
                'module_id' => $this->module('Sales'),
                'name' => 'Payment Add',
                'slug' => 'app.sales.payment-add'
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
