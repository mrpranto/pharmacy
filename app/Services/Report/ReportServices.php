<?php

namespace App\Services\Report;

use App\Models\Expense\Expense;
use App\Models\People\Customer;
use App\Models\People\Supplier;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseProduct;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleProducts;
use App\Services\BaseServices;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportServices extends BaseServices
{
    public Purchase $purchase;
    public PurchaseProduct $purchaseProduct;
    public Sale $sale;
    public SaleProducts $saleProduct;
    public Expense $expense;
    public Supplier $supplier;
    public Customer $customer;

    /**
     * @param Purchase $purchase
     * @param PurchaseProduct $purchaseProduct
     * @param Sale $sale
     * @param SaleProducts $saleProduct
     * @param Expense $expense
     * @param Supplier $supplier
     * @param Customer $customer
     */
    public function __construct(
        Purchase $purchase, PurchaseProduct $purchaseProduct,
        Sale     $sale, SaleProducts $saleProduct, Expense $expense,
        Supplier $supplier, Customer $customer
    )
    {
        $this->purchase = $purchase;
        $this->purchaseProduct = $purchaseProduct;
        $this->sale = $sale;
        $this->saleProduct = $saleProduct;
        $this->expense = $expense;
        $this->supplier = $supplier;
        $this->customer = $customer;
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

    /**
     * @return Collection
     */
    public function suppliers(): Collection
    {
        return $this->supplier
            ->newQuery()
            ->get(['id', 'name', 'phone_number'])
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->name . "({$item->phone_number})"
                ];
            });
    }

    /**
     * @return array
     */
    public function getPurchaseData(): array
    {
        if (request()->filled('date') || request()->filled('supplier') || request()->filled('purchase_status') || request()->filled('payment_status')){
            $purchaseReports = $this->purchase
                ->newQuery()
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                ->select(
                    'suppliers.name as supplier_name', 'suppliers.phone_number as supplier_phone_number',
                    'purchases.id', 'purchases.date', 'purchases.status', 'purchases.reference', 'purchases.subtotal',
                    'purchases.total', 'purchases.total_paid', 'purchases.payment_status',
                    DB::raw('(purchases.total - purchases.total_paid) as total_due')
                )
                ->when(request()->filled('date'), function ($q){
                    $dates = explode(' to ', request()->get('date'));
                    $q->whereBetween('purchases.date', [$dates[0], $dates[1]]);
                })
                ->when(request()->filled('supplier'), fn($q) => $q->where('purchases.supplier_id', request()->get('supplier')))
                ->when(request()->filled('purchase_status'), fn($q) => $q->whereIn('purchases.status', request()->get('purchase_status')))
                ->when(request()->filled('payment_status'), fn($q) => $q->whereIn('purchases.payment_status', request()->get('payment_status')))
                ->when(request()->filled('search'), fn($q) => $q->where('suppliers.name', 'like', "%".request()->get('search')."%")
                    ->orWhere('suppliers.phone_number', 'like', "%".request()->get('search')."%")
                    ->orWhere('purchases.reference', 'like', "%".request()->get('search')."%")
                )
                ->get();

            return [
                'purchase_reports' => $purchaseReports,
                'total_subtotal' => $purchaseReports->sum('subtotal'),
                'total_grand_total' => $purchaseReports->sum('total'),
                'total_paid' => $purchaseReports->sum('total_paid'),
                'total_due' => $purchaseReports->sum('total_due'),
                'total_purchase' => $purchaseReports->count(),
            ];
        }

        return [
            'purchase_reports' => [],
            'total_subtotal' => 0,
            'total_grand_total' => 0,
            'total_paid' => 0,
            'total_due' => 0,
            'total_purchase' => 0,
        ];
    }

    /**
     * @return Collection
     */
    public function customers(): Collection
    {
        return $this->customer
            ->newQuery()
            ->where('status', true)
            ->get(['id', 'name', 'phone_number'])
            ->map(function ($item) {
                return [
                    'value' => $item->id,
                    'label' => $item->name . "({$item->phone_number})"
                ];
            });
    }
}
