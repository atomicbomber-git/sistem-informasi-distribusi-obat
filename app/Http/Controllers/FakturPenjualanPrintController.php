<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class FakturPenjualanPrintController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(FakturPenjualan $fakturPenjualan): Response
    {
        return $this->responseFactory->view("faktur-penjualan.print", [
            "fakturPenjualan" => $fakturPenjualan,
            
            "itemFakturPenjualanPages" => ItemFakturPenjualan::query()
                ->select("id", "faktur_penjualan_id", "produk_kode", "jumlah", "diskon", "harga_satuan")
                ->with([
                    "produk:kode,nama",
                    "mutasiStocks",
                    "mutasiStocks.stock:id,kode_batch,expired_at",
                ])
                ->get()
                ->chunk(8),
            
            "totalDiskonCd" => ItemFakturPenjualan::query()
                ->selectRaw("COALESCE(SUM(item_faktur_penjualan.harga_satuan * item_faktur_penjualan.jumlah * faktur_penjualan.diskon), 0) AS aggregate")
                ->where("faktur_penjualan_id", $fakturPenjualan->getKey())
                ->joinRelationship("fakturPenjualan")
                ->value("aggregate"),

            "totalDiskonTd" => ItemFakturPenjualan::query()
                ->selectRaw("COALESCE(SUM(harga_satuan * jumlah * diskon / 100), 0) AS aggregate")
                ->where("faktur_penjualan_id", $fakturPenjualan->getKey())
                ->value("aggregate"),

            "total" => ItemFakturPenjualan::query()
                ->where("faktur_penjualan_id", $fakturPenjualan->getKey())
                ->selectRaw("
                    COALESCE(SUM(item_faktur_penjualan.harga_satuan * item_faktur_penjualan.jumlah ), 0) AS aggregate
                ")
                ->joinRelationship("fakturPenjualan")
                ->value("aggregate")
        ]);
    }
}
