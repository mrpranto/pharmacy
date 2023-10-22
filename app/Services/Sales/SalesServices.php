<?php

namespace App\Services\Sales;

use App\Models\People\Customer;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Models\Sale\Sale;
use App\Models\Stock\Stock;
use App\Models\Stock\StockLog;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesServices extends BaseServices
{
    public Customer $customer;
    public Product $product;
    public Category $category;
    public Company $company;
    public Stock $stock;

    public function __construct(
        Customer $customer,
        Product  $product,
        Category $category,
        Company  $company,
        Sale     $sale,
        Stock    $stock
    )
    {
        $this->customer = $customer;
        $this->product = $product;
        $this->category = $category;
        $this->company = $company;
        $this->model = $sale;
        $this->stock = $stock;
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
    public function validateStore($request): static
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
            'products.*.quantity' => 'required|numeric',
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
        if (request()->filled('type')){
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
                $max_id = ($this->model->newQuery()->max('id') + 1);
                $invoice_number = str_pad($max_id, '00000000', STR_PAD_LEFT);

                $this->model = $this->model
                    ->newQuery()
                    ->create([
                        'invoice_number' => $invoice_number,
                        'invoice_date' => now(),
                        'customer_id' => $request->customer,
                        'total_unit_qty' => $request->totalUnitQuantity,
                        'subtotal' => $request->totalSubTotal,
                        'status' => $this->status(),
                        'other_cost' => $request->otherCost,
                        'discount' => $request->discount,
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

        }catch (\Exception $exception){
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

}
