<?php

namespace App\Services\People\Suppliers;

use App\Models\People\Supplier;
use App\Models\Product\Company;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SupplierServices extends BaseServices
{
    /**
     * @param Supplier $supplier
     */
    public function __construct(Supplier $supplier)
    {
        $this->model = $supplier;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.supplier.create'),
                'edit' => auth()->user()->can('app.supplier.edit'),
                'delete' => auth()->user()->can('app.supplier.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSuppliers(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('phone_number', 'like', '%' . request()->get('search') . '%')
                ->orWhere('name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('email', 'like', '%' . request()->get('search') . '%')
            )
            ->when(request()->filled('order_by') && request()->filled('order_dir'),
                fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'))
            ->paginate(request()->get('per_page') ?? pagination());
    }

    /**
     * @return array
     */
    public function getDependency(): array
    {
        return [
            'companies' => Company::query()->active()->get(['id', 'name'])
        ];
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateStore($request): static
    {
        $request->validate([
            "name" => "required|string",
            "phone_number" => "required|numeric",
            "email" => "nullable|email",
            "address" => "nullable|string",
            "companies" => 'nullable|array'
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
            $companies = Company::query()
                ->whereIn('id', $request->companies)
                ->get(['id', 'name'])->toArray();

            $this->model->newQuery()->create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'companies' => json_encode($companies)
            ]);

            return response()->json(['success' => __t('supplier_create')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateUpdate($request): static
    {
        $this->validateStore($request);

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

            $companies = Company::query()
                ->whereIn('id', $request->companies)
                ->get(['id', 'name'])->toArray();

            $this->model->newQuery()
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'address' => $request->address,
                    'companies' => json_encode($companies)
                ]);

            return response()->json(['success' => __t('supplier_edit')]);

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

            $this->model->newQuery()->where('id', $id)->delete();

            return response()->json(['success' => __t('supplier_delete')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}






