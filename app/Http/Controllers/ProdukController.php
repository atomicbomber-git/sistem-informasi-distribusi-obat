<?php

namespace App\Http\Controllers;

use App\Enums\MessageState;
use App\Models\Produk;
use App\Support\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware('auth');
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->responseFactory->view("produk.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->responseFactory->view("produk.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "kode" => ["required", "string", Rule::unique(Produk::class)],
            "nama" => ["required", "string", Rule::unique(Produk::class)->where("satuan", $request->get("satuan"))],
            "satuan" => ["required", "string", Rule::unique(Produk::class)->where("nama", $request->get("nama"))],
            "harga_satuan" => ["required", "numeric", "gte:0"],
            "deskripsi" => ["nullable", "string"],
        ]);

        Produk::query()->create($data);

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("produk.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return Response
     */
    public function edit(Produk $produk)
    {
        return $this->responseFactory->view("produk.edit", [
            "produk"  => $produk,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Produk $produk)
    {
        $data = $request->validate([
            "kode" => ["required", "string", Rule::unique(Produk::class)->ignoreModel($produk)],
            "nama" => ["required", "string", Rule::unique(Produk::class)->where("satuan", $request->get("satuan"))->ignoreModel($produk)],
            "satuan" => ["required", "string", Rule::unique(Produk::class)->where("nama", $request->get("nama"))->ignoreModel($produk)],
            "harga_satuan" => ["required", "numeric", "gte:0"],
            "deskripsi" => ["nullable", "string"],
        ]);

        $produk->update($data);

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("produk.edit", $produk);
    }
}
