<?php

namespace App\Models\trait;

trait ActiveScope
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('status', true);
    }
}
