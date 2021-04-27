<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class FakturPenjualanPrintController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(FakturPenjualan $fakturPenjualan)
    {
        return $this->responseFactory->view("faktur-penjualan.print", [
            "fakturPenjualan" => $fakturPenjualan,
        ]);
    }
}
