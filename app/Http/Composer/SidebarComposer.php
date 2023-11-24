<?php

namespace App\Http\Composer;


use Illuminate\View\View;

class SidebarComposer
{
    protected SubMenuComposer $subMenu;

    /**
     * @param SubMenuComposer $subMenuComposer
     */
    public function __construct(SubMenuComposer $subMenuComposer)
    {
        $this->subMenu = $subMenuComposer;
    }

    /**
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'sidebar' => [
                [
                    'name' => __t('dashboard'),
                    'icon' => 'box',
                    'url' => url('home'),
                    'path' => 'home',
                    'permission' => auth()->user()->can('app.dashboard')
                ],
                [
                    'name' => __t('products'),
                    'icon' => 'briefcase',
                    'permission' => auth()->user()->canany([
                        'app.category.index', 'app.company.index',
                        'app.unit.index', 'app.product.index'
                    ]),
                    'subMenu' => $this->subMenu->products(),
                    'id' => 'products',
                ],
                [
                    'name' => __t('peoples'),
                    'icon' => 'users',
                    'permission' => auth()->user()->canany([
                        'app.supplier.index', 'app.customer.index'
                    ]),
                    'subMenu' => $this->subMenu->peoples(),
                    'id' => 'peoples',
                ],
                [
                    'name' => __t('purchase'),
                    'icon' => 'shopping-cart',
                    'permission' => auth()->user()->canany([
                        'app.purchase.index', 'app.purchase.create'
                    ]),
                    'subMenu' => $this->subMenu->purchases(),
                    'id' => 'purchase',
                ],
                [
                    'name' => __t('stock_list'),
                    'icon' => 'database',
                    'url' => route('stocks'),
                    'path' => 'stocks',
                    'permission' => auth()->user()->can('app.dashboard')
                ],
                [
                    'name' => __t('sales'),
                    'icon' => 'shopping-bag',
                    'permission' => auth()->user()->canany([
                        'app.purchase.index', 'app.purchase.create'
                    ]),
                    'subMenu' => $this->subMenu->sales(),
                    'id' => 'sales',
                ],
                [
                    'name' => __t('expenses'),
                    'icon' => 'dollar-sign',
                    'url' => route('expanses.index'),
                    'path' => 'expanses',
                    'permission' => auth()->user()->can('app.expenses.index')
                ],
                [
                    'name' => __t('reports'),
                    'icon' => 'book-open',
                    'url' => route('expanses.index'),
                    'permission' => auth()->user()->can('app.expenses.index'),
                    'subMenu' => $this->subMenu->reports(),
                    'id' => 'reports',
                ],
                [
                    'name' => __t('users'),
                    'icon' => 'user',
                    'permission' => auth()->user()->canany(['app.user.index', 'app.roles.index']),
                    'subMenu' => $this->subMenu->users(),
                    'id' => 'users',
                ],
                [
                    'name' => __t('setting'),
                    'icon' => 'settings',
                    'url' => route('setting').'?type=app_setting',
                    'path' => 'setting',
                    'permission' => auth()->user()->can('app.setting')
                ],
            ]
        ]);
    }

}
