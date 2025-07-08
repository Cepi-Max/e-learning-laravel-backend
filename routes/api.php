<?php

use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\DataPadiController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Yang lebih profesional
// Route default user login check
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Webhook dari midtrans
Route::prefix('v1')->name('api.midtrans.')->group(function() {
    Route::post('/midtrans/callback', [PaymentController::class, 'callback']);
    Route::post('/midtrans/webhook', [PaymentController::class, 'handleWebhook']);
});

// Auth Routes
Route::prefix('v1')->name('api.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    });
});


Route::middleware('auth:sanctum')->prefix('v1')->name('api.')->group(function() {
    
    Route::apiResource('datapadi', DataPadiController::class);
    Route::post('datapadi/update/{id}', [DataPadiController::class, 'update']);
    
    Route::apiResource('product', ProductController::class);

    Route::apiResource('cart', CartApiController::class);
    Route::post('cart/update/{id}', [CartApiController::class, 'update']);
    Route::post('/cart/items', [CartApiController::class, 'destroyMany']);
    
    Route::apiResource('order', OrderController::class);
    
    // Route::post('/pay', [PaymentController::class, 'pay']);
    Route::post('/checkout', [PaymentController::class, 'checkout']);

    Route::get('/statistics', [StatisticController::class, 'getDashboardStats']); // Rute baru
}); 

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::prefix('auth')->group(function(){
//     Route::post('/login', [AuthController::class, 'login'])->name('login');
// });

// Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

// Route::resource('/datapadi', DataPadiController::class)->middleware('auth:sanctum');

// Route::post('datapadi/update/{id}', [DataPadiController::class, 'update'])->middleware('auth:sanctum');
// Route::put('datapadi/update/{id}', [DataPadiController::class, 'update'])->middleware('auth:sanctum');
// // Route::delete('datapadi/delete/{id}', [DataPadiController::class, 'destroy'])->middleware('auth:sanctum'); sudah bisa tanpa destroy juga

// Route::resource('/product', ProductController::class)->middleware('auth:sanctum');

// Route::post('product/update/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
// Route::put('product/update/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');

// Route::resource('/order', OrderController::class)->middleware('auth:sanctum');

// Route::post('order/update/{id}', [OrderController::class, 'update'])->middleware('auth:sanctum');
// Route::put('order/update/{id}', [OrderController::class, 'update'])->middleware('auth:sanctum');
