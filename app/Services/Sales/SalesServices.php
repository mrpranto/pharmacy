<?php

namespace App\Services\Sales;

use App\Models\Payment;
use App\Models\People\Customer;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Sale\SaleProducts;
use App\Models\Stock\Stock;
use App\Models\Stock\StockLog;
use App\Rules\CheckAvailableQuantity;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Mpdf\MpdfException;
use PDF;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesServices extends BaseServices
{
    public Customer $customer;
    public Product $product;
    public Category $category;
    public Company $company;
    public Stock $stock;
    public SaleProducts $saleProducts;

    public function __construct(
        Customer     $customer,
        Product      $product,
        Category     $category,
        Company      $company,
        Sale         $sale,
        SaleProducts $saleProducts,
        Stock        $stock
    )
    {
        $this->customer = $customer;
        $this->product = $product;
        $this->category = $category;
        $this->company = $company;
        $this->model = $sale;
        $this->stock = $stock;
        $this->saleProducts = $saleProducts;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.sales.create'),
                'edit' => auth()->user()->can('app.sales.show'),
                'show' => auth()->user()->can('app.sales.edit'),
                'delete' => auth()->user()->can('app.sales.delete'),
                'change_status' => auth()->user()->can('app.sales.status-change'),
                'payment_add' => auth()->user()->can('app.sales.payment-add'),
            ],
            'payment_type' => Payment::getConst('TYPE_')
        ];
    }

    public function getSalesList()
    {
        $sales = $this->model
            ->newQuery()
            ->select([
                'sales.id', 'sales.invoice_number', 'sales.invoice_date', 'sales.customer_id',
                'sales.total_unit_qty', 'sales.subtotal', 'sales.status', 'sales.payment_status',
                'sales.other_cost', 'sales.discount', 'sales.grand_total', 'sales.total_paid',
                'customers.name as customer_name',
            ])
            ->with(['customer:id,name,phone_number', 'payments'])
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->when(request()->filled('date'), function ($q) {
                $dates = explode(' to ', request()->get('date'));
                $q->whereDate('sales.invoice_date', '>=', $dates[0])
                    ->whereDate('sales.invoice_date', '<=', $dates[1]);
            })
            ->when(request()->filled('customer'), fn($q) => $q->where('sales.customer_id', request()->get('customer')))
            ->when(request()->filled('sale_status'), fn($q) => $q->where('sales.status', request()->get('sale_status')))
            ->when(request()->filled('payment_status'), fn($q) => $q->where('sales.payment_status', request()->get('payment_status')))
            ->when(request()->filled('search'), fn($q) => $q->where('sales.invoice_number', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), function ($q) {
                if (request()->get('order_by') == 'customer') {
                    return $q->orderBy('customer_name', request()->get('order_dir'));
                } else {
                    return $q->orderBy(request()->get('order_by'), request()->get('order_dir'));
                }
            })
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'))
            ->paginate(request()->get('per_page') ?? pagination());

        $salesCounter = $this->model
            ->newQuery()
            ->select(
                DB::raw('sum(total_unit_qty) as totalUnitQuantity'),
                DB::raw('sum(subtotal) as totalSubtotalAmount'),
                DB::raw('sum(grand_total) as totalGrandTotalAmount'),
                DB::raw('sum(total_paid) as totalPaidAmount'),
            )
            ->first();

        return [
            'sales' => $sales,
            'totalUnitQuantity' => $salesCounter->totalUnitQuantity,
            'totalSubtotalAmount' => $salesCounter->totalSubtotalAmount,
            'totalGrandTotalAmount' => $salesCounter->totalGrandTotalAmount,
            'totalPaidAmount' => $salesCounter->totalPaidAmount,
        ];
    }

    /**
     * Get Customers for add sale.
     *
     * @return Collection|array
     */
    public function getCustomers(): Collection|array
    {
        return $this->customer
            ->newQuery()
            ->when(request()->filled('search'),
                fn($query) => $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('phone_number', 'like', '%' . request('search') . '%'))
            ->take(10)
            ->orderByDesc('id')
            ->get(['id', 'name', 'phone_number']);
    }

    /**
     * Getting product for add new sale.
     *
     * @return Collection|array
     */
    public function getProducts(): Collection|array
    {
        return $this->product
            ->newQuery()
            ->with(['stocks'])
            ->whereHas('stocks')
            ->active()
            ->when(request()->filled('search'),
                fn($query) => $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('barcode', 'like', '%' . request('search') . '%'))
            ->when(request()->filled('category'),
                fn($query) => $query->where('category_id', request('category')))
            ->when(request()->filled('company'),
                fn($query) => $query->where('company_id', request('company')))
            ->take(100)
            ->get([
                'id', 'category_id', 'company_id', 'unit_id', 'barcode', 'name', 'purchase_type', 'status'
            ]);
    }

    /**
     * Sale create dependencies.
     *
     * @return array
     */
    public function createDependencies(): array
    {
        return [
            'categories' => $this->category
                ->newQuery()
                ->active()
                ->get(['id', 'name']),

            'companies' => $this->company
                ->newQuery()
                ->active()
                ->get(['id', 'name'])
        ];
    }

    /**
     * @return StreamedResponse
     * @throws MpdfException
     */
    public function renderPdf(): StreamedResponse
    {
        $customer = Customer::query()
            ->where('id', cache(auth()->user()->email)['customer'])
            ->first();

        return generate_pdf('pages.sale.pdf', [
            'customer_phone' => $customer->phone_number,
            'invoice_details' => cache(auth()->user()->email)
        ]);
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateStoreAndUpdate($request): static
    {
        $request->validate([
            'customer' => 'required|numeric|exists:customers,id',
            'grandTotal' => 'required|numeric',
            'otherCost' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'totalSubTotal' => 'required|numeric',
            'totalUnitQuantity' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product.id' => 'required|numeric',
            'products.*.quantity' => ['required', 'numeric', new CheckAvailableQuantity()],
            'products.*.sale_price' => 'required|numeric|gte:products.*.original_sale_price',
            'products.*.sale_percentage' => 'nullable|numeric',
            'products.*.sub_total' => 'required|numeric',
            'products.*.original_sale_price' => 'required|numeric',
        ]);

        return $this;
    }

    /**
     * @return mixed|void
     */
    private function status()
    {
        if (request()->filled('type')) {
            if (request()->get('type') == 'draft') {
                return $this->model::STATUS_DRAFT;
            } elseif (request()->get('type') == 'confirmed') {
                return $this->model::STATUS_CONFIRMED;
            } elseif (request()->get('type') == 'delivered') {
                return $this->model::STATUS_DELIVERED;
            }
        }
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function storeSale($request): JsonResponse
    {
        try {

            DB::transaction(function () use ($request) {
                $max_id = ($this->model->newQuery()->withTrashed()->max('id') + 1);
                $invoice_number = str_pad($max_id, 8, '00000', STR_PAD_LEFT);

                $this->model = $this->model
                    ->newQuery()
                    ->create([
                        'invoice_number' => $invoice_number,
                        'invoice_date' => now(),
                        'customer_id' => $request->customer,
                        'total_unit_qty' => $request->totalUnitQuantity,
                        'subtotal' => $request->totalSubTotal,
                        'status' => $this->status(),
                        'other_cost' => $request->otherCost ?? 0,
                        'discount' => $request->discount ?? 0,
                        'grand_total' => $request->grandTotal,
                        'invoice_details' => json_encode($request->all()),
                    ]);

                $salesProducts = [];

                foreach ($request->products ?? [] as $saleProduct) {
                    $salesProducts[] = [
                        'sale_id' => $this->model->id,
                        'product_id' => $saleProduct['product']['id'],
                        'mrp' => $saleProduct['mrp'],
                        'original_sale_price' => $saleProduct['original_sale_price'],
                        'unit_price' => $saleProduct['unit_price'],
                        'sale_price' => $saleProduct['sale_price'],
                        'sale_percentage' => $saleProduct['sale_percentage'],
                        'quantity' => $saleProduct['quantity'],
                        'subtotal' => $saleProduct['sub_total'],
                        'sale_product_details' => json_encode($saleProduct),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $this->model->saleProducts()->insert($salesProducts);
                if ($this->status() === Sale::STATUS_DELIVERED) {
                    $this->adjustStock($salesProducts);
                }
            });

            return response()->json(['success' => __t('sales_invoice_created_successful')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $salesProducts
     * @return void
     */
    private function adjustStock($salesProducts): void
    {
        foreach ($salesProducts as $salesProduct) {
            $existProduct = $this->stock
                ->newQuery()
                ->where('product_id', $salesProduct['product_id'])
                ->where('sale_price', $salesProduct['original_sale_price'])
                ->first();

            if ($existProduct) {
                $existProduct->update([
                    'available_quantity' => ($existProduct->available_quantity - $salesProduct['quantity']),
                    'sale_quantity' => ($existProduct->sale_quantity + $salesProduct['quantity'])
                ]);

                StockLog::query()->create([
                    'stock_id' => $existProduct->id,
                    'product_id' => $salesProduct['product_id'],
                    'mrp' => $existProduct->mrp,
                    'unit_price' => $existProduct->unit_price,
                    'unit_percentage' => $existProduct->unit_percentage,
                    'sale_price' => $existProduct->sale_price,
                    'sale_percentage' => $existProduct->sale_percentage,
                    'purchase_quantity' => $existProduct->purchase_quantity,
                    'sale_quantity' => $existProduct->sale_quantity,
                    'available_quantity' => $existProduct->available_quantity,
                    'type' => StockLog::TYPE_SALE
                ]);
            }
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function changeStatus($id): JsonResponse
    {
        try {

            DB::transaction(function () use ($id) {

                $sales = $this->getModelById($id);

                if (request()->get('status') === Sale::STATUS_DELIVERED) {

                    $this->adjustStock($sales->saleProducts);

                    $sales->update(['status' => request()->get('status')]);
                } else {
                    $sales->update(['status' => request()->get('status')]);
                }

            });

            return response()->json(['success' => __t('status_change_success')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteSale($id): JsonResponse
    {
        try {

            DB::transaction(function () use ($id) {
                $sales = $this->getModelById($id);
                $sales->saleProducts()->delete();
                $sales->delete();
            });

            return response()->json(['success' => __t('sale_delete_successful')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return StreamedResponse
     * @throws MpdfException
     */
    public function renderInvoicePdf($id): StreamedResponse
    {
        $sales = $this->getModelById($id, ['customer', 'saleProducts.product.unit']);

        return generate_pdf('pages.sale.invoice-pdf', [
            'invoice_details' => $sales
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function getEditableData($id): array
    {
        return array_merge($this->createDependencies(),
            ['sale' => $this->getModelById($id, [
                'customer:id,name',
                'saleProducts.product'
            ])]);
    }

    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function update($request, $id): JsonResponse
    {
        try {

            DB::transaction(function () use ($request, $id) {

                $sale = $this->getModelById($id, ['saleProducts']);
                $oldSaleProduct = $sale->saleProducts;
                $existSaleProduct = [];
                foreach ($request->products as $requestProduct) {
                    $checkExist = $oldSaleProduct
                        ->where('sale_id', $id)
                        ->where('product_id', $requestProduct['product']['id'])
                        ->where('mrp', $requestProduct['mrp'])
                        ->where('original_sale_price', $requestProduct['original_sale_price'])
                        ->first();

                    if ($checkExist) {
                        $existSaleProduct[] = [
                            "id" => $checkExist->id,
                            "sale_id" => $checkExist->sale_id,
                            "product_id" => $checkExist->product_id,
                            "mrp" => $requestProduct['mrp'],
                            "original_sale_price" => $requestProduct['original_sale_price'],
                            'unit_price' => $requestProduct['unit_price'],
                            "sale_price" => $requestProduct['sale_price'],
                            "sale_percentage" => $requestProduct['sale_percentage'],
                            "quantity" => $requestProduct['quantity'],
                            "subtotal" => $requestProduct['sub_total'],
                        ];
                    } else {
                        $existSaleProduct[] = $requestProduct;
                    }
                }

                $oldSaleProductIds = $oldSaleProduct->pluck('id')->toArray();
                $existSaleProductIds = collect($existSaleProduct)->pluck('id')->toArray();
                $removeAbleIds = array_diff($oldSaleProductIds, $existSaleProductIds);
                $sale->saleProducts()->wherein('id', $removeAbleIds)->delete();

                foreach ($existSaleProduct as $existProduct) {
                    if (isset($existProduct['product_id'])) {
                        $hasProduct = $this->saleProducts
                            ->newQuery()
                            ->where('sale_id', $id)
                            ->where('product_id', $existProduct['product_id'])
                            ->where('mrp', $existProduct['mrp'])
                            ->where('original_sale_price', $existProduct['original_sale_price'])
                            ->first();
                        if ($hasProduct) {
                            $hasProduct->update([
                                'unit_price' => $existProduct['unit_price'],
                                'sale_price' => $existProduct['sale_price'],
                                'quantity' => $existProduct['quantity'],
                                'sale_percentage' => $existProduct['sale_percentage'],
                                'subtotal' => $existProduct['subtotal'],
                                'sale_product_details' => json_encode($existProduct),
                            ]);
                        }
                    } else {
                        $this->saleProducts
                            ->newQuery()
                            ->create([
                                'sale_id' => $id,
                                'product_id' => $existProduct['product']['id'],
                                'mrp' => $existProduct['mrp'],
                                'unit_price' => $existProduct['unit_price'],
                                'original_sale_price' => $existProduct['original_sale_price'],
                                'sale_price' => $existProduct['sale_price'],
                                'quantity' => $existProduct['quantity'],
                                'sale_percentage' => $existProduct['sale_percentage'],
                                'subtotal' => $existProduct['sub_total'],
                                'sale_product_details' => json_encode($existProduct),
                            ]);
                    }
                }

                $status = request()->filled('type') && request()->get('type') === 'confirmed' ? Sale::STATUS_CONFIRMED : Sale::STATUS_DRAFT;

                $this->model
                    ->newQuery()
                    ->where('id', $id)
                    ->update([
                        'customer_id' => $request->customer,
                        'total_unit_qty' => $request->totalUnitQuantity,
                        'subtotal' => $request->totalSubTotal,
                        'status' => $status,
                        'other_cost' => $request->otherCost ?? 0,
                        'discount' => $request->discount ?? 0,
                        'grand_total' => $request->grandTotal,
                        'invoice_details' => json_encode($request->all()),
                    ]);
            });

            return response()->json(['success' => __t('sales_invoice_updated_successful')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @return $this
     */
    public function validatePayment(): static
    {
        request()->validate([
            "current_id" => "required|exists:sales,id",
            "totalPaid" => "required|numeric",
            "paymentStatus" => "required|in:" . implode(',', Sale::getValidationConst('PAYMENT_STATUS_')),
            "formData" => "required|array",
            "formData.*.type" => "required|in:" . implode(',', Payment::getValidationConst('TYPE_')),
            "formData.*.paid_amount" => "required|numeric",
        ], [
            "formData.*.type.required" => 'Payment type is required',
            "formData.*.paid_amount.required" => 'Paid amount is required',
            "formData.*.type.in" => 'Payment type is invalid',
            "formData.*.paid_amount.numeric" => 'Paid amount must be numeric',
        ]);

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function savePayment(): JsonResponse
    {
        try {

            DB::transaction(function () {

                $existSale = $this->model
                    ->newQuery()
                    ->where('id', request()->get('current_id'))
                    ->first();

                $paymentIds = $existSale->payments()->pluck('id')->toArray();
                $newPaymentIds = collect(request()->get('formData'))->pluck('id')->toArray();
                $deleteAbleIds = array_diff($paymentIds, $newPaymentIds);

                $existSale->payments()->whereIn('id', $deleteAbleIds)->delete();

                $salesPayment = [];
                foreach (request()->get('formData') as $payment) {
                    if (isset($payment['id'])) {
                        Payment::query()
                            ->where('id', $payment['id'])
                            ->update([
                                'bank_name' => $payment['bank_name'],
                                'paid_amount' => $payment['paid_amount'],
                                'type' => $payment['type'],
                                'account_number' => $payment['account_number'],
                                'transaction_number' => $payment['transaction_number'],
                            ]);
                    } else {
                        $salesPayment[] = [
                            'paid_amount' => $payment['paid_amount'],
                            'type' => $payment['type'],
                            'bank_name' => $payment['bank_name'],
                            'account_number' => $payment['account_number'],
                            'transaction_number' => $payment['transaction_number'],
                            'paymentable_type' => Sale::class,
                            'paymentable_id' => request()->get('current_id'),
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                Payment::query()->insert($salesPayment);

                $existSale->update([
                    'payment_status' => request()->get('paymentStatus'),
                    'total_paid' => request()->get('totalPaid'),
                ]);
            });

            return response()->json(['success' => __t('payment_save_success')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

}
