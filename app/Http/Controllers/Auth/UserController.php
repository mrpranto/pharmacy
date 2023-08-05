<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('pages.users.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUsers(): LengthAwarePaginator
    {
        return $this->services->getUsers();
    }

    public function uploadProfilePicture()
    {
        dd(request()->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
