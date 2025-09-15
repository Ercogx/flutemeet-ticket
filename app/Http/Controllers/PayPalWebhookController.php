<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? null;

        Log::channel('order')->info('PayPal webhook received', $payload);

        if ($eventType === 'PAYMENT.CAPTURE.REFUNDED') {
            $captureLink = collect($payload['resource']['links'] ?? [])
                ->firstWhere('rel', 'up');

            if ($captureLink) {
                $captureId = basename($captureLink['href']);

                Order::wherePayPalCapture($captureId)->update(['status' => OrderStatus::REFUNDED]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
