<?php

namespace App\Models\Product;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, BootTrait, CreatedByRelationship;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'description', 'status', 'created_by', 'updated_by'
    ];
}
