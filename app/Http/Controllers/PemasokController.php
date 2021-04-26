<?php

namespace App\Http\Controllers;

use App\Enums\MessageState;
use App\Models\Pemasok;
use App\Support\SessionHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\Rule;

class PemasokController extends Controller
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
        return $this->responseFactory->view("pemasok.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return $this->responseFactory->view("pemasok.create");
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
            "nama" => ["required", "string", Rule::unique(Pemasok::class)],
            "alamat" => ["nullable", "string"],
        ]);

        Pemasok::query()->create($data);

        SessionHelper::flashMessage(
            __("messages.create.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("pemasok.index");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Pemasok $pemasok
     * @return Response
     */
    public function edit(Pemasok $pemasok)
    {
        return $this->responseFactory->view("pemasok.edit", [
            "pemasok" => $pemasok,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Pemasok $pemasok
     * @return RedirectResponse
     */
    public function update(Request $request, Pemasok $pemasok)
    {
        $data = $request->validate([
            "nama" => ["required", "string", Rule::unique(Pemasok::class)->ignoreModel($pemasok)],
            "alamat" => ["nullable", "string"],
        ]);

        $pemasok->update($data);

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("pemasok.edit", $pemasok);
    }
}
