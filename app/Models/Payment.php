<?php

namespace App\Models;

use App\Models\trait\BootTrait;
use App\Models\trait\GetConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, BootTrait, GetConst, SoftDeletes;

    const TYPE_CASH = 'CASH';
    const TYPE_BANK = 'BANK';
    const TYPE_BKSH = 'BKASH';
    const TYPE_NAGOD = 'NAGOD';


    const PAYMENT_FOR_PURCHASE = 'purchase';
    const PAYMENT_FOR_SALE  = 'sale';
    const PAYMENT_FOR_EXPENSE = 'expense';
    const PAYMENT_FOR_INCOME = 'income';


    const CASH_FLOW_IN = 'in';
    const CASH_FLOW_OUT = 'out';

    protected $fillable = [
        'date', 'paid_amount', 'type', 'account_number', 'bank_name', 'transaction_number',
        'paymentable', 'payment_for', 'cash_flow', 'created_by', 'updated_by',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'paymentable_type', 'paymentable_id'
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];
}
