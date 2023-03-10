<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    /**
     * UserLoginController constructor.
     * @param \App\Services\Api\CustomerService $customerService
     */
    public function __construct(
        private \App\Services\Api\CustomerService $customerService
    )
    {}

    /**
     * Controller method for user login API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->all();
        $token = json_decode($this->customerService->login($credentials), true);

        if (!$token) {
            return response()->json(['message' => 'Password incorrect or account suspended.']);
        }

        return response()->json(['token' => $token]);
    }
}
