<?php

namespace App\Http\Controllers;

use App\Models\ReturPembelian;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;

class ReturPembelianController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->responseFactory->view("retur-pembelian.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->responseFactory->view("retur-pembelian.create");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReturPembelian  $returPembelian
     * @return \Illuminate\Http\Response
     */
    public function edit(ReturPembelian $returPembelian)
    {
        return $this->responseFactory->view("retur-pembelian.edit", [
            "returPembelian" => $returPembelian,
        ]);
    }
}
