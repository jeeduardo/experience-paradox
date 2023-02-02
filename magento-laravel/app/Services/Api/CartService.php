<?php

namespace App\Services\Api;

use App\Models\CartItem;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request;

class CartService extends ApiService
{
    /**
     * Perform a POST request to the specified $endpoint
     * With payload $data
     *
     * @param $endpoint
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function post($endpoint, $data = [])
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

    /**
     * Perform a DELETE request to specified $endpoint
     *
     * @param $endpoint
     * @return string
     * @throws \Exception
     */
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
                    echo "processPendingCalls :: Response: ".json_encode($json)."\n";
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
        echo "processPendingCalls :: Done processing pending Cart API calls...\n";
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

    /**
     * Perform a POST request to the /guest-carts/<token>/estimate-shipping-methods Magento endpoint
     *
     * @param \App\Models\Cart $data
     */
    public function estimateShippingMethods($cartToken, $shippingAddressData)
    {
        $endpoint = '/guest-carts/' . $cartToken . '/estimate-shipping-methods';
        $url = $this->getApiBaseUrl() . $endpoint;
        $returnData = $this->post($endpoint, $shippingAddressData);

        if ($returnData) {
            echo "estimateShippingMethods :: returnData? \n";
            print_r([
                $returnData,
            ]);
            echo "\n";
            return $returnData;
        }

        print_r([$endpoint, $returnData]);
        echo "estimateShippingMethods :: return false\n";
        return false;
    }

    /**
     * Perform a POST request to /guest-carts/<token>/shipping-information endpoint
     * With shipping, billing address, together with the selected shipping method
     *
     * Expected response should have payment_methods and an updated Cart/Quote totals data
     *
     * @param array $params
     * @return bool|mixed
     */
    public function saveShippingMethod(array $params)
    {
        // Construct the payload
        $cart = $params['cart'];
        $shippingAddress = $params['shipping_address'];
        $billingAddress = $params['billing_address'];

        if ($shippingAddress->same_as_billing) {
            $billingAddress = $shippingAddress;
        }
        $shippingCarrierCode = $params['shipping_carrier_code'];
        $shippingMethodCode = $shippingAddress->shipping_method;
        $payload = [
            'addressInformation' => [
                'shipping_address' => $this->constructAddressPayload($shippingAddress),
                'billing_address' => $this->constructAddressPayload($billingAddress),
                'shipping_carrier_code' => $shippingCarrierCode,
                'shipping_method_code' => $shippingMethodCode,
            ]
        ];
        $endpoint = '/guest-carts/'.$cart->cart_token.'/shipping-information';
        // Call API endpoint, should return payment_methods and totals...

        $returnData = $this->post($endpoint, $payload);
        if (!empty($returnData)) {
            return $returnData;
        }
        return false;
    }

    /**
     * Construct address data for necessary Magento checkout API requests
     * @param $address
     * @return array
     */
    private function constructAddressPayload($address)
    {
        return [
            'region' => $address->region,
            'country_id' => $address->country_id,
            // In Magento, the street parameter passed should be an array of street lines 1 and 2
            'street' => [$address->street],
            'postcode' => $address->postcode,
            'city' => $address->city,
            'firstname' => $address->firstname,
            'lastname' => $address->lastname,
            'customer_id' => null,
            'email' => $address->email,
            'telephone' => $address->telephone,
        ];
    }
}
