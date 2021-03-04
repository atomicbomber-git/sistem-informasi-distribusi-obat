<?php

namespace App\Http\Controllers;

use App\Models\FakturPembelian;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class FakturPembelianController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware('auth');
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->responseFactory->view("faktur-pembelian.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->responseFactory->view("faktur-pembelian.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FakturPembelian  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function show(FakturPembelian $fakturPenjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FakturPembelian  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(FakturPembelian $fakturPenjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FakturPembelian  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FakturPembelian $fakturPenjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FakturPembelian  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FakturPembelian $fakturPenjualan)
    {
        //
    }
}
