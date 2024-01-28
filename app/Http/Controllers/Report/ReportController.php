<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportServices;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    /**
     * @param ReportServices $reportServices
     */
    public function __construct(ReportServices $reportServices)
    {
        $this->services = $reportServices;
    }

    /**
     * @return View
     */
    public function summary(): View
    {
        Gate::authorize('app.report.summary');

        return view('pages.report.summary', $this->services->summary());
    }

    /**
     * @return View
     */
    public function purchasePage(): View
    {
        Gate::authorize('app.report.purchase');

        return view('pages.report.purchase',['suppliers' => $this->services->suppliers()]);
    }

    /**
     * @return array
     */
    public function getPurchaseData(): array
    {
        Gate::authorize('app.report.purchase');

        set_time_limit(300);

        return $this->services->getPurchaseData();
    }

    /**
     * @return View
     */
    public function salePage(): View
    {
        Gate::authorize('app.report.sales');

        return view('pages.report.sale',['customers' => $this->services->customers()]);
    }

    /**
     * @return array
     */
    public function getSalesData(): array
    {
        Gate::authorize('app.report.sales');

        set_time_limit(300);

        return $this->services->getSaleData();
    }

    /**
     * @return View
     */
    public function paymentPage(): View
    {
        return view('pages.report.payment');
    }

    public function getPaymentData()
    {
        set_time_limit(300);

        return $this->services->getPaymentData();
    }
}
