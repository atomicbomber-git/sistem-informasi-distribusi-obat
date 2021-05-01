<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\QueryBuilders\ProdukBuilder;
use App\Support\Formatter;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukInHandSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $paginator = Produk::query()
            ->hasQuantityInHand()
            ->withQuantityInHand()
            ->when($request->query("term"), function (ProdukBuilder $builder, $term) {
                $builder->filterBy($term, ["nama", "satuan"]);
            })
            ->orderBy("nama")
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                    ->map(function (Produk $produk) {
                        return [
                            "id" => "$produk->kode",
                            "text" => sprintf(
                                "%s (%s) [%s]",
                                $produk->nama,
                                $produk->satuan,
                                Formatter::normalizedNumeral($produk->quantity_in_hand)
                            )
                        ];
                    }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
