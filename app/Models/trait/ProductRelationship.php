<?php

namespace App\Models\trait;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ProductRelationship
{
    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
