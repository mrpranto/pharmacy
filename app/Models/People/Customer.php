<?php

namespace App\Models\People;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BootTrait, CreatedByRelationship;

    protected $fillable = [
        'name', 'email', 'phone_number', 'address',
        'status', 'created_by', 'updated_by'
    ];
}
