<?php

namespace Database\Factories\People;

use App\Models\Product\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\People\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'phone_number' => fake()->phoneNumber,
            'email' => fake()->email,
            'address' => fake()->address,
            'companies' => json_encode($this->companies(fake()->numberBetween(3,10))),
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
