<?php

namespace App\Services\Product\Attributes;

use App\Models\Product\Attribute;
use App\Models\Product\Category;
use App\Services\BaseServices;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AttributeServices extends BaseServices
{
    /**
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
    }

    /**
     * @return array
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.category.create'),
                'edit' => auth()->user()->can('app.category.edit'),
                'show' => auth()->user()->can('app.category.show'),
                'delete' => auth()->user()->can('app.category.delete')
            ]
        ];
    }


    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAttributes(): array
    {
        $attributeQuery = $this->model
            ->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('status'), fn($q) => $q->where('status', request()->get('status')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'));

        return [
            'attributes' => $attributeQuery->paginate(request()->get('per_page') ?? pagination()),
            'active_attributes' => $this->model->newQuery()->where('status', 1)->count(),
            'in_active_attributes' => $this->model->newQuery()->where('status', 0)->count(),
        ];
    }

    /**
     * @param $request
     * @return $this
     */
    public function validate($request): static
    {
        $request->validate([
            'name' => 'required|string|unique:attributes,name',
            'details' => 'required|array',
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
                'details' => $this->sanitizeDetail($request->details),
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('attribute_create')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $requestDetails
     * @return array
     */
    public function sanitizeDetail($requestDetails): array
    {
        $details = [];
        foreach ($requestDetails as $detail){
            if (isset($detail['name']) && $detail['name'] != null){
                $details[] = [
                    'name' => $detail['name'],
                    'note' => $detail['note'] == null ? '' : $detail['note']
                ];
            }
        }
        return $details;
    }

    /**
     * @param $request
     * @param $id
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'name' => 'required|string|unique:attributes,id,'.$id,
            'details' => 'required|array',
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

            $this->model = $this->model->newQuery()
                ->where('id', $id)
                ->first();

            $this->model->fill([
                'name' => $request->name,
                'details' => $this->sanitizeDetail($request->details),
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('attribute_edit')]);

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

            return response()->json(['success' => __t('attribute_delete')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }


    /**
     * @param $id
     * @return Attribute|array
     */
    public function showDetails($id): Attribute|array
    {
        $attribute = $this->model
            ->newQuery()
            ->with(['createdBy', 'updatedBy'])
            ->where('id', $id)
            ->first();

        $this->model = $attribute->toArray();
        $this->model['created_at'] = $attribute->created_at->format(format_date()).' '.$attribute->created_at->format(format_time());
        $this->model['updated_at'] = $attribute->updated_at->format(format_date()).' '.$attribute->updated_at->format(format_time());

        return $this->model;
    }
}
