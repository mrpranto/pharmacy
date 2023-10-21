<?php

namespace App\Models\Sale;

use App\Models\People\Customer;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory, CreatedByRelationship;

    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_CANCELED = 'CANCELED';
    const STATUS_DELIVERED = 'DELIVERED';

    protected $fillable = [
        'invoice_number', 'invoice_date', 'customer_id', 'total_unit', 'subtotal',
        'other_cost', 'discount', 'grand_total', 'invoice_details', 'created_by', 'updated_by'
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

    public static function boot(): void
    {
        $maxId = (self::query()->max('id') + 1);

        parent::boot();

        static::creating(function ($model) use ($maxId) {
            $model->fill([
                'invoice_number' => str_pad($maxId, 8, '0', STR_PAD_LEFT),
                'created_by' => auth()->id() ?? optional(User::query()->first())->id,
                'updated_by' => auth()->id() ?? optional(User::query()->first())->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        static::updating(function ($model) {
            $model->fill([
                'updated_at' => now(),
                'updated_by' => auth()->id() ?? optional(User::query()->first())->id,
            ]);
        });
    }
}
