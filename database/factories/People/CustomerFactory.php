<?php

namespace Database\Factories\People;

use App\Models\Product\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\People\Supplier>
 */
class CustomerFactory extends Factory
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
            'name' => fake()->name,
            'phone_number' => fake()->phoneNumber,
            'email' => fake()->email,
            'address' => fake()->address,
            'status' => $a[$k]
        ];
    }

    /**
     * @param $take
     * @return mixed
     */
    public function companies($take): mixed
    {
        return Company::query()
            ->active()
            ->offset($take)
            ->take($take)
            ->get(['id', 'name'])
            ->toArray();
    }
}
