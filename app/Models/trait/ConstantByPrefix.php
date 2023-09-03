<?php

namespace App\Models\trait;

trait ConstantByPrefix
{
    /**
     * @param $prefix
     * @return array
     */
    public static function getConstantsByPrefix($prefix): array
    {
        $reflectionClass = new \ReflectionClass(self::class);

        $constants = array_filter($reflectionClass->getConstants(), function ($constant) use ($prefix) {
            return strpos($constant, $prefix) === 0;
        }, ARRAY_FILTER_USE_KEY);

        return array_values($constants);
    }
}
