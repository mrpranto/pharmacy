<?php

namespace Database\Factories\Product;

use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $booleans = [true, false];
        $status = array_rand($booleans);

        $categories = Category::query()->active()->pluck('id')->toArray();
        $category = array_rand($categories);

        $companies = Company::query()->active()->pluck('id')->toArray();
        $company = array_rand($companies);

        $units = Unit::query()->active()->pluck('id')->toArray();
        $unit = array_rand($units);

        return [
            'category_id' => $categories[$category],
            'company_id' => $companies[$company],
            'unit_id' => $units[$unit],
            'barcode' => fake()->unique()->numerify('############'),
            'name' => fake()->domainWord,
            'slug' => fake()->slug,
            'description' => fake()->realText,
            'status' => $booleans[$status],
        ];
    }
}
