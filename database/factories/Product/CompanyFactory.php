<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Company>
 */
class CompanyFactory extends Factory
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
            'name' => fake()->unique()->company(),
            'email' => fake()->unique()->email(),
            'phone_number' => fake()->unique()->phoneNumber(),
            'description' => fake()->realText(),
            'status' => $a[$k],
        ];
    }
}
