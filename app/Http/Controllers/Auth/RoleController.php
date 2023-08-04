<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Roles\RoleServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
    public function index()
    {
        return view('pages.roles.index');
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
        return $this->services->getRoles();
    }

    /**
     * Getting Permissions.
     *
     * @return Collection|array
     */
    public function getPermissions(): Collection|array
    {
        return $this->services->permissions();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->services->validateStore()->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
