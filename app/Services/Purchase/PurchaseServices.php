<?php

namespace App\Services\Purchase;

use App\Models\People\Supplier;
use App\Models\Product\Product;
use App\Models\Purchase\Purchase;
use App\Models\Stock\Stock;
use App\Models\Stock\StockLog;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
                'delete' => auth()->user()->can('app.purchase.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getPurchaseList(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->select(['purchases.*', 'suppliers.name as supplier_name'])
            ->with(['supplier', 'purchaseProducts'])
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->when(request()->get('date'), function ($q) {
                $dates = explode(' to ', request()->get('date'));
                $q->whereBetween('date', [$dates[0], $dates[1]]);
            })
            ->when(request()->filled('supplier'), fn($q) => $q->where('supplier_id', request()->get('supplier')))
            ->when(request()->filled('purchase_status'), fn($q) => $q->where('status', request()->get('purchase_status')))
            ->when(request()->filled('search'), fn($q) => $q->where('reference', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), function ($q) {
                if (request()->get('order_by') == 'supplier') {
                    return $q->orderBy('supplier_name', request()->get('order_dir'));
                } else {
                    return $q->orderBy(request()->get('order_by'), request()->get('order_dir'));
                }
            })
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'))
            ->paginate(request()->get('per_page') ?? pagination());
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
            ->with(['category:id,name', 'company:id,name', 'unit:id,name,pack_size', 'stocks'])
            ->when(request()->filled('search'), function ($q) {
                $q->where('barcode', 'like', "%" . request()->get('search') . "%")
                    ->orWhere('name', 'like', "%" . request()->get('search') . "%");
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
            'products.*.unit_price' => 'required|numeric|min:1',
            'products.*.sale_price' => 'required|numeric|min:1|gt:products.*.unit_price',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.discountAllow' => 'required|boolean',
            'products.*.discount' => 'nullable|numeric',
            'products.*.discount_type' => 'nullable|string',
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
                        'unit_price' => $purchaseProduct['unit_price'],
                        'sale_price' => $purchaseProduct['sale_price'],
                        'quantity' => $purchaseProduct['quantity'],
                        'discountAllow' => $purchaseProduct['discountAllow'],
                        'discount' => $purchaseProduct['discount'],
                        'discount_type' => $purchaseProduct['discount_type'],
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
                    'discountAllow' => $purchaseProduct['discountAllow'],
                    'discount' => $purchaseProduct['discount'],
                    'discount_type' => $purchaseProduct['discount_type'],
                ]);

                $this->storeStockLog([
                    'stock_id' => $existStock->id,
                    'product_id' => $existStock->product_id,
                    'unit_price' => $existStock->unit_price,
                    'sale_price' => $existStock->sale_price,
                    'purchase_quantity' => ($existStock->purchase_quantity + $purchaseProduct['quantity']),
                    'available_quantity' => ($existStock->available_quantity + $purchaseProduct['quantity']),
                    'discountAllow' => $purchaseProduct['discountAllow'],
                    'discount' => $purchaseProduct['discount'],
                    'discount_type' => $purchaseProduct['discount_type'],
                    'type' => StockLog::TYPE_PURCHASE
                ]);

            }else{

                $stock = $this->stock
                    ->newQuery()
                    ->create([
                        'product_id' => $purchaseProduct['product']['id'],
                        'unit_price' => $purchaseProduct['unit_price'],
                        'sale_price' => $purchaseProduct['sale_price'],
                        'purchase_quantity' => $purchaseProduct['quantity'],
                        'available_quantity' => $purchaseProduct['quantity'],
                        'discountAllow' => $purchaseProduct['discountAllow'],
                        'discount' => $purchaseProduct['discount'],
                        'discount_type' => $purchaseProduct['discount_type'],
                    ]);

                $this->storeStockLog([
                    'stock_id' => $stock->id,
                    'product_id' => $purchaseProduct['product']['id'],
                    'unit_price' => $purchaseProduct['unit_price'],
                    'sale_price' => $purchaseProduct['sale_price'],
                    'purchase_quantity' => $purchaseProduct['quantity'],
                    'available_quantity' => $purchaseProduct['quantity'],
                    'discountAllow' => $purchaseProduct['discountAllow'],
                    'discount' => $purchaseProduct['discount'],
                    'discount_type' => $purchaseProduct['discount_type'],
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
            'products.*.unit_price' => 'required|numeric|min:1',
            'products.*.sale_price' => 'required|numeric|min:1',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.discountAllow' => 'required|boolean',
            'products.*.discount' => 'nullable|numeric',
            'products.*.discount_type' => 'nullable|string',
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
                                'unit_price' => $purchaseProduct['unit_price'],
                                'sale_price' => $purchaseProduct['sale_price'],
                                'quantity' => $purchaseProduct['quantity'],
                                'discountAllow' => $purchaseProduct['discountAllow'],
                                'discount' => $purchaseProduct['discount'],
                                'discount_type' => $purchaseProduct['discount_type'],
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
                                'unit_price' => $purchaseProduct['unit_price'],
                                'sale_price' => $purchaseProduct['sale_price'],
                                'quantity' => $purchaseProduct['quantity'],
                                'discountAllow' => $purchaseProduct['discountAllow'],
                                'discount' => $purchaseProduct['discount'],
                                'discount_type' => $purchaseProduct['discount_type'],
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
                    'discountAllow' => $purchaseProduct['discountAllow'],
                    'discount' => $purchaseProduct['discount'],
                    'discount_type' => $purchaseProduct['discount_type'],
                ]);

                $this->storeStockLog([
                    'stock_id' => $existStock->id,
                    'product_id' => $existStock->product_id,
                    'unit_price' => $existStock->unit_price,
                    'sale_price' => $existStock->sale_price,
                    'purchase_quantity' => ($existStock->purchase_quantity + $purchaseProduct['quantity']),
                    'available_quantity' => ($existStock->available_quantity + $purchaseProduct['quantity']),
                    'discountAllow' => $purchaseProduct['discountAllow'],
                    'discount' => $purchaseProduct['discount'],
                    'discount_type' => $purchaseProduct['discount_type'],
                    'type' => StockLog::TYPE_PURCHASE_UPDATE
                ]);

            }else{

                $stock = $this->stock
                    ->newQuery()
                    ->create([
                        'product_id' => $purchaseProduct['product']['id'],
                        'unit_price' => $purchaseProduct['unit_price'],
                        'sale_price' => $purchaseProduct['sale_price'],
                        'purchase_quantity' => $purchaseProduct['quantity'],
                        'available_quantity' => $purchaseProduct['quantity'],
                        'discountAllow' => $purchaseProduct['discountAllow'],
                        'discount' => $purchaseProduct['discount'],
                        'discount_type' => $purchaseProduct['discount_type'],
                    ]);

                $this->storeStockLog([
                    'stock_id' => $stock->id,
                    'product_id' => $purchaseProduct['product']['id'],
                    'unit_price' => $purchaseProduct['unit_price'],
                    'sale_price' => $purchaseProduct['sale_price'],
                    'purchase_quantity' => $purchaseProduct['quantity'],
                    'available_quantity' => $purchaseProduct['quantity'],
                    'discountAllow' => $purchaseProduct['discountAllow'],
                    'discount' => $purchaseProduct['discount'],
                    'discount_type' => $purchaseProduct['discount_type'],
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
}
