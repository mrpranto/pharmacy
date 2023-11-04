<?php

namespace App\Models\trait;

use ReflectionClass;

trait GetConst
{
    public static function getConst($prefix): array
    {
        $reflectionClass = new ReflectionClass(self::class);

        return array_filter($reflectionClass->getConstants(),
            fn ($constant) => strpos($constant, $prefix) === 0, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param $prefix
     * @return array
     */
    public static function getValidationConst($prefix): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        $constants = array_filter($reflectionClass->getConstants(),
            fn ($constant) => strpos($constant, $prefix) === 0, ARRAY_FILTER_USE_KEY);

        return array_values($constants);
    }
}
