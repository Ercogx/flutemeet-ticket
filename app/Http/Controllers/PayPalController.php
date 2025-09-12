<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    public function success(Request $request): View
    {
        return view('success', [
            'order' => Order::wherePayPalId($request->input('orderId'))->firstOrFail()
        ]);
    }

    public function cancel(): View
    {
        return view('cancel');
    }
}
