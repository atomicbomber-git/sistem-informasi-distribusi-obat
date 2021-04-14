<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use App\Models\ReturPenjualan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class ReturPenjualanController extends Controller
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
     * @param FakturPenjualan $fakturPenjualan
     * @return Response
     */
    public function index(FakturPenjualan $fakturPenjualan)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @return Response
     */
    public function create(FakturPenjualan $fakturPenjualan)
    {
        return $this->responseFactory->view("retur-penjualan.create", [
            "fakturPenjualan" => $fakturPenjualan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param FakturPenjualan $fakturPenjualan
     * @return Response
     */
    public function store(Request $request, FakturPenjualan $fakturPenjualan)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @param ReturPenjualan $returPenjualan
     * @return Response
     */
    public function show(FakturPenjualan $fakturPenjualan, ReturPenjualan $returPenjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @param ReturPenjualan $returPenjualan
     * @return Response
     */
    public function edit(FakturPenjualan $fakturPenjualan, ReturPenjualan $returPenjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param FakturPenjualan $fakturPenjualan
     * @param ReturPenjualan $returPenjualan
     * @return Response
     */
    public function update(Request $request, FakturPenjualan $fakturPenjualan, ReturPenjualan $returPenjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @param ReturPenjualan $returPenjualan
     * @return Response
     */
    public function destroy(FakturPenjualan $fakturPenjualan, ReturPenjualan $returPenjualan)
    {
        //
    }
}
