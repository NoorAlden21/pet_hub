<?php

use App\Http\Controllers\Api\Admin\PetBreedController;
use App\Http\Controllers\Api\Admin\PetController;
use App\Http\Controllers\Api\Admin\PetTypeController;
use App\Http\Controllers\Api\Admin\ProductCategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\AdoptionApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PublicPetController;
use App\Http\Controllers\Api\PublicProductController;
use App\Http\Controllers\Api\BoardingReservationController;
use App\Http\Controllers\Api\Admin\BoardingReservationAdminController;
use App\Http\Controllers\Api\Admin\BoardingServiceController;
use App\Models\BoardingService;
use Illuminate\Support\Facades\Route;


// public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::get('/pets', [PublicPetController::class, 'index']);
Route::get('/pets/{pet}', [PublicPetController::class, 'show']);


// products
Route::get('/products', [PublicProductController::class, 'index']);
Route::get('/products/{product}', [PublicProductController::class, 'show']);

Route::get('/pet-types', [PetTypeController::class, 'index']);
Route::get('/pet-breeds', [PetBreedController::class, 'index']);
Route::get('/product-categories', [ProductCategoryController::class, 'index']);

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
    Route::apiResource('pet-types', PetTypeController::class);
    Route::apiResource('pet-breeds', PetBreedController::class);
    Route::apiResource('pets', PetController::class);
    Route::apiResource('product-categories', ProductCategoryController::class);
    Route::apiResource('products', ProductController::class);

    Route::apiResource('adoption-applications', AdoptionApplicationController::class);
    Route::get('adoption-applications/pet/{petId}', [AdoptionApplicationController::class, 'petApplications']);

    //orders
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{order}', [OrderController::class, 'show']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::delete('orders/{order}', [OrderController::class, 'destroy']);

    //boarding
    Route::apiResource('boarding-services', BoardingServiceController::class);

    Route::get('boarding-reservations', [BoardingReservationAdminController::class, 'index']);
    Route::get('boarding-reservations/{boardingReservation}', [BoardingReservationAdminController::class, 'show']);
    Route::patch('boarding-reservations/{boardingReservation}/status', [BoardingReservationAdminController::class, 'updateStatus']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/email/resend-verification', [AuthController::class, 'resendVerificationEmail']);

    Route::get('/boarding-services', [BoardingServiceController::class, 'index']);

    Route::post('/boarding/quote', [BoardingReservationController::class, 'quote']);

    Route::prefix('/my')->group(function () {
        //my pets
        Route::get('/pets', [PetController::class, 'myPets']);
        Route::post('/pets', [PetController::class, 'store']);
        Route::patch('/pets/{pet}', [PetController::class, 'update'])
            ->middleware('owner:pet,owner_id');
        Route::delete('/pets/{pet}', [PetController::class, 'destroy'])
            ->middleware('owner:pet,owner_id');

        //my orders
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->middleware('owner:order,user_id');

        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->middleware('owner:order,user_id');

        //my adoption applications
        Route::get('/adoption-applications', [AdoptionApplicationController::class, 'index']);
        Route::post('/adoption-applications', [AdoptionApplicationController::class, 'store']);

        Route::get('/adoption-applications/{adoptionApplication}', [AdoptionApplicationController::class, 'show'])
            ->middleware('owner:adoptionApplication,user_id');

        Route::delete('/adoption-applications/{adoptionApplication}', [AdoptionApplicationController::class, 'destroy'])
            ->middleware('owner:adoptionApplication,user_id');

        //my boarding reservations
        Route::get('/boarding-reservations', [BoardingReservationController::class, 'index']);
        Route::post('/boarding-reservations', [BoardingReservationController::class, 'store']);

        Route::get('/boarding-reservations/{boardingReservation}', [BoardingReservationController::class, 'show'])
            ->middleware('owner:boardingReservation,user_id');

        Route::post('/boarding-reservations/{boardingReservation}/cancel', [BoardingReservationController::class, 'cancel'])
            ->middleware('owner:boardingReservation,user_id');
    });


    //shop:
    Route::prefix('/cart')->group(function () {
        Route::get('', [CartController::class, 'show']);
        Route::post('/items', [CartController::class, 'storeItem']);
        Route::patch('/items/{cartItemId}', [CartController::class, 'updateItem']);
        Route::delete('/items/{cartItemId}', [CartController::class, 'destroyItem']);
        Route::delete('', [CartController::class, 'clear']);
    });
});
