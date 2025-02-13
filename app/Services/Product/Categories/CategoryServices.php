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
    public function getCategories(): array
    {
        $categoriesQuery = $this->model->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('name', 'like', '%' . request()->get('search') . '%'))
            ->when(request()->filled('status'), fn($q) => $q->where('status', request()->get('status')))
            ->when(request()->filled('order_by') && request()->filled('order_dir'), fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'));

        return [
            'categories' => $categoriesQuery->paginate(request()->get('per_page') ?? pagination()),
            'active_categories' => $this->model->newQuery()->where('status', 1)->count(),
            'in_active_categories' => $this->model->newQuery()->where('status', 0)->count(),
        ];
    }

    /**
     * @param $request
     * @return $this
     */
    public function validate($request): static
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
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

    /**
     * @param $request
     * @param $id
     * @return $this
     */
    public function validateUpdate($request, $id): static
    {
        $request->validate([
            'name' => 'required|string|unique:categories,id,'.$id,
            'description' => 'nullable|string',
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
                'description' => $request->description,
                'status' => $request->status,
            ])->save();

            return response()->json(['success' => __t('category_edit')]);

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

            return response()->json(['success' => __t('category_delete')]);

        }catch (\Exception $exception){
            return exception_message_handle($exception);
        }
    }

    /**
     * @param $id
     * @return array|Category
     */
    public function showDetails($id): array|Category
    {
        $category = $this->model
            ->newQuery()
            ->with(['createdBy', 'updatedBy'])
            ->where('id', $id)
            ->first();

        $this->model = $category->toArray();
        $this->model['created_at'] = $category->created_at->format(format_date()).' '.$category->created_at->format(format_time());
        $this->model['updated_at'] = $category->updated_at->format(format_date()).' '.$category->updated_at->format(format_time());

        return $this->model;
    }
}
