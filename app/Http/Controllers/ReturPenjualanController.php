<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use App\Models\ReturPenjualan;
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

    public function index(): Response
    {
        return $this->responseFactory->view("retur-penjualan.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @return Response
     */
    public function create()
    {
        return $this->responseFactory->view("retur-penjualan.create");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FakturPenjualan $fakturPenjualan
     * @param ReturPenjualan $returPenjualan
     * @return Response
     */
    public function edit(ReturPenjualan $returPenjualan)
    {
        return $this->responseFactory->view("retur-penjualan.edit", [
            "returPenjualan" => $returPenjualan,
        ]);
    }
}
