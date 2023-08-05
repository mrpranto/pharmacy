<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserServices extends BaseServices
{
    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.user.create'),
                'edit' => auth()->user()->can('app.user.edit'),
                'show' => auth()->user()->can('app.user.show'),
                'delete' => auth()->user()->can('app.user.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUsers(): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->when(request()->filled('search'), function ($q){
                $q->where('name', 'like', '%'.request()->get('search').'%')
                    ->orWhere('email', 'like', '%'.request()->get('search').'%')
                    ->orWhere('phone_number', 'like', '%'.request()->get('search').'%');
            })->paginate(pagination());
    }
}
