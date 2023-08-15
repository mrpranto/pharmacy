<?php

namespace App\Services\Product\Products;

use App\Models\Product\Product;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProductServices extends BaseServices
{
    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.product.create'),
                'edit' => auth()->user()->can('app.product.edit'),
                'delete' => auth()->user()->can('app.product.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProducts(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->select(['products.*', 'categories.name as category_name', 'companies.name as company_name', 'units.name as unit_name'])
            ->with(['category', 'company', 'unit'])
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->when(request()->filled('search'), fn($q) => $q->where('products.name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('products.barcode', 'like', '%' . request()->get('search') . '%')
                ->orWhere('products.slug', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('status'), fn($q) => $q->where('products.status', request()->get('status')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), function ($q) {
                if (request()->get('order_by') == 'category') {
                    return $q->orderBy('category_name', request()->get('order_dir'));
                } elseif (request()->get('order_by') == 'company') {
                    return $q->orderBy('company_name', request()->get('order_dir'));
                } elseif (request()->get('order_by') == 'unit') {
                    return $q->orderBy('unit_name', request()->get('order_dir'));
                } else {
                    return $q->orderBy(request()->get('order_by'), request()->get('order_dir'));
                }
            })
            ->paginate(request()->get('per_page') ?? pagination());
    }
}
