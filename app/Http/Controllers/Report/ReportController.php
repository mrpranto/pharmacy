<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportServices;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function __construct(ReportServices $reportServices)
    {
        $this->services = $reportServices;
    }

    /**
     * @return View
     */
    public function summary(): View
    {
        return view('pages.report.summary', $this->services->summary());
    }

    /**
     * @return View
     */
    public function purchasePage(): View
    {
        return view('pages.report.purchase',['suppliers' => $this->services->suppliers()]);
    }

    /**
     * @return array
     */
    public function getPurchaseData(): array
    {
        set_time_limit(300);

        return $this->services->getPurchaseData();
    }
}
