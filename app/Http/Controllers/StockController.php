<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class StockController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware("auth");
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function index(Produk $produk)
    {
        return $this->responseFactory->view("stock.index", compact("produk"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function create(Produk $produk)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Produk $produk)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @param  \App\Models\Stock  $stock_batch
     * @return \Illuminate\Http\Response
     */
    public function show(Produk $produk, Stock $stock_batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @param  \App\Models\Stock  $stock_batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Produk $produk, Stock $stock_batch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @param  \App\Models\Stock  $stock_batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produk $produk, Stock $stock_batch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @param  \App\Models\Stock  $stock_batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk $produk, Stock $stock_batch)
    {
        //
    }
}
