<?php

namespace App\Models\Sale;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleProducts extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id', 'product_id', 'mrp', 'original_sale_price',
        'sale_price', 'sale_percentage', 'subtotal', 'sale_product_details',
    ];

    /**
     * @return BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
