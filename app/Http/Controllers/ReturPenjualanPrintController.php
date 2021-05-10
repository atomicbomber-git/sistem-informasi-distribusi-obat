<?php

namespace App\Http\Controllers;

use App\Models\ReturPenjualan;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class ReturPenjualanPrintController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware("auth");
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ReturPenjualan $returPenjualan)
    {
        return $this->responseFactory->view("retur-penjualan.print", [
            "returPenjualan" => $returPenjualan,
            "itemReturPenjualanPages" => $returPenjualan->itemReturPenjualans()
                ->with([
                    "mutasiStockPenjualan.itemFakturPenjualan",
                    "mutasiStockPenjualan.stock",
                ])
                ->get()
                ->chunk(8)
        ]);
    }
}
