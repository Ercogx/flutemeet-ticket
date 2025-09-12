<?php

namespace App\Http\Requests;

use App\Models\Event;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(#[RouteParameter('event')] Event $event): array
    {
        return [
            'adult_input' => 'integer|min:0|max:'.$event->remaining_adult_ticket,
            'child_input' => 'integer|min:0|max:'.$event->remaining_child_ticket,
        ];
    }
}
