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
    use HasFactory, SoftDeletes, BootTrait, CreatedByRelationship, ProductRelationship;

    protected $fillable = [
        'stock_id', 'product_id', 'unit_price', 'sale_price', 'purchase_quantity',
        'sale_quantity', 'available_quantity', 'created_by', 'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
