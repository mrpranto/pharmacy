<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Attribute;
use App\Services\Product\Attributes\AttributeServices;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AttributeController extends Controller
{
    public function __construct(AttributeServices $attributeServices)
    {
        $this->services = $attributeServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.products.attributes.index', $this->services->accessPermissions());
    }

    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAttributes(): array
    {
        return $this->services->getAttributes();
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
    public function show(string $id): Attribute|array
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
    public function destroy(string $id): JsonResponse
    {
        return $this->services->delete($id);
    }
}
