<?php

namespace App\Services\Purchase;

use App\Models\Payment;
use App\Models\People\Supplier;
use App\Models\Product\Product;
use App\Models\Purchase\Purchase;
use App\Models\Stock\Stock;
use App\Models\Stock\StockLog;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PurchaseServices extends BaseServices
{
    public Supplier $supplier;
    public Product $product;
    public Stock $stock;
    public StockLog $stockLog;


    /**
     * @param Purchase $purchase
     * @param Product $product
     * @param Supplier $supplier
     * @param Stock $stock
     * @param StockLog $stockLog
     */
    public function __construct(Purchase $purchase, Product $product, Supplier $supplier, Stock $stock, StockLog $stockLog)
    {
        $this->model = $purchase;
        $this->product = $product;
        $this->supplier = $supplier;
        $this->stock = $stock;
        $this->stockLog = $stockLog;
    }


    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.purchase.create'),
                'edit' => auth()->user()->can('app.purchase.edit'),
                'show' => auth()->user()->can('app.purchase.show'),
                'delete' => auth()->user()->can('app.purchase.delete'),
                'payment_add' => auth()->user()->can('app.purchase.payment-add')
            ],
            'payment_type' => Payment::getConst('TYPE_')
        ];
    }


    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPurchaseList(): array
    {
        $purchase = $this->model->newQuery()
            ->select(['purchases.*', 'suppliers.name as supplier_name'])
            ->with(['supplier', 'purchaseProducts', 'payments'])
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->when(request()->get('date'), function ($q) {
                $dates = explode(' to ', request()->get('date'));
                $q->whereBetween('purchases.date', [$dates[0], $dates[1]]);
            })
            ->when(request()->filled('supplier'), fn($q) => $q->where('purchases.supplier_id', request()->get('supplier')))
            ->when(request()->filled('purchase_status'), fn($q) => $q->where('purchases.status', request()->get('purchase_status')))
            ->when(request()->filled('payment_status'), fn($q) => $q->where('purchases.payment_status', request()->get('payment_status')))
            ->when(request()->filled('search'), fn($q) => $q->where('purchases.reference', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), function ($q) {
                if (request()->get('order_by') == 'supplier') {
                    return $q->orderBy('supplier_name', request()->get('order_dir'));
                } else {
                    return $q->orderBy(request()->get('order_by'), request()->get('order_dir'));
                }
            })
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'))
            ->paginate(request()->get('per_page') ?? pagination());

        $counters = $this->counter();

        return [
            'purchase' => $purchase,
            'received' => $counters['received'],
            'pending' => $counters['pending'],
            'canceled' => $counters['canceled'],
            'all' => $counters['all'],
        ];
    }

    /**
     * @return array[]
     */
    public function counter(): array
    {
        $purchase = $this->model
            ->newQuery()
            ->withCount('purchaseProducts')
            ->withSum('purchaseProducts', 'quantity')
            ->get(['id', 'total', 'status']);

        $received = $purchase->where('status', $this->model::STATUS_RECEIVED);
        $pending = $purchase->where('status', $this->model::STATUS_PENDING);
        $canceled = $purchase->where('status', $this->model::STATUS_CANCELED);

        return [
            'received' => [
                'total_purchase' => $received->count(),
                'total_amount' => $received->sum('total'),
                'total_unit' => $received->sum('purchase_products_count'),
                'total_quantity' => $received->sum('purchase_products_sum_quantity'),
            ],
            'pending' => [
                'total_purchase' => $pending->count(),
                'total_amount' => $pending->sum('total'),
                'total_unit' => $pending->sum('purchase_products_count'),
                'total_quantity' => $pending->sum('purchase_products_sum_quantity'),
            ],
            'canceled' => [
                'total_purchase' => $canceled->count(),
                'total_amount' => $canceled->sum('total'),
                'total_unit' => $canceled->sum('purchase_products_count'),
                'total_quantity' => $canceled->sum('purchase_products_sum_quantity'),
            ],
            'all' => [
                'total_purchase' => $purchase->count(),
                'total_amount' => $purchase->sum('total'),
                'total_unit' => $purchase->sum('purchase_products_count'),
                'total_quantity' => $purchase->sum('purchase_products_sum_quantity'),
            ],
        ];
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
            ->with(['category:id,name', 'company:id,name', 'unit:id,name,pack_size', 'stocks'])
            ->when(request()->filled('search'), function ($q) {
                $q->where('barcode', 'like', "%" . request()->get('search') . "%")
                    ->orWhere('name', 'like', "%" . request()->get('search') . "%");
            })
            ->active()
            ->orderBy('id', 'desc')
            ->take(10)
            ->get(['id', 'name', 'category_id', 'company_id', 'unit_id', 'barcode', 'status', 'purchase_type']);
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

    /**
     * @param $request
     * @return $this
     */
    public function validateStore($request): static
    {
        $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'date' => 'required',
            'status' => 'required|in:' . implode(',', Purchase::getConstantsByPrefix('STATUS_')),
            'reference' => 'required|unique:purchases,reference',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product.id' => 'required|numeric|exists:products,id',
            'products.*.mrp' => 'nullable|numeric',
            'products.*.unit_price' => 'required|numeric|min:1',
            'products.*.unit_percentage' => 'nullable|numeric',
            'products.*.sale_price' => 'required|numeric|min:1|gt:products.*.unit_price',
            'products.*.sale_percentage' => 'nullable|numeric',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.subTotal' => 'nullable|numeric',
        ]);

        return $this;
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function store($request): JsonResponse
    {
        try {

            DB::transaction(function () use ($request) {

                $this->model = $this->model
                    ->newQuery()
                    ->create([
                        'supplier_id' => $request->supplier,
                        'date' => date('Y-m-d', strtotime($request->date)),
                        'status' => $request->status,
                        'reference' => $request->reference,
                        'subtotal' => $request->subtotal,
                        'otherCost' => $request->otherCost,
                        'discount' => $request->discount,
                        'total' => $request->total,
                        'purchase_details' => json_encode($request->all()),
                        'note' => $request->note,
                    ]);

                $purchaseProducts = [];
                foreach ($request->products as $purchaseProduct) {
                    $purchaseProducts[] = [
                        'purchase_id' => $this->model->id,
                        'product_id' => $purchaseProduct['product']['id'],
                        'mrp' => $purchaseProduct['mrp'] ?? 0,
                        'unit_price' => $purchaseProduct['unit_price'],
                        'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                        'sale_price' => $purchaseProduct['sale_price'],
                        'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                        'quantity' => $purchaseProduct['quantity'],
                        'subTotal' => $purchaseProduct['subTotal'],
                        'product_details' => json_encode($purchaseProduct['product']),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if ($request->status == Purchase::STATUS_RECEIVED){
                        $this->storeStock($purchaseProduct);
                    }
                }

                $this->model->purchaseProducts()->insert($purchaseProducts);
            });

            return response()->json(['success' => __t('purchase_create')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $purchaseProduct
     * @return JsonResponse|void
     */
    public function storeStock($purchaseProduct)
    {
        try {

            $existStock = $this->stock
                ->newQuery()
                ->where('id', $purchaseProduct['stock_id'])
                ->first();

            if ($existStock){

                $existStock->update([
                    'purchase_quantity' => ($existStock->purchase_quantity + $purchaseProduct['quantity']),
                    'available_quantity' => ($existStock->available_quantity + $purchaseProduct['quantity']),
                ]);

                $this->storeStockLog([
                    'stock_id' => $existStock->id,
                    'product_id' => $existStock->product_id,
                    'mrp' => $existStock->mrp ?? 0,
                    'unit_price' => $existStock->unit_price,
                    'unit_percentage' => $existStock->unit_percentage ?? 0,
                    'sale_price' => $existStock->sale_price,
                    'sale_percentage' => $existStock->sale_percentage ?? 0,
                    'purchase_quantity' => ($existStock->purchase_quantity + $purchaseProduct['quantity']),
                    'available_quantity' => ($existStock->available_quantity + $purchaseProduct['quantity']),
                    'type' => StockLog::TYPE_PURCHASE
                ]);

            }else{

                $stock = $this->stock
                    ->newQuery()
                    ->create([
                        'product_id' => $purchaseProduct['product']['id'],
                        'sku' => make_sku($purchaseProduct['product']['id'], $purchaseProduct['unit_price'], $purchaseProduct['mrp']),
                        'mrp' => $purchaseProduct['mrp'] ?? 0,
                        'unit_price' => $purchaseProduct['unit_price'],
                        'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                        'sale_price' => $purchaseProduct['sale_price'],
                        'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                        'purchase_quantity' => $purchaseProduct['quantity'],
                        'available_quantity' => $purchaseProduct['quantity'],
                    ]);

                $this->storeStockLog([
                    'stock_id' => $stock->id,
                    'product_id' => $purchaseProduct['product']['id'],
                    'mrp' => $purchaseProduct['mrp'] ?? 0,
                    'unit_price' => $purchaseProduct['unit_price'],
                    'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                    'sale_price' => $purchaseProduct['sale_price'],
                    'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                    'purchase_quantity' => $purchaseProduct['quantity'],
                    'available_quantity' => $purchaseProduct['quantity'],
                    'type' => StockLog::TYPE_PURCHASE
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param array $data
     * @return void
     */
    public function storeStockLog(array $data): void
    {
        $this->stockLog->newQuery()->create($data);
    }

    /**
     * @param $id
     * @return Purchase|array
     */
    public function showDetails($id): Purchase|array
    {
        $purchase = $this->model
            ->newQuery()
            ->with(['supplier', 'purchaseProducts.product', 'createdBy', 'updatedBy'])
            ->where('id', $id)
            ->first();

        $this->model = $purchase->toArray();
        $this->model['created_at'] = $purchase->created_at->format(format_date()) . ' ' . $purchase->created_at->format(format_time());
        $this->model['updated_at'] = $purchase->updated_at->format(format_date()) . ' ' . $purchase->updated_at->format(format_time());

        return $this->model;
    }

    /**
     * @param $request
     * @param $id
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'supplier' => 'required|exists:suppliers,id',
            'date' => 'required',
            'status' => 'required|in:' . implode(',', Purchase::getConstantsByPrefix('STATUS_')),
            'reference' => 'required|unique:purchases,reference,' . $id,
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            'products' => 'required|array',
            'products.*.product.id' => 'required|numeric|exists:products,id',
            'products.*.mrp' => 'nullable|numeric',
            'products.*.unit_price' => 'required|numeric|min:1',
            'products.*.unit_percentage' => 'nullable|numeric',
            'products.*.sale_price' => 'required|numeric|min:1|gt:products.*.unit_price',
            'products.*.sale_percentage' => 'nullable|numeric',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.subTotal' => 'nullable|numeric',
        ]);

        return $this;
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

                $this->model = $this->model
                    ->newQuery()
                    ->with(['purchaseProducts'])
                    ->where('id', $id)
                    ->first();

                $request_purchase_product_ids = Arr::pluck($request->products, 'id');

                $purchase_product_ids = $this->model
                    ->purchaseProducts
                    ->pluck('id')
                    ->toArray();

                $deleteAblePurchaseProduct = array_diff($purchase_product_ids, $request_purchase_product_ids);

                $this->model->update([
                    'supplier_id' => $request->supplier,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'status' => $request->status,
                    'reference' => $request->reference,
                    'subtotal' => $request->subtotal,
                    'otherCost' => $request->otherCost,
                    'discount' => $request->discount,
                    'total' => $request->total,
                    'purchase_details' => json_encode($request->all()),
                    'note' => $request->note,
                ]);

                foreach ($request->products as $purchaseProduct) {
                    if (isset($purchaseProduct['id'])) {
                        $this->model
                            ->purchaseProducts()
                            ->where('id', $purchaseProduct['id'])
                            ->update([
                                'purchase_id' => $this->model->id,
                                'product_id' => $purchaseProduct['product']['id'],
                                'mrp' => $purchaseProduct['mrp'] ?? 0,
                                'unit_price' => $purchaseProduct['unit_price'],
                                'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                                'sale_price' => $purchaseProduct['sale_price'],
                                'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                                'quantity' => $purchaseProduct['quantity'],
                                'subTotal' => $purchaseProduct['subTotal'],
                                'product_details' => json_encode($purchaseProduct['product']),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                    } else {
                        $this->model
                            ->purchaseProducts()
                            ->create([
                                'purchase_id' => $this->model->id,
                                'product_id' => $purchaseProduct['product']['id'],
                                'mrp' => $purchaseProduct['mrp'] ?? 0,
                                'unit_price' => $purchaseProduct['unit_price'],
                                'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                                'sale_price' => $purchaseProduct['sale_price'],
                                'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                                'quantity' => $purchaseProduct['quantity'],
                                'subTotal' => $purchaseProduct['subTotal'],
                                'product_details' => json_encode($purchaseProduct['product']),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                    }
                    if ($request->status === Purchase::STATUS_RECEIVED){
                        $this->updateStock($purchaseProduct);
                    }
                }

                $this->model
                    ->purchaseProducts()
                    ->whereIn('id', $deleteAblePurchaseProduct)
                    ->delete();
            });

            return response()->json(['success' => __t('purchase_update')]);

        } catch (\Exception $exception) {
            Log::info($exception);
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $purchaseProduct
     * @return JsonResponse|void
     */
    public function updateStock($purchaseProduct)
    {
        try {

            $existStock = $this->stock
                ->newQuery()
                ->where('product_id', $purchaseProduct['product']['id'])
                ->where('unit_price', $purchaseProduct['unit_price'])
                ->where('sale_price', $purchaseProduct['sale_price'])
                ->first();

            if ($existStock){

                $existStock->update([
                    'purchase_quantity' => ($existStock->purchase_quantity + $purchaseProduct['quantity']),
                    'available_quantity' => ($existStock->available_quantity + $purchaseProduct['quantity']),
                ]);

                $this->storeStockLog([
                    'stock_id' => $existStock->id,
                    'product_id' => $existStock->product_id,
                    'mrp' => $existStock->mrp ?? 0,
                    'unit_price' => $existStock->unit_price,
                    'unit_percentage' => $existStock->unit_percentage ?? 0,
                    'sale_price' => $existStock->sale_price,
                    'sale_percentage' => $existStock->sale_percentage ?? 0,
                    'purchase_quantity' => ($existStock->purchase_quantity + $purchaseProduct['quantity']),
                    'available_quantity' => ($existStock->available_quantity + $purchaseProduct['quantity']),
                    'type' => StockLog::TYPE_PURCHASE_UPDATE
                ]);

            }else{

                $stock = $this->stock
                    ->newQuery()
                    ->create([
                        'product_id' => $purchaseProduct['product']['id'],
                        'mrp' => $purchaseProduct['mrp'] ?? 0,
                        'sku' => make_sku($purchaseProduct['product']['id'], $purchaseProduct['unit_price'], $purchaseProduct['mrp']),
                        'unit_price' => $purchaseProduct['unit_price'],
                        'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                        'sale_price' => $purchaseProduct['sale_price'],
                        'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                        'purchase_quantity' => $purchaseProduct['quantity'],
                        'available_quantity' => $purchaseProduct['quantity'],
                    ]);

                $this->storeStockLog([
                    'stock_id' => $stock->id,
                    'product_id' => $purchaseProduct['product']['id'],
                    'mrp' => $purchaseProduct['mrp'] ?? 0,
                    'unit_price' => $purchaseProduct['unit_price'],
                    'unit_percentage' => $purchaseProduct['unit_percentage'] ?? 0,
                    'sale_price' => $purchaseProduct['sale_price'],
                    'sale_percentage' => $purchaseProduct['sale_percentage'] ?? 0,
                    'purchase_quantity' => $purchaseProduct['quantity'],
                    'available_quantity' => $purchaseProduct['quantity'],
                    'type' => StockLog::TYPE_PURCHASE_UPDATE
                ]);
            }

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        try {

            $this->model = $this->model
                ->newQuery()
                ->where('id', $id)
                ->first();

            $this->model->purchaseProducts()->delete();

            $this->model->delete();

            return response()->json(['success' => __t('purchase_delete')]);

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
            "current_id" => "required|exists:purchases,id",
            "totalPaid" => "required|numeric",
            "paymentStatus" => "required|in:" . implode(',', Purchase::getValidationConst('PAYMENT_STATUS_')),
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

                $existPurchase = $this->model
                    ->newQuery()
                    ->where('id', request()->get('current_id'))
                    ->first();

                $paymentIds = $existPurchase->payments()->pluck('id')->toArray();
                $newPaymentIds = collect(request()->get('formData'))->pluck('id')->toArray();
                $deleteAbleIds = array_diff($paymentIds, $newPaymentIds);

                $existPurchase->payments()->whereIn('id', $deleteAbleIds)->delete();

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
                            'paymentable_type' => Purchase::class,
                            'paymentable_id' => request()->get('current_id'),
                            'payment_for' => Payment::PAYMENT_FOR_PURCHASE,
                            'cash_flow' => Payment::CASH_FLOW_OUT,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                Payment::query()->insert($salesPayment);

                $existPurchase->update([
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
