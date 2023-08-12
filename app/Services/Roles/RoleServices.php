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
     * @return array
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.roles.create'),
                'edit' => auth()->user()->can('app.roles.edit'),
                'delete' => auth()->user()->can('app.roles.delete')
            ]
        ];
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
            ->with(['permissions'])
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
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
                        'is_delete_able' => $request->isDeleteAble
                    ])
                    ->permissions()
                    ->sync($request->permissions);
            });

            return response()->json(['success' => __t('role_create')]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @param $id
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$id,
            'description' => 'required',
            'permissions' => 'required|array'
        ]);

        return $this;
    }

    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function update($request, $id): JsonResponse
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $role = $this->model
                    ->newQuery()
                    ->where('id', $id)
                    ->first();

                    $role->update([
                        'name' => $request->name,
                        'description' => $request->description,
                        'is_delete_able' => $request->isDeleteAble
                    ]);

                    $role->permissions()
                        ->sync($request->permissions);
            });

            return response()->json(['success' => __t('role_update')]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {

            $role = $this->model
                ->newQuery()
                ->where('id', $id)
                ->first();

            if ($role->is_delete_able == false){

                throw new \Exception('This role is not delete able.');

            }else{

                $role->permissions()->detach();

                $role->delete();
            }

            return response()->json(['success' => __t('role_delete_successful')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
