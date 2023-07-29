<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name',  'description'];

    /**
     * @return HasMany
     */
    public function permission(): HasMany
    {
        return $this->hasMany(Permission::class, 'module_id', 'id');
    }
}
