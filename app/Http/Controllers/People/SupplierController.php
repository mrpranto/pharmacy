<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Services\People\Suppliers\SupplierServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
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
