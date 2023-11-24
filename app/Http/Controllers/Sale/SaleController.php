<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Services\Sales\SalesServices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function Termwind\render;

class SaleController extends Controller
{
    public function __construct(SalesServices $salesServices)
    {
        $this->services = $salesServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('app.sales.index');

        return view('pages.sale.index', $this->services->accessPermissions());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('app.sales.create');

        return view('pages.sale.create', $this->services->createDependencies());
    }


    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getSalesList(): array
    {
        Gate::authorize('app.sales.index');

        return $this->services->getSalesList();
    }

    /**
     * Get Customers for add sale.
     *
     * @return Collection|array
     */
    public function getCustomers(): Collection|array
    {
        Gate::authorize('app.sales.create');

        return $this->services->getCustomers();
    }

    /**
     * Get Products for add sale.
     *
     * @return Collection|array
     */
    public function getProducts(): Collection|array
    {
        Gate::authorize('app.sales.create');

        return $this->services->getProducts();
    }

    /**
     * @return View
     */
    public function salesPreview(): View
    {
        Gate::authorize('app.sales.show');

        Cache::put(auth()->user()->email, request()->all());

        return view('pages.sale.preview');
    }

    /**
     * @return StreamedResponse
     */
    public function salesPdf(): StreamedResponse
    {
        Gate::authorize('app.sales.show');

        return $this->services->renderPdf();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('app.sales.create');

        return $this->services
            ->validateStoreAndUpdate($request)
            ->storeSale($request);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function changeStatus($id): JsonResponse
    {
        Gate::authorize('app.sales.status-change');

        return $this->services->changeStatus($id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        Gate::authorize('app.sales.show');

        return view('pages.sale.invoice', ['id' => $id]);
    }

    /**
     * @param $id
     * @return StreamedResponse
     */
    public function invoicePdf($id): StreamedResponse
    {
        Gate::authorize('app.sales.show');

        return $this->services->renderInvoicePdf($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        Gate::authorize('app.sales.edit');

        return view('pages.sale.edit', $this->services->getEditableData($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        Gate::authorize('app.sales.edit');

        return $this->services
            ->validateStoreAndUpdate($request)
            ->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        Gate::authorize('app.sales.delete');

        return $this->services->deleteSale($id);
    }

    /**
     * @return JsonResponse
     */
    public function paymentSave(): JsonResponse
    {
        Gate::authorize('app.sales.payment-add');

        return $this->services
            ->validatePayment()
            ->savePayment();
    }
}
