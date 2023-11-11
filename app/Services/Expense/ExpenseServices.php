<?php

namespace App\Services\Expense;

use App\Models\Expense\Expense;
use App\Models\trait\FileHandler;
use App\Services\BaseServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ExpenseServices extends BaseServices
{
    use FileHandler;

    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.expenses.create'),
                'edit' => auth()->user()->can('app.expenses.edit'),
                'show' => auth()->user()->can('app.expenses.show'),
                'delete' => auth()->user()->can('app.expenses.delete')
            ]
        ];
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getExpenses(): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->when(request()->filled('search'), fn($q) => $q->where('title', 'like', '%' . request()->get('search') . '%')
                ->orWhere('details', 'like', '%' . request()->get('search') . '%')
            )
            ->when(request()->filled('order_by') && request()->filled('order_dir'),
                fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
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
            'title' => 'required|string',
            'date' => 'required',
            'details' => 'nullable|string',
            'item_details' => 'nullable',
            'total_amount' => 'nullable|numeric',
            'attachment' => 'nullable|file|max:1000'
        ]);
        return $this;
    }

    public function storeExpenses($request)
    {
        try {
            $this->model = $this->model->newQuery()->create([
                'date' => date('Y-m-d H:i:s'),
                'title' => $request->title,
                'item_details' => $request->item_details,
                'details' => $request->details,
                'total_amount' => $request->total_amount,
            ]);

            if ($request->has('attachment')){
                $this->uploadAttachment($request->file('attachment'), $this->model);
            }

            return response()->json(['success' => __t('expense_create_successful')]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $attachment
     * @param $expense
     * @return void
     */
    public function uploadAttachment($attachment, $expense): void
    {
        $this->deleteImage(optional($expense->expanseAttachment)->path);

        $file_path = $this->storeFile($attachment, Expense::EXPANSE_ATTACHMENT);

        $expense->expanseAttachment()->updateOrCreate([
            'type' => Expense::EXPANSE_ATTACHMENT
        ], [
            'path' => $file_path
        ]);
    }
}
