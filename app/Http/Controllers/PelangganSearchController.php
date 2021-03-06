<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PelangganSearchController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $paginator = Pelanggan::query()
            ->when($request->query("term"), function (Builder $builder, $term) {
                $builder->where("nama", "like", "%$term%");
            })
            ->orderBy("nama")
            ->paginate();

        return $this->responseFactory->json([
            "results" =>
                collect($paginator->items())
                ->map(function (Pelanggan $pelanggan) {
                    return [
                        "id" => $pelanggan->getKey(),
                        "text" => $pelanggan->nama,
                    ];
                }),
            "pagination" => [
                "more" => $paginator->hasMorePages(),
            ]
        ]);
    }
}
