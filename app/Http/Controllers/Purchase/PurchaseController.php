<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\Purchase;
use App\Services\Purchase\PurchaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PurchaseController extends Controller
{
    /**
     * @param PurchaseServices $purchaseServices
     */
    public function __construct(PurchaseServices $purchaseServices)
    {
        $this->services = $purchaseServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.purchase.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPurchaseList(): LengthAwarePaginator
    {
        return $this->services->getPurchaseList();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.purchase.create');
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProducts(): mixed
    {
        return $this->services->getProducts();
    }

    /**
     * @return Collection|array
     */
    public function getSuppliers(): Collection|array
    {
        return $this->services->getSuppliers();
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
    public function show(string $id): Purchase|array
    {
        return $this->services->showDetails($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        return view('pages.purchase.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->services
            ->validateUpdate($request, $id)
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
