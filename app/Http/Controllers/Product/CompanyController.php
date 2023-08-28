<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Company;
use App\Services\Product\Companies\CompaniesServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompanyController extends Controller
{
    /**
     * @param CompaniesServices $companiesServices
     */
    public function __construct(CompaniesServices $companiesServices)
    {
        $this->services = $companiesServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('app.company.index');

        return view('pages.products.companies.index', $this->services->accessPermissions());
    }

    /**
     * @return LengthAwarePaginator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getCompanies(): LengthAwarePaginator
    {
        Gate::authorize('app.company.index');

        return $this->services->getCompanies();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('app.company.create');

        return $this->services->validate($request)->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): array|Company
    {
        Gate::authorize('app.company.show');

        return $this->services->showDetails($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('app.company.edit');

        return $this->services->validateUpdate($request, $id)->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('app.company.delete');

        return $this->services->delete($id);
    }
}
