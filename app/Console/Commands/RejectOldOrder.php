<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Console\Command;

class RejectOldOrder extends Command
{
    protected $signature = 'app:reject-old-order';

    public function handle()
    {
        Order::where('status', OrderStatus::PENDING)
            ->where('created_at', '<', now()->subMinutes(5))
            ->update([
                'status' => OrderStatus::REJECTED
            ]);
    }
}
