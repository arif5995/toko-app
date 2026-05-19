<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Resources\OrderSummaryResource;

use App\Models\User;
use App\Models\Order;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);

Route::post('/orders', [OrderController::class, 'store']);

Route::delete('/dashboard/cache', [DashboardController::class, 'flushCache']);

Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

Route::delete('/api/dashboard/cache', [DashboardController::class, 'flushCache']);

Route::get('/users/{user}/orders/{order}', function (User $user, Order $order) {
    return new OrderSummaryResource($order);
})->scopeBindings();
