<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserCartController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Controllers\User\UserProductController;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {

    // Auth Routes
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    // Protected Routes
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [AdminProductController::class, 'index']);       // all products
            Route::get('/{id}', [AdminProductController::class, 'show']);    // product detail
            Route::post('/', [AdminProductController::class, 'create']);     // create product
            Route::put('/{id}', [AdminProductController::class, 'update']);  // update product
            Route::delete('/{id}', [AdminProductController::class, 'delete']); // delete product
        });
        //Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});


Route::group([
    'prefix' => 'user',
    'as' => 'user.'
], function () {
    // Auth Routes
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');
    //  Products
    Route::get('/products', [UserProductController::class, 'index']); // all products

    Route::prefix('cart')->group(function () {
        Route::get('/', [UserCartController::class, 'index']);
        Route::post('/add', [UserCartController::class, 'add']);
        Route::put('/update/{productId}', [UserCartController::class, 'update']);
        Route::delete('/remove/{productId}', [UserCartController::class, 'remove']);
    });
    // Protected Routes
    Route::middleware(['auth:sanctum', 'user'])->group(function () {
        // Logout
        Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
        // Checkout
        Route::prefix('checkout')->group(function () {
            Route::post('/', [UserCheckoutController::class, 'checkout']);
        });
    });
});
