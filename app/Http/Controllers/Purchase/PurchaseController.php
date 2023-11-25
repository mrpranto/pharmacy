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
use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('app.purchase.index');

        return view('pages.purchase.index', $this->services->accessPermissions());
    }


    public function getPurchaseList()
    {
        Gate::authorize('app.purchase.index');

        return $this->services->getPurchaseList();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('app.purchase.create');

        return view('pages.purchase.create');
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProducts(): mixed
    {
        Gate::authorize('app.purchase.create');

        return $this->services->getProducts();
    }

    /**
     * @return Collection|array
     */
    public function getSuppliers(): Collection|array
    {
        Gate::authorize('app.purchase.create');

        return $this->services->getSuppliers();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('app.purchase.create');

        return $this->services
            ->validateStore($request)
            ->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Purchase|array
    {
        Gate::authorize('app.purchase.show');

        return $this->services->showDetails($id);
    }

    /**
     * @param string $id
     * @return View
     */
    public function printPurchase(string $id): View
    {
        Gate::authorize('app.purchase.show');

        return view('pages.purchase.print-purchase',['purchase' => (object) $this->services->showDetails($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        Gate::authorize('app.purchase.edit');

        return view('pages.purchase.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('app.purchase.edit');

        return $this->services
            ->validateUpdate($request, $id)
            ->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('app.purchase.delete');

        return $this->services->delete($id);
    }

    /**
     * @return JsonResponse
     */
    public function paymentSave(): JsonResponse
    {
        return $this->services
            ->validatePayment()
            ->savePayment();
    }
}
