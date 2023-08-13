<?php

namespace App\Services\Product\Units;

use App\Models\Product\Unit;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UnitServices extends BaseServices
{
    /**
     * @param Unit $unit
     */
    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.unit.create'),
                'edit' => auth()->user()->can('app.unit.edit'),
                'delete' => auth()->user()->can('app.unit.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getUnits(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('pack_size', 'like', '%' . request()->get('search') . '%')
                ->orWhere('name', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('status'), fn($q) => $q->where('status', request()->get('status')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
            ->paginate(request()->get('per_page') ?? pagination());
    }

    /**
     * @param $request
     * @return $this
     */
    public function validateStore($request): static
    {
        $request->validate([
            'name' => 'required|string',
            'pack_size' => 'required',
            'description' => 'nullable',
            'status' => 'required|boolean',
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
                'pack_size' => $request->pack_size,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('unit_create')]);

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
        $request->validate([
            'name' => 'required|string',
            'pack_size' => 'required',
            'description' => 'nullable',
            'status' => 'required|boolean',
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
                    'pack_size' => $request->pack_size,
                    'name' => $request->name,
                    'description' => $request->description,
                    'status' => $request->status,
                ]);

            return response()->json(['success' => __t('unit_edit')]);

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

            return response()->json(['success' => __t('unit_edit')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
