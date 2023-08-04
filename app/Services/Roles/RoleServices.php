<?php

namespace App\Services\Roles;

use App\Models\Module;
use App\Models\Role;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
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
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%'))
            ->orderBy('id', 'desc')
            ->paginate(request()->get('per_page') ?? pagination());
    }

    /**
     * Getting permissions.
     *
     * @return Collection|array
     */
    public function permissions(): Collection|array
    {
        return Module::query()->with('permission')->get();
    }

    public function validateStore(): static
    {
        request()->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'required',
            'permissions' => 'required|array'
        ]);

        return $this;
    }

    /**
     * Add new role.
     *
     * @param $request
     * @return JsonResponse
     */
    public function store($request): JsonResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $this->model
                    ->newQuery()
                    ->updateOrCreate([
                        'name' => $request->name,
                        'description' => $request->description,
                    ])
                    ->permissions()
                    ->sync($request->permissions);
            });

            return response()->json(['success' => __t('role_create')]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
