<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRegistrationController extends Controller
{
    public function __construct(
        private \App\Services\Api\CustomerService $customerService
    ) {}

    /**
     * Controller method for API user registration
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $data = $request->all();
        $result = $this->customerService->registerToMagento($data);

        if (!empty($result['message'])) {
            $errorResult = $this->dissectError($result);
            return response()->json($errorResult);
        }
        return response()->json($result);
    }

    /**
     * Dissect the error message and return accordingly
     * 
     * @param array $result
     * @return array $errorResult
     */
    private function dissectError($result)
    {
        $errorResult = [];
        $errorResult['message'] = $result['message'];
        if (preg_match("/Classes of characters: /", $result['message'])) {
            $errorResult['message'] = 'Password should have small letters, Capital letters, digits, and special characters';
        }

        return $errorResult;
    }
}
