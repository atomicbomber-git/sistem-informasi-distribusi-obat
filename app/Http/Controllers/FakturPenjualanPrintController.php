<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use App\Models\MutasiStock;
use Illuminate\Database\Eloquent\Builder;
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

            "mutasiStockPages" => MutasiStock::query()
                ->select(
                    "mutasi_stock.id", "mutasi_stock.item_faktur_penjualan_id",
                    "stock_id"
                )
                ->selectRaw("-mutasi_stock.jumlah AS jumlah")
                ->selectRaw("-mutasi_stock.jumlah * item_faktur_penjualan.harga_satuan * (100 - item_faktur_penjualan.diskon) / 100 AS jumlah_harga_per_baris")
                ->with([
                    "itemFakturPenjualan:id,faktur_penjualan_id,produk_kode,harga_satuan,diskon",
                    "itemFakturPenjualan.produk:kode,nama",
                    "stock:id,kode_batch,expired_at",
                ])
                ->joinRelationship("itemFakturPenjualan.produk")
                ->whereHas("itemFakturPenjualan", function (Builder $builder) use ($fakturPenjualan) {
                    $builder->where("faktur_penjualan_id", $fakturPenjualan->id);
                })
                ->orderBy("produk.nama")
                ->get()
                ->chunk(8),

            "itemFakturPenjualanPages" => ItemFakturPenjualan::query()
                ->select("id", "faktur_penjualan_id", "produk_kode", "jumlah", "diskon", "harga_satuan")
                ->with([
                    "produk:kode,nama",
                    "mutasiStocks",
                    "mutasiStocks.stock:id,kode_batch,expired_at",
                ])
                ->get()
                ->chunk(8),

            "jumlahHargaTanpaDiskonTanpaPajak" => ItemFakturPenjualan::query()
                ->where("faktur_penjualan_id", $fakturPenjualan->getKey())
                ->selectRaw("COALESCE(SUM(harga_satuan * jumlah), 0) AS sum")
                ->value("sum"),

            "jumlahHargaDenganDiskonDanPajak" => ItemFakturPenjualan::query()
                ->selectRaw("COALESCE(SUM(harga_satuan * jumlah * (100 - item_faktur_penjualan.diskon - faktur_penjualan.diskon) / 100), 0)  AS sum")
                ->where("faktur_penjualan_id", $fakturPenjualan->getKey())
                ->joinRelationship("fakturPenjualan")
                ->value("sum"),
        ]);
    }
}
