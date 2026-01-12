<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppointmentCategory\StoreAppointmentCategoryRequest;
use App\Http\Requests\Admin\AppointmentCategory\UpdateAppointmentCategoryRequest;
use App\Http\Resources\AppointmentCategoryResource;
use App\Models\AppointmentCategory;

class AppointmentCategoryController extends Controller
{
    public function index()
    {
        return AppointmentCategoryResource::collection(
            AppointmentCategory::orderByDesc('created_at')->get()
        );
    }

    public function store(StoreAppointmentCategoryRequest $request)
    {
        $category = AppointmentCategory::create($request->validated());

        return response()->json([
            'message' => __('messages.appointment_category.created'),
            'data' => new AppointmentCategoryResource($category),
        ], 201);
    }

    public function show(AppointmentCategory $appointmentCategory)
    {
        return new AppointmentCategoryResource($appointmentCategory);
    }

    public function update(UpdateAppointmentCategoryRequest $request, AppointmentCategory $appointmentCategory)
    {
        $appointmentCategory->update($request->validated());

        return response()->json([
            'message' => __('messages.appointment_category.updated'),
            'data' => new AppointmentCategoryResource($appointmentCategory),
        ]);
    }

    public function destroy(AppointmentCategory $appointmentCategory)
    {
        $appointmentCategory->delete();

        return response()->json([
            'message' => __('messages.appointment_category.deleted'),
        ]);
    }
}
