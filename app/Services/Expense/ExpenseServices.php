<?php

namespace App\Services\Expense;

use App\Models\Expense\Expense;
use App\Services\BaseServices;

class ExpenseServices extends BaseServices
{
    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }
}
