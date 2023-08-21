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
                'url' => route('roles.index'),
                'path' => 'roles',
                'permission' => auth()->user()->can('app.roles.index')
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
        ];
    }
}
