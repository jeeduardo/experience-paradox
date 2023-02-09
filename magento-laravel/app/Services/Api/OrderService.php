<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class OrderService extends ApiService
{
    public function placeOrder($cartId, $payload)
    {
        $this->throwExceptionIfUrlOrTokenEmpty();

        $endpoint = '/guest-carts/'.$cartId.'/payment-information-order';
        $placeOrderUrl = $this->getApiBaseUrl() . $endpoint;
        $response = Http::withToken($this->getToken())->post($placeOrderUrl, $payload);

        if (!$response->successful()) {
            // @todo: log also to cart_to_order_api and throw an exception
            return [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ];
        }
        return [
            'endpoint' => $endpoint,
            'status' => $response->status(),
            'json' => $response->json(),
        ];
    }
}