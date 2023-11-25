<?php

namespace App\Models\Purchase;

use App\Models\Payment;
use App\Models\People\Supplier;
use App\Models\trait\BootTrait;
use App\Models\trait\ConstantByPrefix;
use App\Models\trait\CreatedByRelationship;
use App\Models\trait\GetConst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, ConstantByPrefix, SoftDeletes, BootTrait, CreatedByRelationship,GetConst;

    const STATUS_RECEIVED = 'received';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';

    const PAYMENT_STATUS_PAID = 'PAID';
    const PAYMENT_STATUS_DUE = 'DUE';
    const PAYMENT_STATUS_PARTIAL_PAID = 'PARTIAL-PAID';
    const PAYMENT_STATUS_ORVER_DUE = 'OVER-DUE';

    protected $fillable = [
        'supplier_id', 'date', 'status', 'reference', 'subtotal', 'purchase_details',
        'otherCost', 'discount', 'total', 'created_by', 'updated_by', 'note',
        'total_paid', 'payment_status'
    ];

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function purchaseProducts(): HasMany
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }
}
