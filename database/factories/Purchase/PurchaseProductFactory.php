<?php

namespace Database\Factories\Purchase;

use App\Models\Product\Product;
use App\Models\Purchase\Purchase;
use App\Models\Stock\Stock;
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
        $purchases = Purchase::query()->where('status', Purchase::STATUS_RECEIVED)->pluck('id')->toArray();
        $purchase = array_rand($purchases);

        $products = Product::query()->active()->pluck('id')->toArray();
        $product = array_rand($products);

        $a = [true, false];
        $k = array_rand($a);

        $purchaseInformation = [
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

        $this->storeStock($purchaseInformation);

        return $purchaseInformation;
    }

    /**
     * @param $purchaseInformation
     * @return void
     */
    public function storeStock($purchaseInformation): void
    {
        $checkExistStock = Stock::query()
            ->where('product_id', $purchaseInformation['product_id'])
            ->where('unit_price', $purchaseInformation['unit_price'])
            ->where('sale_price', $purchaseInformation['sale_price'])
            ->first();

        if ($checkExistStock){
            $checkExistStock->update([
                'purchase_quantity' => $checkExistStock->purchase_quantity + $purchaseInformation['quantity'],
                'available_quantity' => $checkExistStock->available_quantity + $purchaseInformation['quantity'],
                'discountAllow' => $purchaseInformation['discountAllow'],
                'discount' => $purchaseInformation['discount'],
                'discount_type' => $purchaseInformation['discount_type'],
            ]);
        }else{
            Stock::query()->create([
                'product_id' => $purchaseInformation['product_id'],
                'unit_price' => $purchaseInformation['unit_price'],
                'sale_price' => $purchaseInformation['sale_price'],
                'purchase_quantity' => $purchaseInformation['quantity'],
                'sale_quantity' => 0,
                'available_quantity' => $purchaseInformation['quantity'],
                'discountAllow' => $purchaseInformation['discountAllow'],
                'discount' => $purchaseInformation['discount'],
                'discount_type' => $purchaseInformation['discount_type'],
            ]);
        }
    }
}
