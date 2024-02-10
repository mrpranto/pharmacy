<?php

namespace App\Models\Product;

use App\Models\trait\ProductRelationship;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use ProductRelationship;

    protected $fillable = [
        'product_id', 'key', 'value'
    ];

}
