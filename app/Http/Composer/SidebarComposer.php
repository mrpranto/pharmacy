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
                    'name' => __t('users'),
                    'icon' => 'users',
                    'permission' => auth()->user()->canany(['app.user.index', 'app.roles.index']),
                    'subMenu'    => $this->subMenu->users(),
                    'id' => 'users',
                ],
                [
                    'name' => __t('products'),
                    'icon' => 'briefcase',
                    'permission' => auth()->user()->canany(['app.user.index', 'app.roles.index']),
                    'subMenu'    => $this->subMenu->products(),
                    'id' => 'products',
                ],
            ]
        ]);
    }

}
