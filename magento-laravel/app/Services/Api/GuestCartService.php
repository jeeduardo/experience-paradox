<?php

namespace App\Services\Api;


use Illuminate\Support\Facades\Http;

class GuestCartService extends ApiService
{
    public function requestGuestCart()
    {
        $this->throwExceptionIfUrlOrTokenEmpty();
        $response = Http::withToken($this->getToken())->post($this->getApiBaseUrl() . '/guest-carts');

        if (!$response->successful()) {
            throw new \Exception('ERROR : Cannot create guest cart :: '.$response->body());
        }
        $responseBody = trim($response->body(), '"');
        return $responseBody;
    }

    public function getQuoteInfo($cartId)
    {
        $this->throwExceptionIfUrlOrTokenEmpty();
        $response = Http::withToken($this->getToken())->get($this->getApiBaseUrl() . '/guest-carts/'.$cartId);
        if (!$response->successful()) {
            throw new \Exception(
                'ERROR : Cannot get quote info :: '.$response->body()
            );
        }
        $json = $response->json();
        return $json;
    }

    public function addToCart($cartItem)
    {
        // @todo: implementation
    }
}