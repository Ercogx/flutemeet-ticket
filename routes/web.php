<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PayPalController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::post('/createOrder/{event}', [CheckoutController::class, 'createOrder'])->name('order.create');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::post('/submitOrder/{order}', [CheckoutController::class, 'submitOrder'])->name('checkout.submitOrder');
Route::post('/captureOrder/{orderId?}', [CheckoutController::class, 'captureOrder'])->name('checkout.captureOrder');

Route::get('paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
