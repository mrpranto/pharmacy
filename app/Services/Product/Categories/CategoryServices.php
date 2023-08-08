<?php

namespace App\Services\Product\Categories;

use App\Models\Product\Category;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CategoryServices extends BaseServices
{
    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
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
                'delete' => auth()->user()->can('app.category.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCategories(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%'))
            ->orderBy('id', 'desc')
            ->paginate(request()->get('per_page') ?? pagination());
    }

    /**
     * @param $request
     * @return $this
     */
    public function validate($request): static
    {
        $request->validate([
            'name' => 'required|string|unique:categories,id',
            'description' => 'nullable|string',
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
                'description' => $request->description,
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('category_create')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'name' => 'required|string|unique:categories,id,'.$id,
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        return $this;
    }
    public function update($request, $id): JsonResponse
    {
        try {

            $this->model = $this->model->newQuery()
                ->where('id', $id)
                ->first();

            $this->model->fill([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('category_edit')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
