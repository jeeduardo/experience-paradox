<?php

namespace App\Console\Commands;

use App\Models\CartApiCall;
use Illuminate\Console\Command;

class SyncCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

//    private $cartService;

    public function __construct(
        private \App\Services\Api\CartService $cartService
    ){
        parent::__construct();
//        $this->cartService = $cartService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->cartService->processPendingCalls($this->getPendingCartApiCalls());
        return Command::SUCCESS;
    }

    protected function getPendingCartApiCalls()
    {
        $pendingCalls = CartApiCall::where('status', 'pending')->get()->all();
        return $pendingCalls;
    }
}
