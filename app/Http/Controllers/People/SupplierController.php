<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\People\Supplier;
use App\Services\People\Suppliers\SupplierServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SupplierController extends Controller
{
    /**
     * @param SupplierServices $supplierServices
     */
    public function __construct(SupplierServices $supplierServices)
    {
        $this->services = $supplierServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.peoples.suppliers.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSuppliers(): LengthAwarePaginator
    {
        return $this->services->getSuppliers();
    }

    /**
     * @return array
     */
    public function getDependency(): array
    {
        return $this->services->getDependency();
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
    public function show(string $id): array|Supplier
    {
        return $this->services->showDetails($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return $this->services
            ->validateUpdate($request)
            ->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->services->delete($id);
    }
}
