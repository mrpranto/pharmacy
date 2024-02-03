<?php

namespace App\Models\Product;

use App\Models\trait\ActiveScope;
use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, BootTrait, CreatedByRelationship, ActiveScope, SoftDeletes;

    protected $fillable = [
      'name', 'details', 'status', 'created_by', 'updated_by'
    ];

    protected $casts = [
      'details' => 'json'
    ];
}
