<?php

use App\Http\Controllers\Api\Admin\PetBreedController;
use App\Http\Controllers\Api\Admin\PetController;
use App\Http\Controllers\Api\Admin\PetTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
    Route::apiResource('pet-types', PetTypeController::class);
    Route::apiResource('pet-breeds', PetBreedController::class);
    Route::apiResource('pets', PetController::class);
});
