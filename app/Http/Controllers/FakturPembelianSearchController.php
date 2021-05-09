<?php

namespace App\Http\Controllers;

use App\Models\FakturPembelian;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FakturPembelianSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $paginator = FakturPembelian::query()
            ->whereDoesntHave("returPembelian")
            ->when($request->get("term"), function (Builder $builder, string $searchTerm) {
                $builder->where("kode", "LIKE", "%{$searchTerm}%");
            })
            ->orderByDesc("waktu_penerimaan")
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                    ->map(function (FakturPembelian $fakturPembelian) {
                        return [
                            "id" => $fakturPembelian->getKey(),
                            "text" => $fakturPembelian->kode,
                        ];
                    }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
