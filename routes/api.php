<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FawryPaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payments/fawry/webhook', [FawryPaymentController::class, 'webhook'])
    ->name('payments.fawry.webhook');
