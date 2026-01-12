<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentCategoryResource;
use App\Models\AppointmentCategory;

class AppointmentCategoryController extends Controller
{
    public function index()
    {
        $categories = AppointmentCategory::active()
            ->orderBy('id')
            ->get();

        return AppointmentCategoryResource::collection($categories);
    }
}
