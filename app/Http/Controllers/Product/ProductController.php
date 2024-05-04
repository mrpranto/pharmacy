<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Services\Product\Products\ProductServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProductController extends Controller
{
    /**
     * @param ProductServices $productServices
     */
    public function __construct(ProductServices $productServices)
    {
        $this->services = $productServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('app.product.index');

        return view('pages.products.products.index', $this->services->accessPermissions());
    }


    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProducts(): array
    {
        Gate::authorize('app.product.index');

        return $this->services->getProducts();
    }

    /**
     * @return array
     */
    public function getDependency(): array
    {
        Gate::authorize('app.product.index');

        return $this->services->getDependency();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): ?JsonResponse
    {
        Gate::authorize('app.product.create');

        return $this->services
            ->validateStore($request)
            ->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Product|array
    {
        Gate::authorize('app.product.show');

        return $this->services->showDetails($id);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('app.product.edit');

        return $this->services
            ->validateUpdate($request, $id)
            ->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('app.product.delete');

        return $this->services->delete($id);
    }

    public function storeOpeningStock(Request $request)
    {
        $this->services
            ->validateOpeningStock($request)
            ->storeOpeningStock($request);
        dd($request->all());
    }
}
