<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $a = [true, false];
        $k = array_rand($a);
        return [
            'pack_size' => fake()->bloodType(),
            'name' => fake()->unique()->slug(2),
            'description' => fake()->realText,
            'status' => $a[$k],
        ];
    }
}
