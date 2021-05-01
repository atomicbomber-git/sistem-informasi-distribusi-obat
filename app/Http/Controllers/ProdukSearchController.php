<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $paginator = Produk::query()
            ->when($request->query("term"), function (Builder $builder, $term) {
                $builder
                    ->where("nama", "like", "%$term%")
                    ->orWhere("satuan", "like", "%$term%")
                ;
            })
            ->orderBy("nama")
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                ->map(function (Produk $produk) {
                    return [
                        "id" => $produk->kode,
                        "text" => "{$produk->nama} ({$produk->satuan})",
                    ];
                }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
