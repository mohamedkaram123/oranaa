<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix("admin")->group(function () {
    Route::post("/login", [AuthController::class, 'login']);

    Route::middleware(['jwt.auth:admin'])->group(function () {
        Route::get("/logout", [AuthController::class, 'logout']);

        Route::post('/products', [ProductController::class, 'index']);
        Route::post('/product', [ProductController::class, 'store']);
        Route::get('/product/{id}', [ProductController::class, 'show']);
        Route::put('/product/{id}', [ProductController::class, 'update']);
        Route::get('/products/total_products', [ProductController::class, 'total_products']);
        Route::get('/products/total_urls_count_for_each_website', [ProductController::class, 'total_urls_count_for_each_website']);
        Route::get('/products/avg_price_products', [ProductController::class, 'avg_price_products']);
        Route::get('/products/webiste_highest_total_prices', [ProductController::class, 'webiste_highest_total_prices']);
        Route::get('/products/total_price_during_month', [ProductController::class, 'total_price_during_month']);
    });

});
