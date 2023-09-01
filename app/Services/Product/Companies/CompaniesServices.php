<?php

namespace App\Services\Product\Companies;

use App\Models\Product\Company;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompaniesServices extends BaseServices
{
    /**
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->model = $company;
    }

    /**
     * @return array
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.company.create'),
                'edit' => auth()->user()->can('app.company.edit'),
                'show' => auth()->user()->can('app.company.show'),
                'delete' => auth()->user()->can('app.company.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCompanies(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('status'), fn($q) => $q->where('status', request()->get('status')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), function ($q) {
                $q->orderBy(request()->get('order_by'), request()->get('order_dir'));
            })
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'))
            ->paginate(request()->get('per_page') ?? pagination());
    }

    /**
     * @param $request
     * @return $this
     */
    public function validate($request): static
    {
        $request->validate([
            'name' => 'required|string|unique:companies,name',
            'email' => 'nullable|email|unique:companies,email',
            'phone_number' => 'nullable|unique:companies,phone_number',
            'description' => 'nullable',
            'status' => 'required|boolean'
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
            $this->model->fill([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'description' => $request->description,
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('company_create')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'name' => 'required|string|unique:companies,name,' . $id,
            'email' => 'nullable|email|unique:companies,email,' . $id,
            'phone_number' => 'nullable|unique:companies,phone_number,' . $id,
            'description' => 'nullable',
            'status' => 'required|boolean'
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

            $this->model->newQuery()
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'description' => $request->description,
                    'status' => $request->status,
                ]);

            return response()->json(['success' => __t('company_edit')]);

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

            return response()->json(['success' => __t('company_delete')]);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $id
     * @return array|Company
     */
    public function showDetails($id): array|Company
    {
        $company = $this->model
            ->newQuery()
            ->with(['createdBy', 'updatedBy'])
            ->where('id', $id)
            ->first();

        $this->model = $company->toArray();
        $this->model['created_at'] = $company->created_at->format(format_date()).' '.$company->created_at->format(format_time());
        $this->model['updated_at'] = $company->updated_at->format(format_date()).' '.$company->updated_at->format(format_time());

        return $this->model;
    }
}
