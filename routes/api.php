<?php

use App\Http\Controllers\Api\Admin\PetBreedController;
use App\Http\Controllers\Api\Admin\PetController;
use App\Http\Controllers\Api\Admin\PetTypeController;
use App\Http\Controllers\Api\Admin\ProductCategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
    Route::apiResource('pet-types', PetTypeController::class);
    Route::apiResource('pet-breeds', PetBreedController::class);
    Route::apiResource('pets', PetController::class);
    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('products', ProductController::class);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/email/resend-verification', [AuthController::class, 'resendVerificationEmail']);

    Route::get('/pets', [PetController::class, 'index']);
    Route::get('/pets/{pet}', [PetController::class, 'show']);

    Route::prefix('/my')->group(function () {
        Route::get('/pets', [PetController::class, 'myPets']);
        Route::post('/pets', [PetController::class, 'store']);
        Route::patch('/pets/{pet}', [PetController::class, 'update'])
            ->middleware('owner:pet,owner_id');
        Route::delete('/pets/{pet}', [PetController::class, 'destroy'])
            ->middleware('owner:pet,owner_id');
    });


    //shop:

    //only active products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::prefix('/cart')->group(function () {
        Route::get('', [CartController::class, 'show']);
        Route::post('/items', [CartController::class, 'storeItem']);
        Route::put('/items/{cartItemId}', [CartController::class, 'updateItem']);
        Route::delete('/items/{cartItemId}', [CartController::class, 'destroyItem']);
        Route::delete('', [CartController::class, 'clear']);
    });
});
