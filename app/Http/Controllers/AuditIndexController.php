<?php

namespace App\Http\Controllers;

use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Livewire\Livewire;

class AuditIndexController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->authorize(AuthServiceProvider::CAN_VIEW_AUDIT_DATA);
        return $this->responseFactory->view("audit.index");
    }
}
