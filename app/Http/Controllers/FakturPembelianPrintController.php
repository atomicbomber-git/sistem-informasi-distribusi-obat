<?php

namespace App\Http\Controllers;

use App\Models\FakturPembelian;
use App\Models\ItemFakturPembelian;
use App\Models\ItemFakturPenjualan;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class FakturPembelianPrintController extends Controller
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
    public function __invoke(Request $request, FakturPembelian $fakturPembelian)
    {
        return $this->responseFactory->view("faktur-pembelian.print", [
            "itemFakturPembelianPages" => ItemFakturPembelian::query()
                ->where("faktur_pembelian_kode", $fakturPembelian->getKey())
                ->select("*")
                ->selectRaw("item_faktur_pembelian.jumlah * item_faktur_pembelian.harga_satuan AS jumlah_harga_per_baris")
                ->joinRelationship("produk")
                ->orderBy("produk.nama")
                ->get()
                ->chunk(8),

            "total_harga" => ItemFakturPembelian::query()
                ->where("faktur_pembelian_kode", $fakturPembelian->getKey())
                ->sum(DB::raw("jumlah * harga_satuan")),

            "fakturPembelian" => $fakturPembelian,
        ]);
    }
}
