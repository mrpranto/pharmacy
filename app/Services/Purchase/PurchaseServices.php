<?php

namespace App\Services\Purchase;

use App\Models\People\Supplier;
use App\Models\Product\Product;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Collection;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PurchaseServices extends BaseServices
{
    public Product $product;

    public  Supplier $supplier;

    /**
     * @param Product $product
     * @param Supplier $supplier
     */
    public function __construct(Product $product, Supplier $supplier)
    {
        $this->product = $product;
        $this->supplier = $supplier;
    }

    /**
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProducts(): mixed
    {
        return $this->product
            ->newQuery()
            ->active()
            ->with(['category:id,name', 'company:id,name', 'unit:id,name,pack_size'])
            ->when(request()->filled('search'), function ($q){
                $q->where('barcode', 'like', "%".request()->get('search')."%")
                    ->orWhere('name', 'like', "%".request()->get('search')."%")
                    ->orWhere('slug', 'like', "%".request()->get('search')."%")
                    ->orWhereHas('category', function ($q){
                        $q->where('name', 'like', "%".request()->get('search')."%");
                    })
                    ->orWhereHas('company', function ($q){
                        $q->where('name', 'like', "%".request()->get('search')."%");
                    })
                    ->orWhereHas('unit', function ($q){
                        $q->where('name', 'like', "%".request()->get('search')."%")
                            ->orWhere('pack_size', 'like', "%".request()->get('search')."%");
                    });
            })
            ->orderBy('id', 'desc')
            ->take(10)
            ->get(['id', 'name', 'category_id', 'company_id', 'unit_id', 'barcode', 'status']);
    }

    /**
     * @return Collection|array
     */
    public function getSuppliers(): Collection|array
    {
        return $this->supplier
            ->newQuery()
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'phone_number']);
    }
}
