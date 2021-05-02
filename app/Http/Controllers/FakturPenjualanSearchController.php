<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FakturPenjualanSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $paginator = FakturPenjualan::query()
            ->orderBy("nomor")
            ->when($request->get("term"), function (Builder $builder, string $searchTerm) {
                $builder->where("nomor", "LIKE", "%{$searchTerm}%");
            })
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                    ->map(function (FakturPenjualan $fakturPenjualan) {
                        return [
                            "id" => $fakturPenjualan->getKey(),
                            "text" => $fakturPenjualan->getPrefixedNomor(),
                        ];
                    }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
