<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\Categories\CategoryServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategoryController extends Controller
{
    /**
     * @param CategoryServices $categoryServices
     */
    public function __construct(CategoryServices $categoryServices)
    {
        $this->services = $categoryServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.products.categories.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCategories(): LengthAwarePaginator
    {
        return $this->services->getCategories();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->services
            ->validate($request)
            ->store($request);
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
