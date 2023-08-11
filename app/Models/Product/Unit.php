<?php

namespace App\Models\Product;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory, BootTrait, CreatedByRelationship;

    protected $fillable = [
        'pack_size', 'name', 'short_name', 'description', 'status', 'created_by', 'updated_by'
    ];
}
