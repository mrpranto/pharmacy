<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportServices;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(ReportServices $reportServices)
    {
        $this->services = $reportServices;
    }

    public function summary()
    {
        return view('pages.report.summary', $this->services->summary());
    }
}
