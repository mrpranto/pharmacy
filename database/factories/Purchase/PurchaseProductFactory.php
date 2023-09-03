<?php

namespace Database\Factories\Purchase;

use App\Models\Product\Product;
use App\Models\Purchase\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase\PurchaseProduct>
 */
class PurchaseProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $purchases = Purchase::query()->pluck('id')->toArray();
        $purchase = array_rand($purchases);

        $products = Product::query()->pluck('id')->toArray();
        $product = array_rand($products);

        $a = [true, false];
        $k = array_rand($a);

        return [
            'purchase_id' => $purchases[$purchase],
            'product_id' => $products[$product],
            'unit_price' => fake()->randomNumber(4),
            'sale_price' => fake()->randomNumber(4),
            'quantity' => fake()->randomNumber(3),
            'discountAllow' => $a[$k],
            'discount' => fake()->randomNumber(4),
            'discount_type' => '%',
            'subTotal' => fake()->randomNumber(4),
        ];
    }
}
