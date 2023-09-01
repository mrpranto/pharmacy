<?php

namespace App\Services\Purchase;

use App\Models\Product\Product;
use App\Services\BaseServices;

class PurchaseServices extends BaseServices
{
    public $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProducts()
    {
        return $this->product
            ->newQuery()
            ->active()
            ->with(['category:id,name', 'company:id,name', 'unit:id,name,pack_size'])
            ->when(request()->filled('search'), function ($q){
                $q->where('barcode', 'like', "%".request()->get('search')."%")
                    ->orWhere('name', 'like', "%".request()->get('search')."%")
                    ->orWhere('slug', 'like', "%".request()->get('search')."%");
            })
            ->orderBy('id', 'desc')
            ->take(10)
            ->get(['id', 'name', 'category_id', 'company_id', 'unit_id', 'barcode', 'status']);
    }
}
