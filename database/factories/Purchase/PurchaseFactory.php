<?php

namespace Database\Factories\Purchase;

use App\Models\People\Supplier;
use App\Models\Purchase\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $suppliers = Supplier::query()->pluck('id')->toArray();
        $supplier = array_rand($suppliers);

        $statuses = [Purchase::STATUS_RECEIVED, Purchase::STATUS_PENDING, Purchase::STATUS_CANCELED];
        $status = array_rand($statuses);
        return [
            'supplier_id' => $suppliers[$supplier],
            'date' => fake()->date(),
            'status' => $statuses[$status],
            'reference' => fake()->unique()->randomNumber(9, 10),
            'subtotal' => fake()->randomNumber(8),
            'otherCost' => fake()->randomNumber(4),
            'discount' => fake()->randomNumber(3),
            'total' => fake()->randomNumber(9),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
            'note' => fake()->realText
        ];
    }
}
