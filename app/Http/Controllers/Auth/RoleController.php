<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Roles\RoleServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RoleController extends Controller
{
    /**
     * @param RoleServices $roleServices
     */
    public function __construct(RoleServices $roleServices)
    {
        $this->services = $roleServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('app.roles.index');

        return view('pages.roles.index', $this->services->accessPermissions());
    }

    /**
     * Getting roles.
     *
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getRoles(): LengthAwarePaginator
    {
        Gate::authorize('app.roles.index');

        return $this->services->getRoles();
    }

    /**
     * Getting Permissions.
     *
     * @return Collection|array
     */
    public function getPermissions(): Collection|array
    {
        Gate::authorize('app.roles.create');

        return $this->services->permissions();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('app.roles.create');

        return $this->services->validateStore()->store($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('app.roles.edit');

        return $this->services->validateUpdate($request, $id)->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('app.roles.delete');

        return $this->services->delete($id);
    }
}
