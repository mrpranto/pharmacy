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
        $purchases = Purchase::query()
            ->pluck('id')
            ->toArray();

        $purchase = array_rand($purchases);

        $products = Product::query()
            ->active()
            ->pluck('id')
            ->toArray();

        $product = array_rand($products);

        $mrp = fake()->randomNumber(4);
        $unitPercentage = rand(1, 50);
        $salePercentage = ($unitPercentage - 5) > 0 ? ($unitPercentage - 5) : 0;
        $salePrice = ($mrp - (($mrp * $salePercentage) / 100));

        $purchaseInformation = [
            'purchase_id' => $purchases[$purchase],
            'product_id' => $products[$product],
            'mrp' => $mrp,
            'unit_price' => ($mrp - (($mrp * $unitPercentage) / 100)),
            'unit_percentage' => $unitPercentage,
            'sale_price' => $salePrice,
            'sale_percentage' => $salePercentage,
            'quantity' => fake()->randomNumber(3),
            'subTotal' => ($mrp - (($mrp - $unitPercentage) / 100)) * fake()->randomNumber(3),
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
            ->where('unit_percentage', $purchaseInformation['unit_percentage'])
            ->where('sale_percentage', $purchaseInformation['sale_percentage'])
            ->first();

        if ($checkExistStock){
            $checkExistStock->update([
                'purchase_quantity' => $checkExistStock->purchase_quantity + $purchaseInformation['quantity'],
                'available_quantity' => $checkExistStock->available_quantity + $purchaseInformation['quantity'],
            ]);
        }else{
            Stock::query()->create([
                'product_id' => $purchaseInformation['product_id'],
                'mrp' => $purchaseInformation['mrp'],
                'sku' => make_sku($purchaseInformation['product_id'], $purchaseInformation['sale_price'], $purchaseInformation['mrp']),
                'unit_price' => $purchaseInformation['unit_price'],
                'unit_percentage' => $purchaseInformation['unit_percentage'],
                'sale_price' => $purchaseInformation['sale_price'],
                'sale_percentage' => $purchaseInformation['sale_percentage'],
                'purchase_quantity' => $purchaseInformation['quantity'],
                'sale_quantity' => 0,
                'available_quantity' => $purchaseInformation['quantity'],
            ]);
        }
    }
}
