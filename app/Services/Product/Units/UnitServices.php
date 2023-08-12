<?php

namespace App\Services\Product\Units;

use App\Models\Product\Unit;
use App\Services\BaseServices;

class UnitServices extends BaseServices
{
    /**
     * @param Unit $unit
     */
    public function __construct(Unit $unit)
    {
        $this->model = $unit;
    }

    /**
     * @return array[]
     */
    public function accessPermissions(): array
    {
        return [
            'permission' => [
                'create' => auth()->user()->can('app.unit.create'),
                'edit' => auth()->user()->can('app.unit.edit'),
                'delete' => auth()->user()->can('app.unit.delete')
            ]
        ];
    }
}
