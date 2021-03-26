<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class FakturPenjualanController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->responseFactory->view("faktur-penjualan.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->responseFactory->view("faktur-penjualan.create");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FakturPenjualan  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function show(FakturPenjualan $fakturPenjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FakturPenjualan  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(FakturPenjualan $fakturPenjualan)
    {
        return $this->responseFactory->view("faktur-penjualan.edit", [
            "fakturPenjualan" => $fakturPenjualan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FakturPenjualan  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FakturPenjualan $fakturPenjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FakturPenjualan  $fakturPenjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FakturPenjualan $fakturPenjualan)
    {
        //
    }
}
