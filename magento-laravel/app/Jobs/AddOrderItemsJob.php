<?php

namespace App\Jobs;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddOrderItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    
    /**
     * @var \App\Models\Order
     */
    private $order;

    /**
     * @var array
     */
    private $responseJson;

    /**
     * @var \App\Models\Cart
     */
    private $cart;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        \App\Models\Order $order,
        $responseJson,
        \App\Models\Cart $cart
    ) {
        $this->order = $order;
        $this->responseJson = $responseJson;
        $this->cart = $cart;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->logger = \Illuminate\Support\Facades\Log::channel('order_sync');
        $this->logger->info('AddOrderItemsJob :: response json');
        $this->logger->info(print_r($this->responseJson, 1));
        if (empty($this->responseJson['items'])) {
            $this->logger->info('AddOrderItemsJob :: order items empty! Exiting');
            return 1;
        }
        $orderItems = $this->responseJson['items'];
        $cartItems = $this->cart->cartItems;
        $orderItemsData = OrderItem::createOrderItems($this->order, $orderItems, $cartItems);
    }
}
