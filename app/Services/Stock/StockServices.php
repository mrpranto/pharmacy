<?php

namespace App\Services\Stock;

use App\Models\Product\Product;
use App\Models\Stock\Stock;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getStocks(): array
    {
        $stocks = $this->product
            ->newQuery()
            ->with(['stocks'])
            ->whereHas('stocks')
            ->when(request()->filled('category'), fn($q) => $q->where('category_id', request()->get('category')))
            ->when(request()->filled('company'), fn($q) => $q->where('company_id', request()->get('company')))
            ->when(request()->filled('unit'), fn($q) => $q->where('unit_id', request()->get('unit')))
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('barcode', 'like', '%' . request()->get('search') . '%'))
            ->paginate(request('per_page', pagination()));

        $counter = $this->counter();

        return [
            'stocks' => $stocks,
            'sales' => [
                'totalSaleQuantity' => $counter->totalSaleQuantity,
                'totalSaleAmount' => round($counter->totalSaleAmount, 2),
            ],
            'purchase' => [
                'totalPurchaseQuantity' => $counter->totalPurchaseQuantity,
                'totalPurchaseAmount' => round($counter->totalPurchaseAmount, 2),
            ],
            'available' => [
                'totalAvailableQuantity' => $counter->totalAvailableQuantity,
                'totalStockCostAmount' => round($counter->totalStockCostAmount, 2),
            ],
        ];
    }


    /**
     * @return object|Builder|Model|null
     */
    public function counter(): object|null
    {
        return $this->model
            ->newQuery()
            ->select(
                DB::raw('sum(purchase_quantity) as totalPurchaseQuantity'),
                DB::raw('sum(purchase_quantity * unit_price) as totalPurchaseAmount'),
                DB::raw('sum(sale_quantity) as totalSaleQuantity'),
                DB::raw('sum(sale_quantity * sale_price) as totalSaleAmount'),
                DB::raw('sum(available_quantity) as totalAvailableQuantity'),
                DB::raw('sum(available_quantity * unit_price) as totalStockCostAmount'),
            )
            ->first();
    }
}
