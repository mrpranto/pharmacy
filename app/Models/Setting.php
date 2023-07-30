<?php

namespace App\Models;

use App\Models\trait\BootTrait;
use App\Models\trait\CreatedByRelationship;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CreatedByRelationship, BootTrait;

    /**
     * @var string[]
     */
    protected $fillable = ['type', 'settings_info', 'created_by', 'updated_by'];

    /**
     * @var string[]
     */
    protected $casts = [
        'settings_info' => 'json'
    ];
}
