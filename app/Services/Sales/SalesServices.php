<?php

namespace App\Services\Sales;

use App\Models\People\Customer;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Services\BaseServices;
use Illuminate\Database\Eloquent\Collection;

class SalesServices extends BaseServices
{
    public Customer $customer;
    public Product $product;
    public Category $category;
    public Company $company;

    public function __construct(Customer $customer, Product $product, Category $category, Company $company)
    {
        $this->customer = $customer;
        $this->product = $product;
        $this->category = $category;
        $this->company = $company;
    }

    /**
     * Get Customers for add sale.
     *
     * @return Collection|array
     */
    public function getCustomers(): Collection|array
    {
        return $this->customer
            ->newQuery()
            ->when(request()->filled('search'),
                fn($query) => $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('phone_number', 'like', '%' . request('search') . '%'))
            ->take(10)
            ->orderByDesc('id')
            ->get(['id', 'name', 'phone_number']);
    }

    /**
     * Getting product for add new sale.
     *
     * @return Collection|array
     */
    public function getProducts(): Collection|array
    {
        return $this->product
            ->newQuery()
            ->with(['stocks'])
            ->whereHas('stocks')
            ->active()
            ->when(request()->filled('search'),
                fn($query) => $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('barcode', 'like', '%' . request('search') . '%'))
            ->when(request()->filled('category'),
                fn($query) => $query->where('category_id', request('category')))
            ->when(request()->filled('company'),
                fn($query) => $query->where('company_id', request('company')))
            ->take(100)
            ->get([
                'id', 'category_id', 'company_id', 'unit_id', 'barcode', 'name', 'purchase_type', 'status'
            ]);
    }

    /**
     * Sale create dependencies.
     *
     * @return array
     */
    public function createDependencies(): array
    {
        return [
            'categories' => $this->category
                ->newQuery()
                ->active()
                ->get(['id', 'name']),

            'companies' => $this->company
                ->newQuery()
                ->active()
                ->get(['id', 'name'])
        ];
    }

}
