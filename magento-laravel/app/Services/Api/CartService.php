<?php

namespace App\Services\Api;

use App\Models\CartItem;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;

class CartService extends ApiService
{
    public function post($endpoint, $data)
    {
        $this->throwExceptionIfUrlOrTokenEmpty();

        // Convert to array
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        $response = Http::withToken($this->getToken())
                        ->post($this->getApiBaseUrl() . $endpoint, $data);

        if (!$response->successful()) {
            echo 'ERROR with POST '.$endpoint." :: \n";
            echo $response->body() . "\n";
            throw new \Exception(
                'ERROR with POST '.$endpoint.' :: '.$response->body().' , data :: '.json_encode($data)
            );
        }
        $json = $response->json();
        return $json;
    }

    public function delete($endpoint)
    {
        $this->throwExceptionIfUrlOrTokenEmpty();

        $response = Http::withToken($this->getToken())
            ->delete($this->getApiBaseUrl() . $endpoint);
        if (!$response->successful()) {
            throw new \Exception(
                'CANNOT perform DELETE '.$endpoint.' :: '.$response->body()
            );
        }
        return $response->body();
    }

    public function processPendingCalls($pendingCalls)
    {
        foreach ($pendingCalls as $pendingCall) {
            echo "==============================\n";
            echo $pendingCall->magento_api_url . "\n";
            echo $pendingCall->method . "\n";
            if ($pendingCall->method == Request::METHOD_POST) {
                try {
                    $json = $this->post($pendingCall->magento_api_url, $pendingCall->payload);
                    echo "Got response: ".json_encode($json)."\n";
                    // update $pendingCall if all good..
                    if (preg_match('/guest-carts.*items/', $pendingCall->magento_api_url)) {
                        $result = $this->processAddToCart($json, $pendingCall);
                    }
                } catch (\Exception $e) {
                    $pendingCall->status = 'error';
                    $pendingCall->save();
                    throw new \Exception($e->getMessage());
                }
            }
        }
        echo "Done processing pending Cart API calls...\n";
    }

    public function processAddToCart($json, $pendingCall = null)
    {
        /** @var \App\Models\CartItem $cartItem */
        $cartItem = CartItem::findOrFail($pendingCall->cart_item_id);
        $cartItem->updateCartItem($json);
        $cartItem->save();

        if ($cartItem->id) {
            $pendingCall->response = $json;
            $pendingCall->status = 'success';
            $pendingCall->save();
        }
        return true;
    }
}