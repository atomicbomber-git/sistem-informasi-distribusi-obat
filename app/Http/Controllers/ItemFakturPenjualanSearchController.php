<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use App\Models\ItemFakturPenjualan;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemFakturPenjualanSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, FakturPenjualan $fakturPenjualan): JsonResponse
    {
        $paginator = ItemFakturPenjualan::query()
            ->select(
                "mutasi_stock.id AS id",
                "produk.nama AS nama_produk",
                "stock.kode_batch AS kode_batch",
                DB::raw("-mutasi_stock.jumlah AS jumlah")
            )
            ->when($request->get("term"), function (Builder $builder, string $term) {
                $termParts = preg_split("/ +/ui", trim($term));
                foreach ($termParts as $termPart) {
                    $builder
                        ->orWhere("produk.nama", "LIKE", "%{$termPart}%")
                        ->orWhere("stock.kode_batch", "LIKE", "%{$termPart}%");
                }
            })
            ->joinRelationship("produk")
            ->joinRelationship("mutasiStocks.stock")
            ->where("faktur_penjualan_id", $fakturPenjualan->getKey())
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                    ->map(function (ItemFakturPenjualan $item) {
                        return [
                            "id" => $item->id,
                            "text" => sprintf(
                                "%s (KODE BATCH: %s) [%s]",
                                $item->nama_produk,
                                $item->kode_batch,
                                $item->jumlah,
                            )
                        ];
                    }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
