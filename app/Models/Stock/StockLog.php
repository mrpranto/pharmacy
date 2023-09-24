<?php

namespace App\Models\Stock;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use App\Models\trait\ProductRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockLog extends Model
{
    const TYPE_PURCHASE = 'purchase';
    const TYPE_PURCHASE_UPDATE = 'purchase_update';
    const TYPE_SALE = 'sale';

    use HasFactory, SoftDeletes, BootTrait, CreatedByRelationship, ProductRelationship;

    protected $fillable = [
        'stock_id', 'product_id', 'mrp', 'unit_price', 'unit_percentage',
        'sale_price', 'sale_percentage', 'purchase_quantity', 'sale_quantity',
        'available_quantity','created_by', 'updated_by', 'type'
    ];

    /**
     * @return BelongsTo
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
