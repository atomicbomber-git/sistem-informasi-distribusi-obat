<?php

namespace App\Http\Controllers;

use App\Enums\MessageState;
use App\Models\Pelanggan;
use App\Support\SessionHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware("auth");
        $this->responseFactory = $responseFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->responseFactory->view("pelanggan.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->responseFactory->view("pelanggan.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            "nama" => ["required", "string", Rule::unique(Pelanggan::class)],
            "alamat" => ["nullable", "string"],
        ]);

        Pelanggan::query()->create($data);

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("pelanggan.index");
    }

    /**
     * Display the specified resource.
     *
     * @param Pelanggan $pelanggan
     * @return Response
     */
    public function show(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Pelanggan $pelanggan
     * @return Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        return $this->responseFactory->view("pelanggan.edit", [
            "pelanggan" => $pelanggan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Pelanggan $pelanggan
     * @return RedirectResponse
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate([
            "nama" => ["required", "string", Rule::unique(Pelanggan::class)->ignoreModel($pelanggan)],
            "alamat" => ["nullable", "string"],
        ]);

        $pelanggan->update($data);

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("pelanggan.edit", $pelanggan);
    }
}
