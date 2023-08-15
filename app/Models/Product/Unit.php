<?php

namespace App\Models\Product;

use App\Models\trait\ActiveScope;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory, BootTrait, CreatedByRelationship, ActiveScope;

    protected $fillable = [
        'pack_size', 'name', 'description', 'status', 'created_by', 'updated_by'
    ];
}
