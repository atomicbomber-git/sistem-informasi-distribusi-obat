<?php

namespace App\Http\Controllers;

use App\Models\ReturPembelian;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class ReturPembelianPrintController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ReturPembelian $returPembelian)
    {
        return $this->responseFactory->view("retur-pembelian.print", [
            "returPembelian" => $returPembelian,
            "itemReturPembelianPages" => $returPembelian->itemReturPembelians()
                ->with([
                    "itemFakturPembelian.faktur_pembelian",
                    "itemFakturPembelian.produk",
                    "itemFakturPembelian.mutasiStock.stock",
                ])
                ->get()
                ->chunk(8)
        ]);
    }
}
