<?php

namespace App\Http\Composer;

class SubMenuComposer
{
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
}
