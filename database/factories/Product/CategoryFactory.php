<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Category>
 */
class CategoryFactory extends Factory
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
            'name' => fake()->unique()->firstName(),
            'description' => fake()->realText,
            'status' => $a[$k]
        ];
    }
}
