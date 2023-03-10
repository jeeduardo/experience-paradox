<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * @var \App\Services\Api\RegisteredCustomerCartService
     */
    private $registeredCustomerCartService;

    /**
     * CartController constructor.
     * @param \App\Services\Api\RegisteredCustomerCartService $registeredCustomerCartService
     */
    public function __construct(
        \App\Services\Api\RegisteredCustomerCartService $registeredCustomerCartService
    ) {
        $this->registeredCustomerCartService = $registeredCustomerCartService;
    }

    /**
     * Initialize cart session
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $token = $request->post('token');
        $result = $this->registeredCustomerCartService->initCart($token);

        return response()->json($result);
    }
}
