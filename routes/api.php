<?php

use App\Http\Controllers\API\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], static function () {
    Route::get('/orders', [OrderController::class, 'getOrders'])->name('orders.list');
    Route::get('/orders/{order_number}', [OrderController::class, 'getOrder'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'createOrder'])->name('orders.create');
});

//If you need authentication use sanctum middleware
//Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], static function () {
//    Route::post('/orders', [OrderController::class, 'createOrder'])->name('orders.create');
//});