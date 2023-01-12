<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveFromCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cartId;
    /**
     * @var \App\Models\CartItem
     */
    private $cartItem;

    private $cartService;

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
        /** @var \App\Models\Cart $cart */
        $cart = $this->cartItem->cart;
        $quoteItemId = $this->cartItem->magento_quote_item_id;
        print_r([
            $quoteItemId,
        ]);
        if (empty($quoteItemId)) {
            // Remove the cart item anyways if magento_quote_item_id is empty!
            echo "Cart item ID ".$this->cartItem->id." not synced with magento. Deleting...\n";
            $this->cartItem->delete();
            return;
        }

        $endpoint = '/guest-carts/'.$this->cartId.'/items/'.$quoteItemId;
        echo "DELETE $endpoint \n";
        $result = $this->cartService->delete($endpoint);
        echo "DELETE $endpoint result: \n";
        print_r($result);
        echo "\n";
        if ($result == true) {
            $this->cartItem->delete();
            echo "Cart item deleted.\n";
        }
        $cart->updateCart();
        $cart->save();
    }
}
