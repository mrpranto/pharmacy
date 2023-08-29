<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\People\Customer;
use App\Services\People\Customers\CustomerServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CustomerController extends Controller
{

    /**
     * @param CustomerServices $customerServices
     */
    public function __construct(CustomerServices $customerServices)
    {
        $this->services = $customerServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.peoples.customers.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCustomers(): LengthAwarePaginator
    {
        return $this->services->getCustomers();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->services
            ->validateStore($request)
            ->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Customer|array
    {
        return $this->services->showDetails($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return $this->services
            ->validateUpdate($request, $id)
            ->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->services->delete($id);
    }
}
