<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddToCartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var string
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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $payload,
        $cartId,
        \App\Models\CartItem $cartItem
    ) {
        $this->payload = $payload;
        $this->cartId = $cartId;
        $this->cartItem = $cartItem;
        $this->cartService = new \App\Services\Api\CartService();
    }

    /**
     * Execute the add-to-cart job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->cartItem->has_failed) {
            try {
                $endpoint = "/guest-carts/".$this->cartId."/items";
                echo "POST $endpoint \n"; print_r($this->payload);
                $json = $this->cartService->post($endpoint, $this->payload);
                echo "Response... \n"; print_r($json);
                if (!empty($json) && $json['item_id']) {
                    $this->cartItem->updateCartItem($json);
                    $this->cartItem->save();
                    return;
                }
            } catch (\Exception $e) {
                echo ("Add to cart sync failed for ".$this->cartItem->id."\n");
                echo ($e->getMessage());
                $this->cartItem->retries = $this->cartItem->retries + 1;
                if ($this->cartItem->retries == 3) {
                    $this->cartItem->has_failed = 1;
                    $this->cartItem->determineFailureCode($e);
                }
                $this->cartItem->save();
                throw $e;
            }
        }
        echo ("Cart item sync has failed for ".$this->cartItem->sku);
        echo (" (cart item ID: ".$this->cartItem->id);
    }
}
