<?php

namespace App\Http\Controllers;

use App\Models\FakturPembelian;
use App\Models\ItemFakturPenjualan;
use App\Models\MutasiStock;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemFakturPembelianSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request, FakturPembelian $fakturPembelian): JsonResponse
    {
        $paginator = MutasiStock::query()
            ->with("item_faktur_pembelian.produk")
            ->whereHas("item_faktur_pembelian", function (Builder $builder) use ($fakturPembelian) {
                $builder->where("faktur_pembelian_kode", $fakturPembelian->kode);
            })->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                    ->map(function (MutasiStock $mutasiStock) {
                        return [
                            "id" => $mutasiStock->item_faktur_pembelian->id,
                            "text" => sprintf(
                                "%s (KODE BATCH: %s) [%s]",
                                $mutasiStock->stock->produk->nama,
                                $mutasiStock->stock->kode_batch,
                                $mutasiStock->jumlah,
                            )
                        ];
                    }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
