<?php

namespace App\Services\Product\Products;

use App\Models\Product\Attribute;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use App\Models\trait\FileHandler;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProductServices extends BaseServices
{
    use FileHandler;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.product.create'),
                'edit' => auth()->user()->can('app.product.edit'),
                'show' => auth()->user()->can('app.product.show'),
                'delete' => auth()->user()->can('app.product.delete')
            ]
        ];
    }


    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getProducts(): array
    {
        $productsQuery = $this->model->newQuery()
            ->select(['products.*', 'categories.name as category_name', 'companies.name as company_name', 'units.name as unit_name'])
            ->with(['category', 'company', 'unit'])
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->when(request()->filled('search'), fn($q) => $q->where('products.name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('products.barcode', 'like', '%' . request()->get('search') . '%')
                ->orWhere('products.slug', 'like', '%' . request()->get('search') . '%')
                ->orWhere('categories.name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('companies.name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('units.name', 'like', '%' . request()->get('search') . '%')
            )
            ->when(request()->filled('category'), fn($q) => $q->where('category_id', request()->get('category')))
            ->when(request()->filled('company'), fn($q) => $q->where('company_id', request()->get('company')))
            ->when(request()->filled('unit'), fn($q) => $q->where('unit_id', request()->get('unit')))
            ->when(request()->filled('status'), fn($q) => $q->where('products.status', request()->get('status')))
            ->when(request()->filled('purchase_type'), fn($q) => $q->where('products.purchase_type', request()->get('purchase_type')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), function ($q) {
                if (request()->get('order_by') == 'category') {
                    return $q->orderBy('category_name', request()->get('order_dir'));
                } elseif (request()->get('order_by') == 'company') {
                    return $q->orderBy('company_name', request()->get('order_dir'));
                } elseif (request()->get('order_by') == 'unit') {
                    return $q->orderBy('unit_name', request()->get('order_dir'));
                } else {
                    return $q->orderBy(request()->get('order_by'), request()->get('order_dir'));
                }
            })
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'));

        return [
            'products' => $productsQuery->paginate(request()->get('per_page') ?? pagination()),
            'active_products' => $this->model->where('products.status', 1)->count(),
            'in_active_products' => $this->model->where('products.status', 0)->count(),
            'direct_price_products' => $this->model->where('products.purchase_type', '$')->count(),
            'percentage_products' => $this->model->where('products.purchase_type', '%')->count(),
        ];
    }

    /**
     * @return array
     */
    public function getDependency(): array
    {
        return [
          'categories' => Category::query()->active()->orderBy('id', 'desc')->get(['id', 'name']),
          'companies' => Company::query()->active()->orderBy('id', 'desc')->get(['id', 'name']),
          'units' => Unit::query()->active()->orderBy('id', 'desc')->get(['id', 'name', 'pack_size']),
          'attributes' => Attribute::query()->active()->orderBy('id', 'desc')->get(['id', 'name', 'details']),
        ];
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateStore($request): static
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'barcode' => 'required|unique:products,barcode',
            'category' => 'required|exists:categories,id',
            'company' => 'required|exists:companies,id',
            'unit' => 'required|exists:units,id',
            'description' => 'nullable|string',
            'status' => 'required|in:true,false',
            'product_photo' => 'nullable|image|max:2048',
            'purchase_type' => 'required|in:'.Product::PURCHASE_TYPE_PERCENTAGE.','.Product::PURCHASE_TYPE_DIRECT_PRICE
        ]);

        return $this;
    }

    /**
     * @param $request
     * @return JsonResponse|void
     */
    public function store($request)
    {
        try {

            DB::transaction(function () use ($request){

                $this->model = $this->model->newQuery()->create([
                    'category_id' => $request->category,
                    'company_id' => $request->company,
                    'unit_id' => $request->unit,
                    'barcode' => $request->barcode,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name.'-'.Str::uuid(), '-'),
                    'description' => $request->description,
                    'status' => $request->status === 'true' ? true : false,
                    'purchase_type' => $request->purchase_type,
                ]);

                if ($request->has('product_photo')){
                    $this->uploadProductPhoto($request->file('product_photo'), $this->model);
                }
            });

            return response()->json(['success' => __t('product_create')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $product_photo
     * @param $product
     * @return void
     */
    public function uploadProductPhoto($product_photo, $product): void
    {
        $this->deleteImage(optional($product->productPhoto)->path);

        $file_path = $this->uploadImage($product_photo, Product::PRODUCT_PHOTO_TYPE);

        $product->productPhoto()->updateOrCreate([
            'type' => Product::PRODUCT_PHOTO_TYPE
        ], [
            'path' => $file_path
        ]);
    }

    /**
     * @param $request
     * @param $id
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'name' => 'required|string',
            'barcode' => 'required|unique:products,barcode,'.$id,
            'category' => 'required|exists:categories,id',
            'company' => 'required|exists:companies,id',
            'unit' => 'required|exists:units,id',
            'description' => 'nullable|string',
            'status' => 'required|in:true,false',
            'product_photo' => 'nullable|image|max:2048',
            'purchase_type' => 'required|in:'.Product::PURCHASE_TYPE_PERCENTAGE.','.Product::PURCHASE_TYPE_DIRECT_PRICE
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
            DB::transaction(function () use ($request, $id){

                $this->model = $this->model->newQuery()
                    ->where('id', $id)
                    ->first();

                $this->model->update([
                    'category_id' => $request->category,
                    'company_id' => $request->company,
                    'unit_id' => $request->unit,
                    'barcode' => $request->barcode,
                    'name' => $request->name,
                    'slug' => Str::slug($request->name.'-'.Str::uuid(), '-'),
                    'description' => $request->description,
                    'status' => $request->status === 'true' ? true : false,
                    'purchase_type' => $request->purchase_type,
                ]);

                if ($request->has('product_photo')){
                    $this->uploadProductPhoto($request->file('product_photo'), $this->model);
                }
            });

            return response()->json(['success' => __t('product_edit')]);

        }catch (\Exception $exception){
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
            $this->model
                ->newQuery()
                ->where('id', $id)
                ->delete();

            return response()->json(['success' => __t('product_delete')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return Product|array
     */
    public function showDetails($id): Product|array
    {
        $product = $this->model
            ->newQuery()
            ->with(['createdBy', 'updatedBy', 'category', 'company', 'unit',])
            ->where('id', $id)
            ->first();

        $this->model = $product->toArray();
        $this->model['created_at'] = $product->created_at->format(format_date()).' '.$product->created_at->format(format_time());
        $this->model['updated_at'] = $product->updated_at->format(format_date()).' '.$product->updated_at->format(format_time());

        return $this->model;
    }

}
