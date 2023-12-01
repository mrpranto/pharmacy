<?php

namespace App\Services\Report;

use App\Models\Expense\Expense;
use App\Models\People\Supplier;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseProduct;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleProducts;
use App\Services\BaseServices;
use Illuminate\Support\Facades\DB;

class ReportServices extends BaseServices
{
    public Purchase $purchase;
    public PurchaseProduct $purchaseProduct;
    public Sale $sale;
    public SaleProducts $saleProduct;
    public Expense $expense;
    public Supplier $supplier;

    /**
     * @param Purchase $purchase
     * @param PurchaseProduct $purchaseProduct
     * @param Sale $sale
     * @param SaleProducts $saleProduct
     * @param Expense $expense
     * @param Supplier $supplier
     */
    public function __construct(
        Purchase $purchase, PurchaseProduct $purchaseProduct,
        Sale     $sale, SaleProducts $saleProduct, Expense $expense,
        Supplier $supplier
    )
    {
        $this->purchase = $purchase;
        $this->purchaseProduct = $purchaseProduct;
        $this->sale = $sale;
        $this->saleProduct = $saleProduct;
        $this->expense = $expense;
        $this->supplier = $supplier;
    }

    /**
     * @return array[]
     */
    public function summary(): array
    {
        return [
            'purchase' => $this->purchase(),
            'sales' => $this->sales(),
            'profit' => $this->profit()
        ];
    }

    /**
     * @return array
     */
    private function purchase(): array
    {
        $purchase = $this->purchase
            ->newQuery()
            ->where('status', $this->purchase::STATUS_RECEIVED)
            ->select(
                DB::raw('sum(total) as totalGrandTotal'),
                DB::raw('sum(subtotal) as totalSubTotal'),
                DB::raw('sum(discount) as totalDiscount'),
                DB::raw('sum(total_paid) as totalPaid'),
                DB::raw('sum(total - total_paid) as totalDue'),
            )
            ->first()
            ->toArray();

        $purchaseIds = $this->purchase
            ->newQuery()
            ->where('status', $this->purchase::STATUS_RECEIVED)
            ->pluck('id')
            ->toArray();

        $purchaseQuantity = $this->purchaseProduct
            ->newQuery()
            ->whereIn('purchase_id', $purchaseIds)
            ->sum('quantity');

        return [
            ...$purchase,
            'totalPurchaseQuantity' => $purchaseQuantity
        ];
    }

    /**
     * @return array
     */
    private function sales(): array
    {
        return $this->sale
            ->newQuery()
            ->where('status', $this->sale::STATUS_DELIVERED)
            ->select(
                DB::raw('sum(grand_total) as totalGrandTotal'),
                DB::raw('sum(subtotal) as totalSubTotal'),
                DB::raw('sum(discount) as totalDiscount'),
                DB::raw('sum(total_paid) as totalPaid'),
                DB::raw('sum(grand_total - total_paid) as totalDue'),
                DB::raw('sum(total_unit_qty) as totalSalesQuantity'),
            )
            ->first()
            ->toArray();
    }

    /**
     * @return array
     */
    public function profit(): array
    {
        $revenue = $this->saleProduct
            ->newQuery()
            ->whereHas('sale', fn($query) => $query->where('status', $this->sale::STATUS_DELIVERED))
            ->select(DB::raw('sum((sale_price * quantity) - (unit_price * quantity)) as revenue'))
            ->first()
            ->revenue;

        $expense = $this->expense
            ->newQuery()
            ->sum('total_amount');

        return [
            'revenue' => $revenue,
            'expense' => $expense,
            'profit' => ($revenue - $expense)
        ];
    }

    public function suppliers()
    {
        return $this->supplier
            ->newQuery()
            ->get(['id', 'name', 'phone_number'])
            ->map(function ($item){
                return [
                    'value' => $item->id,
                    'label' => $item->name ."({$item->phone_number})"
                ];
            });
    }
}
