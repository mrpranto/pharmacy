<?php

namespace App\Rules;

use App\Models\Stock\Stock;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CheckAvailableQuantity implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $availableQty = $this->getQty($attribute);
        if ($availableQty < $value){
            $fail("Available quantity is {$availableQty}.");
        }
    }

    /**
     * @param $attribute
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getQty($attribute): mixed
    {
        $attribute = explode('.', $attribute);
        $requestProducts = request()->get('products');
        $key = (int) $attribute[1];
        $salePrice = $requestProducts[$key]['original_sale_price'];
        $productId = $requestProducts[$key]['product']['id'];
        $stock = Stock::query()
            ->where('product_id', $productId)
            ->where('sale_price', $salePrice)
            ->first();

        if ($stock && $stock->available_quantity){
            return $stock->available_quantity;
        }else{
            return 0;
        }
    }
}
