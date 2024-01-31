<?php

namespace App\Services\Expense;

use App\Models\Expense\Expense;
use App\Models\Payment;
use App\Models\trait\FileHandler;
use App\Services\BaseServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ExpenseServices extends BaseServices
{
    use FileHandler;

    public Payment $payment;

    public function __construct(Expense $expense, Payment $payment)
    {
        $this->model = $expense;
        $this->payment = $payment;
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
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getExpenses(): array
    {
        $expenses = $this->model->newQuery()
            ->with(['expanseAttachment'])
            ->when(request()->filled('search'),
                fn($q) => $q->where('title', 'like', '%' . request()->get('search') . '%')
                    ->orWhere('details', 'like', '%' . request()->get('search') . '%')
                    ->orWhere('total_amount', 'like', '%' . request()->get('search') . '%')
            )
            ->when(request()->filled('date'), function ($q) {
                $dates = explode(' to ', request()->get('date'));
                $q->whereDate('date', '>=', $dates[0])
                    ->whereDate('date', '<=', $dates[1]);
            })
            ->when(request()->filled('order_by') && request()->filled('order_dir'),
                fn($q) => $q->orderBy(request()->get('order_by'), request()->get('order_dir')))
            ->when(!request()->filled('order_by') && !request()->filled('order_dir'), fn($q) => $q->orderBy('id', 'desc'));

        return  [
            'expenses' => $expenses->paginate(request()->get('per_page') ?? pagination()),
            'totalAmount' => $expenses->sum('total_amount')
        ];
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

    /**
     * @param $request
     * @return JsonResponse
     */
    public function storeExpenses($request): JsonResponse
    {
        try {
            DB::transaction(function () use ($request){

                $this->model = $this->model->newQuery()->create([
                    'date' => date('Y-m-d H:i:s', strtotime($request->date)),
                    'title' => $request->title,
                    'item_details' => $request->item_details,
                    'details' => $request->details,
                    'total_amount' => $request->total_amount,
                ]);

                $this->payment
                    ->newQuery()
                    ->insert([
                        'paid_amount' => $request->total_amount,
                        'type' => Payment::TYPE_CASH,
                        'paymentable_type' => Expense::class,
                        'paymentable_id' => $this->model->id,
                        'payment_for' => Payment::PAYMENT_FOR_EXPENSE,
                        'cash_flow' => Payment::CASH_FLOW_OUT,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                if ($request->has('attachment')) {
                    $this->uploadAttachment($request->file('attachment'), $this->model);
                }

            });

            return response()->json(['success' => __t('expense_create_successful')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function updateExpense($request, $id): JsonResponse
    {
        try {
            DB::transaction(function () use ($request, $id){

                $this->model = $this->model
                    ->newQuery()
                    ->where('id', $id)
                    ->first();

                $this->model
                    ->update([
                        'date' => date('Y-m-d H:i:s', strtotime($request->date)),
                        'title' => $request->title,
                        'item_details' => $request->item_details,
                        'details' => $request->details,
                        'total_amount' => $request->total_amount,
                    ]);

                $this->model
                    ->payment()
                    ->update([
                        'paid_amount' => $request->total_amount
                    ]);

                if ($request->has('attachment')) {
                    $this->uploadAttachment($request->file('attachment'), $this->model);
                }

            });

            return response()->json(['success' => __t('expense_edit_successful')]);

        } catch (\Exception $exception) {
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
        $this->deleteFile(optional($expense->expanseAttachment)->path);

        $file_path = $this->storeFile($attachment, Expense::EXPANSE_ATTACHMENT);

        $expense->expanseAttachment()->updateOrCreate([
            'type' => Expense::EXPANSE_ATTACHMENT
        ], [
            'path' => $file_path
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteExpense($id): JsonResponse
    {
        try {

            DB::transaction(function () use ($id) {

                $expense = $this->getModelById($id);

                $this->deleteFile(optional($expense->expanseAttachment)->path);

                $expense->expanseAttachment()->delete();

                $expense->delete();

            });

            return response()->json(['success' => __t('expense_delete_successful')]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
