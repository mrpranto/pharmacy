<?php

namespace App\Models\Sale;

use App\Models\Payment;
use App\Models\People\Customer;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use App\Models\trait\GetConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, CreatedByRelationship, BootTrait, SoftDeletes, GetConst;

    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_DELIVERED = 'DELIVERED';

    const PAYMENT_STATUS_PAID = 'PAID';
    const PAYMENT_STATUS_DUE = 'DUE';
    const PAYMENT_STATUS_PARTIAL_PAID = 'PARTIAL-PAID';
    const PAYMENT_STATUS_ORVER_DUE = 'OVER-DUE';

    protected $fillable = [
        'invoice_number', 'invoice_date', 'customer_id', 'total_unit_qty', 'subtotal', 'status', 'payment_status',
        'total_paid', 'other_cost', 'discount', 'grand_total', 'invoice_details', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'invoice_date' => 'datetime'
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function saleProducts(): HasMany
    {
        return $this->hasMany(SaleProducts::class, 'sale_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
