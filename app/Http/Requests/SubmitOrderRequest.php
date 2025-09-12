<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitOrderRequest extends FormRequest
{
    public function rules(#[RouteParameter('order')] Order $order): array
    {
        return [
            'ticket' => 'required|array',
            'ticket.*.name' => 'required|string|max:255',
            'ticket.*.email' => 'required|string|max:255|email',
            'ticket.*.id' => [
                'required',
                Rule::exists('order_tickets', 'id')->where('order_id', $order->id),
            ],
            'payer_name' => 'required|string|max:255',
            'payer_email' => 'required|string|max:255|email',
        ];
    }
}
