<?php

namespace App\Models\People;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, BootTrait, CreatedByRelationship;

    protected $fillable = [
        'name', 'phone_number', 'email', 'address',
        'companies', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'companies' => 'array',
    ];
}
