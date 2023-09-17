<?php

namespace App\Services\Stock;

use App\Models\Product\Product;
use App\Models\Stock\Stock;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockServices extends BaseServices
{
    public Product $product;

    /**
     * @param Stock $stock
     * @param Product $product
     */
    public function __construct(Stock $stock, Product $product)
    {
        $this->model = $stock;
        $this->product = $product;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getStocks(): LengthAwarePaginator
    {
        return $this->product
            ->newQuery()
            ->with(['stocks'])
            ->whereHas('stocks')
            ->when(request()->filled('category'), fn($q) => $q->where('category_id', request()->get('category')))
            ->when(request()->filled('company'), fn($q) => $q->where('company_id', request()->get('company')))
            ->when(request()->filled('unit'), fn($q) => $q->where('unit_id', request()->get('unit')))
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('barcode', 'like', '%' . request()->get('search') . '%'))
            ->paginate(request('per_page', pagination()));
    }
}
