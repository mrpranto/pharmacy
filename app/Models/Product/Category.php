<?php

namespace App\Models\Product;

use App\Models\trait\ActiveScope;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, BootTrait, CreatedByRelationship, ActiveScope, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'description', 'status', 'created_by', 'updated_by'
    ];
}
