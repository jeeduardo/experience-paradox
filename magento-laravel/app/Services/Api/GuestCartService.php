<?php

namespace App\Services\Api;


use Illuminate\Support\Facades\Http;

class GuestCartService extends ApiService
{
    /**
     * Create guest cart session
     *
     * @return string
     * @throws \Exception
     */
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

    /**
     * Get Magento quote/cart data based on given $cartId
     *
     * @param $cartId
     * @return mixed
     * @throws \Exception
     */
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
}