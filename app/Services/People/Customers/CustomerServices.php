<?php

namespace App\Services\People\Customers;

use App\Models\People\Customer;
use App\Services\BaseServices;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CustomerServices extends BaseServices
{
    /**
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.customer.create'),
                'edit' => auth()->user()->can('app.customer.edit'),
                'show' => auth()->user()->can('app.customer.show'),
                'delete' => auth()->user()->can('app.customer.delete')
            ]
        ];
    }

    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCustomers(): array
    {
        $customers = $this->model->newQuery()
            ->where('id', '!=', 1)
            ->when(request()->filled('search'), fn($q) => $q->where('phone_number', 'like', '%' . request()->get('search') . '%')
                ->orWhere('name', 'like', '%' . request()->get('search') . '%')
                ->orWhere('email', 'like', '%' . request()->get('search') . '%')
            )
            ->when(request()->filled('status'), fn($q) => $q->where('status', request()->get('status')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'),
                fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'))
            ->paginate(request()->get('per_page') ?? pagination());

        return [
            'customers' => $customers,
            'active_customers' => $this->model
                ->newQuery()
                ->where('id', '!=', 1)
                ->where('status', 1)
                ->count(),
            'in_active_customers' => $this->model
                ->newQuery()
                ->where('id', '!=', 1)
                ->where('status', 0)
                ->count(),
        ];
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateStore($request): static
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|numeric|unique:customers,phone_number',
            'email' => 'nullable|email|unique:customers,email',
            'address' => 'nullable',
            'status' => 'required|nullable',
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
            $this->model->newQuery()->create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'status' => $request->status,
            ]);
            return response()->json(['success' => __t('customer_create')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
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
            'phone_number' => 'required|numeric|unique:customers,phone_number,'.$id,
            'email' => 'nullable|email|unique:customers,email,'.$id,
            'address' => 'nullable',
            'status' => 'required|nullable',
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
            $this->model->newQuery()->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'status' => $request->status,
            ]);
            return response()->json(['success' => __t('customer_edit')]);

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
            $this->model->newQuery()->where('id', $id)->delete();

            return response()->json(['success' => __t('customer_delete')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return Customer|array
     */
    public function showDetails($id): Customer|array
    {
        $customer = $this->model
            ->newQuery()
            ->with(['createdBy', 'updatedBy'])
            ->where('id', $id)
            ->first();

        $this->model = $customer->toArray();
        $this->model['created_at'] = $customer->created_at->format(format_date()).' '.$customer->created_at->format(format_time());
        $this->model['updated_at'] = $customer->updated_at->format(format_date()).' '.$customer->updated_at->format(format_time());

        return $this->model;
    }
}
