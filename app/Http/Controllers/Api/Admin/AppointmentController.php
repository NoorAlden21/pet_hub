<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Appointment\UpdateAppointmentStatusRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $service)
    {
    }

    public function index(Request $request)
    {
        $appointments = $this->service->indexFor($request->user());

        return AppointmentResource::collection($appointments);
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load(['petType', 'petBreed', 'category', 'user']));
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment)
    {
        $data = $request->validated();

        $appointment = $this->service->updateStatus($appointment, $data['status'], $data['rejection_reason'] ?? null);

        return response()->json([
            'message' => __('messages.appointment.status_updated'),
            'data' => new AppointmentResource($appointment),
        ]);
    }
}
