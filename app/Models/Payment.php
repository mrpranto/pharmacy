<?php

namespace App\Models;

use App\Models\trait\BootTrait;
use App\Models\trait\GetConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, BootTrait, GetConst;

    const TYPE_CASH = 'CASH';
    const TYPE_BANK = 'BANK';
    const TYPE_BKSH = 'BKASH';
    const TYPE_NAGOD = 'NAGOD';

    protected $fillable = [
        'paid_amount', 'type', 'account_number',
        'transaction_number', 'paymentable', 'created_by', 'updated_by',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'paymentable_type', 'paymentable_id'
    ];
}
