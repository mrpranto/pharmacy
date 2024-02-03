<?php

namespace App\Http\Composer;

class SubMenuComposer
{
    /**
     * @return array[]
     */
    public function users(): array
    {
        return [
            [
                'name' => __t('roles'),
                'url' => route('roles.index'),
                'path' => 'roles',
                'permission' => auth()->user()->can('app.roles.index')
            ],
            [
                'name' => __t('users'),
                'url' => route('users.index'),
                'path' => 'users',
                'permission' => auth()->user()->can('app.user.index')
            ]
        ];
    }
    /**
     * @return array[]
     */
    public function peoples(): array
    {
        return [
            [
                'name' => __t('suppliers'),
                'url' => route('suppliers.index'),
                'path' => 'peoples/suppliers',
                'permission' => auth()->user()->can('app.supplier.index')
            ],
            [
                'name' => __t('customers'),
                'url' => route('customers.index'),
                'path' => 'peoples/customers',
                'permission' => auth()->user()->can('app.customer.index')
            ],
        ];
    }
    /**
     * @return array[]
     */
    public function purchases(): array
    {
        return [
            [
                'name' => __t('add_purchase'),
                'url' => route('purchases.create'),
                'path' => 'purchases/create',
                'permission' => auth()->user()->can('app.purchase.create')
            ],
            [
                'name' => __t('purchase_list'),
                'url' => route('purchases.index'),
                'path' => ['purchases', 'purchases/*/edit', 'purchase-print'],
                'permission' => auth()->user()->can('app.purchase.index')
            ],
        ];
    }
    /**
     * @return array[]
     */
    public function sales(): array
    {
        return [
            [
                'name' => __t('add_sale'),
                'url' => route('sales.create'),
                'path' => 'sales/create',
                'permission' => auth()->user()->can('app.sales.create')
            ],
            [
                'name' => __t('sales_list'),
                'url' => route('sales.index'),
                'path' => ['sales', 'sales/*/edit'],
                'permission' => auth()->user()->can('app.sales.index')
            ],
        ];
    }
    public function reports(): array
    {
        return [
            [
                'name' => __t('summary'),
                'url' => route('report.summary'),
                'path' => 'report/summary',
                'permission' => auth()->user()->can('app.report.summary')
            ],
            [
                'name' => __t('purchase_report'),
                'url' => route('report.purchase'),
                'path' => 'report/purchase',
                'permission' => auth()->user()->can('app.report.purchase')
            ],
            [
                'name' => __t('sales_report'),
                'url' => route('report.sale'),
                'path' => 'report/sale',
                'permission' => auth()->user()->can('app.report.sales')
            ],
            [
                'name' => __t('payment'),
                'url' => route('report.payment'),
                'path' => 'report/payment',
                'permission' => auth()->user()->can('app.report.sales')
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function products(): array
    {
        return [
            [
                'name' => __t('products'),
                'url' => route('products.index'),
                'path' => 'product/products',
                'permission' => auth()->user()->can('app.product.index')
            ],
            [
                'name' => __t('categories'),
                'url' => route('categories.index'),
                'path' => 'product/categories',
                'permission' => auth()->user()->can('app.category.index')
            ],
            [
                'name' => __t('companies'),
                'url' => route('companies.index'),
                'path' => 'product/companies',
                'permission' => auth()->user()->can('app.company.index')
            ],
            [
                'name' => __t('units'),
                'url' => route('units.index'),
                'path' => 'product/units',
                'permission' => auth()->user()->can('app.unit.index')
            ],
            [
                'name' => __t('attributes'),
                'url' => route('attributes.index'),
                'path' => 'product/attributes',
                'permission' => auth()->user()->can('app.unit.index')
            ],
        ];
    }
}
