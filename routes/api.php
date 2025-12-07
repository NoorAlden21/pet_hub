<?php

use App\Http\Controllers\Api\Admin\PetBreedController;
use App\Http\Controllers\Api\Admin\PetController;
use App\Http\Controllers\Api\Admin\PetTypeController;
use App\Http\Controllers\Api\Admin\ProductCategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
    Route::apiResource('pet-types', PetTypeController::class);
    Route::apiResource('pet-breeds', PetBreedController::class);
    Route::apiResource('pets', PetController::class);
    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('products', ProductController::class);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/cart')->group(function () {
        Route::get('', [CartController::class, 'show']);
        Route::post('/items', [CartController::class, 'storeItem']);
        Route::put('/items/{cartItemId}', [CartController::class, 'updateItem']);
        Route::delete('/cart/items/{cartItemId}', [CartController::class, 'destroyItem']);
        Route::delete('', [CartController::class, 'clear']);
    });
});
