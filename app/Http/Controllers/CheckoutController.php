<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\TicketType;
use App\Events\OrderPaid;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\SubmitOrderRequest;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Facades\PayPal;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $order = Order::whereSession($request->session()->getId())->latest()->first();

        if (is_null($order) || $order->status !== OrderStatus::PENDING) {
            return to_route('home');
        }

        if ($order->created_at->lt(now()->subMinutes(5))) {
            $order->update(['status' => OrderStatus::REJECTED]);

            return to_route('home');
        }

        return view('checkout', compact('order'));
    }

    public function submitOrder(Order $order, SubmitOrderRequest $request): JsonResponse
    {
        $paypalOrder = DB::transaction(function () use ($order, $request) {
            $provider = PayPal::setProvider();
            $provider->getAccessToken();

            $order->update([
                'payer_name' => $request->input('payer_name'),
                'payer_email' => $request->input('payer_email'),
            ]);

            $request->collect('ticket')
                ->each(function (array $ticket) {
                    OrderTicket::where('id', $ticket['id'])->update([
                        'name' => $ticket['name'],
                        'email' => $ticket['email'],
                    ]);
                });

            $paypalOrder = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => config('paypal.currency'),
                            'value' => $order->totalPrice()
                        ]
                    ]
                ],
                'items' => $order->toPayPalItems(),
                'application_context' => [
                    'return_url' => route('paypal.success'),
                    'cancel_url' => route('paypal.cancel'),
                ]
            ]);

            $order->update(['pay_pal_id' => $paypalOrder['id']]);

            return $paypalOrder;
        });

        return response()->json($paypalOrder);
    }

    public function captureOrder(string $orderId): JsonResponse
    {
        $provider = PayPal::setProvider();
        $provider->getAccessToken();

        $result = $provider->capturePaymentOrder($orderId);

        if ($result['status'] === 'COMPLETED') {
            $order = Order::wherePayPalId($orderId)->first();
            $order->update(['status' => OrderStatus::PAID]);

            OrderPaid::dispatch($order);
        } else {
            return response()->json(['redirect' => to_route('paypal.cancel')]);
        }

        return response()->json([
            'redirect' => route('paypal.success', ['orderId' => $orderId]),
        ]);
    }

    public function createOrder(Event $event, CreateOrderRequest $request): RedirectResponse
    {
        Order::whereSession($request->session()->getId())
            ->where('status', OrderStatus::PENDING)
            ->update([
                'status' => OrderStatus::REJECTED,
            ]);

        $order = $event->orders()->create([
            'session' => $request->session()->getId(),
            'status' => OrderStatus::PENDING,
        ]);

        $adult = array_fill(0, $request->integer('adult_input'), ['ticket_type' => TicketType::ADULT]);
        $child = array_fill(0, $request->integer('child_input'), ['ticket_type' => TicketType::CHILD]);

        $order->orderTickets()->createMany($adult);
        $order->orderTickets()->createMany($child);

        return to_route('checkout.index');
    }
}
