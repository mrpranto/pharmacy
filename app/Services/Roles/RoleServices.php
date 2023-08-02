<?php

namespace App\Services\Roles;

use App\Models\Role;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RoleServices extends BaseServices
{
    /**
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getRoles(): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%'.request()->get('search').'%'))
            ->paginate(request()->get('per_page') ?? pagination());
    }
}
