<?php

namespace App\Http\Controllers;

use App\Enums\MessageState;
use App\Models\User;
use App\Support\SessionHelper;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\Rule;

class UpdateAdminUserController extends Controller
{
    private ResponseFactory $responseFactory;

    public function __construct(ResponseFactory $responseFactory)
    {
        $this->middleware("auth");
        $this->responseFactory = $responseFactory;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            "name" => ["required", "string"],
            "username" => ["required", "string", Rule::unique(User::class)->ignore($user)],
            "password" => ["nullable", "string", "confirmed"],
        ]);

        if ($data["password"] !== null) {
            $data["password"] = Hash::make($data["password"]);
        } else {
            unset($data["password"]);
        }

        $user->update($data);

        SessionHelper::flashMessage(
            __("messages.update.success"),
            MessageState::STATE_SUCCESS,
        );

        return $this->responseFactory->redirectToRoute("admin.user.edit");
    }
}
