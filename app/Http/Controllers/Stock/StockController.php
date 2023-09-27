<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Services\Stock\StockServices;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;

class StockController extends Controller
{
    public function __construct(StockServices $stockServices)
    {
        $this->services = $stockServices;
    }

    public function stockPage(): View
    {
        return view('pages.stocks.index');
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getStocks(): LengthAwarePaginator
    {
        return $this->services->getStocks();
    }
}
