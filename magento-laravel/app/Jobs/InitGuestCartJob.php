<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Services\Api\GuestCartService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class InitGuestCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var GuestCartService
     */
    private $guestCartService;

    /**
     * @var Cart
     */
    private $cart;

    private $logger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        GuestCartService $guestCartService,
        Cart $cart
    ) {
        $this->guestCartService = $guestCartService;
        $this->cart = $cart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->logger = Log::channel('cart_sync');
        // Initialize guest cart, get token
        // Then, update "cart" record with the token
        $cartToken = $this->guestCartService->requestGuestCart();
        if (!empty($cartToken)) {
            $this->cart->cart_token = $cartToken;

            // Get quote info (magento_quote_id)
            $quoteInfo = $this->guestCartService->getQuoteInfo($cartToken);
            if ($quoteInfo['id']) {
                $this->cart->magento_quote_id = $quoteInfo['id'];
            }
            $this->cart->save();
            $this->logger->info("Cart ".$this->cart->id."updated ", [$this->cart->toArray()]);
        }
    }

    public function __sleep()
    {
        return ['cart', 'guestCartService'];
    }
}
