<?php

namespace App\Models\Stock;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use App\Models\trait\ProductRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory, SoftDeletes, BootTrait, CreatedByRelationship, ProductRelationship;

    protected $fillable = [
        'product_id', 'unit_price', 'unit_percentage', 'mrp', 'sale_price', 'sale_percentage',
        'purchase_quantity', 'sale_quantity', 'available_quantity', 'created_by', 'updated_by',
    ];

    /**
     * @return HasMany
     */
    public function stockLog(): HasMany
    {
        return $this->hasMany(StockLog::class, 'stock_id', 'id');
    }
}
