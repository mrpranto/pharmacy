<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Services\Product\Units\UnitServices;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * @param UnitServices $unitServices
     */
    public function __construct(UnitServices $unitServices)
    {
        $this->services = $unitServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.products.units.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
