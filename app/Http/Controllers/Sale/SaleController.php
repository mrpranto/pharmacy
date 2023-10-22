<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Services\Sales\SalesServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(SalesServices $salesServices)
    {
        $this->services = $salesServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.sale.create', $this->services->createDependencies());
    }

    /**
     * Get Customers for add sale.
     *
     * @return Collection|array
     */
    public function getCustomers(): Collection|array
    {
        return $this->services->getCustomers();
    }

    /**
     * Get Products for add sale.
     *
     * @return Collection|array
     */
    public function getProducts(): Collection|array
    {
        return $this->services->getProducts();
    }

    /**
     * @return View
     */
    public function salesPreview(): View
    {
        Cache::put(auth()->user()->email, request()->all());

        return view('pages.sale.preview');
    }

    public function salesPdf()
    {
        return $this->services->renderPdf();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->services
            ->validateStore($request)
            ->storeSale($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
