<?php

namespace App\Services\Report;

use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseProduct;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportServices extends BaseServices
{
    public Purchase $purchase;
    public PurchaseProduct $purchaseProduct;
    public function __construct(Purchase $purchase, PurchaseProduct $purchaseProduct)
    {
        $this->purchase = $purchase;
        $this->purchaseProduct = $purchaseProduct;
    }

    /**
     * @return array[]
     */
    public function summary(): array
    {
        return [
            'purchase' => $this->purchase()
        ];
    }

    /**
     * @return array
     */
    private function purchase(): array
    {
        $purchase = $this->purchase
            ->newQuery()
            ->where('purchases.status', $this->purchase::STATUS_RECEIVED)
            ->select(
                DB::raw('sum(purchases.total) as totalGrandTotal'),
                DB::raw('sum(purchases.subtotal) as totalSubTotal'),
                DB::raw('sum(purchases.discount) as totalDiscount'),
                DB::raw('sum(purchases.total_paid) as totalPaid'),
                DB::raw('sum(purchases.total - total_paid) as totalDue'),
            )
            ->first()
            ->toArray();

        $purchaseIds = $this->purchase
            ->newQuery()
            ->where('purchases.status', $this->purchase::STATUS_RECEIVED)
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
}
