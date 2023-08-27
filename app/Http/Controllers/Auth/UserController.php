<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserController extends Controller
{
    /**
     * @param UserServices $userServices
     */
    public function __construct(UserServices $userServices)
    {
        $this->services = $userServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('app.user.index');

        return view('pages.users.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUsers(): LengthAwarePaginator
    {
        Gate::authorize('app.user.index');

        return $this->services->getUsers();
    }

    /**
     * @return Collection|array
     */
    public function getRoles(): Collection|array
    {
        Gate::authorize('app.user.index');

        return $this->services->getRoles();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('app.user.create');

        return $this->services->validateStore($request)->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): User|array
    {
        Gate::authorize('app.user.show');

        return $this->services->showDetails($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('app.user.edit');

       return $this->services->validateUpdate($request, $id)->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('app.user.delete');

        return $this->services->delete($id);
    }
}
