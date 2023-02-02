<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveShippingMethodJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cart;

    private $shippingAddress;

    private $billingAddress;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        \App\Models\Cart $cart,
        \App\Models\CheckoutAddress $shippingAddress,
        \App\Models\CheckoutAddress $billingAddress
    ) {
        $this->cart = $cart;
        $this->shippingAddress = $shippingAddress;
        $this->billingAddress = $billingAddress;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Construct the payload
        /*
         * API URL: <SITE_URL>/rest/<store_code>/V1/guest-carts/:cartId/shipping-information
(Example: https://magento.test/rest/default/V1/guest-carts/w04QKi5YeYh6CC1tTFbpY1sPNKhbrMIZ/shipping-information)

Payload:

{
    "addressInformation": {
        "shipping_address": {
            "region": "Dublin",
            "country_id": "IE",
            "street": [
                "123 Oak Ave"
            ],
            "postcode": "W34 X5Y5",
            "city": "Dublin",
            "firstname": "Jane Guest",
            "lastname": "Doe Guest",
            "customer_id": null,
            "email": "rakesh@wearejh.com",
            "telephone": "4445531111"
        },
        "billing_address": {
            "region": "Dublin",
            "country_id": "IE",
            "street": [
                "123 Oak Ave"
            ],
            "postcode": "W34 X5Y5",
            "city": "Dublin",
            "firstname": "Jane Guest",
            "lastname": "Doe Guest",
            "customer_id": null,
            "email": "rakesh@wearejh.com",
            "telephone": "4445531111"
        },
        "shipping_carrier_code": "matrixrate",
        "shipping_method_code": "matrixrate_1"
    }
}
         */
        // Save payment methods available
        // Update totals
    }
}
