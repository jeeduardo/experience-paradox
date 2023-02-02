<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class RemoveFromCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    private $cartId;

    /**
     * @var \App\Models\CartItem
     */
    private $cartItem;

    /**
     * @var \App\Services\Api\CartService
     */
    private $cartService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $cartId,
        \App\Models\CartItem $cartItem
    ) {
        $this->cartId = $cartId;
        $this->cartItem = $cartItem;
        $this->cartService = new \App\Services\Api\CartService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Cannot place at __construct due to Closure serialization problems
        $this->logger = Log::channel('cart_sync');

        /** @var \App\Models\Cart $cart */
        $cart = $this->cartItem->cart;
        $quoteItemId = $this->cartItem->magento_quote_item_id;

        if (empty($quoteItemId)) {
            // Remove the cart item anyways if magento_quote_item_id is empty!
            $this->logger->info("Cart item ID ".$this->cartItem->id." not synced with magento. Deleting...");
            $this->cartItem->delete();
            return;
        }

        $endpoint = '/guest-carts/'.$this->cartId.'/items/'.$quoteItemId;
        $this->logger->info("DELETE $endpoint");
        $result = $this->cartService->delete($endpoint);
        $this->logger->info("DELETE $endpoint result", [$result]);
        if ($result == true) {
            $this->cartItem->delete();
            $this->logger->info("Cart item deleted.");
        }
        $cart->updateCart();
        $cart->save();
    }
}
