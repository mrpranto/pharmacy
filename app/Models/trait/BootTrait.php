<?php

namespace App\Models\trait;

use App\Models\User;

trait BootTrait
{
    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->fill([
                'created_by' => auth()->id() ?? User::query()->first()->id,
                'updated_by' => auth()->id() ?? User::query()->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        static::updating(function ($model) {
            $model->fill([
                'updated_at' => now(),
                'updated_by' => auth()->id() ?? User::query()->first()->id,
            ]);
        });
    }
}
