<?php

use Illuminate\Support\Facades\Route;

Route::post('/webhooks/chatbot/order-status', [\App\Http\Controllers\Api\OrderStatusWebhookController::class, 'search'])
    ->name('webhooks.chatbot.order-status');

