<?php

use App\Http\Controllers\Api\SendEmailWhitelistController;
use Illuminate\Support\Facades\Route;

Route::post('/send-email-whitelist', SendEmailWhitelistController::class)->middleware('auth:sanctum');
