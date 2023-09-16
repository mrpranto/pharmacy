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
            ->paginate(request('per_page', pagination()));
    }
}
