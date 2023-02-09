<?php

namespace App\Jobs;

use App\Models\CheckoutShippingRate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EstimateShippingMethodsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Cart
     */
    private $cart;

    /**
     * @var array
     */
    private $shippingAddressData;

    /**
     * @var string
     */
    private $cartToken;

    /**
     * @var int
     */
    private $checkoutAddressId;

    /**
     * @var \App\Services\Api\CartService
     */
    private $cartService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        \App\Models\Cart $cart,
        $cartToken,
        $checkoutAddressId,
        $shippingAddressData
    ) {
        $this->cart = $cart;
        $this->cartToken = $cartToken;
        $this->checkoutAddressId = $checkoutAddressId;
        $this->shippingAddressData = $shippingAddressData;
        $cartService = new \App\Services\Api\CartService();
        $this->cartService = $cartService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // This is outputted to the screen where the "php artisan queue:work..." is running
        echo "Fetching shipping methods for cart with ID :: ".$this->cart->id."\n";
        print_r([
            $this->checkoutAddressId,
            $this->cartToken,
            $this->shippingAddressData,
            gettype($this->shippingAddressData['address']['same_as_billing']),
        ]);
        $shippingMethods = $this->cartService
            ->estimateShippingMethods($this->cartToken, $this->shippingAddressData);

        // Add the shipping rates/methods to checkout_shipping_rates table...
        CheckoutShippingRate::addShippingRatesForAddress(
            $this->checkoutAddressId,
            $shippingMethods
        );
    }
}
