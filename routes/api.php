<?php

use App\Http\Controllers\Api\Admin\PetTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('/admin')->group(function () {
    Route::apiResource('pet-types', PetTypeController::class);
});
