<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Appointment\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class MyAppointmentController extends Controller
{
    public function __construct(protected AppointmentService $service)
    {
    }

    public function index(Request $request)
    {
        $appointments = Appointment::with(['petType', 'petBreed', 'category'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate((int) $request->get('per_page', 15));

        return AppointmentResource::collection($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->service->create($request->user(), $request->validated());

        return response()->json([
            'message' => __('messages.appointment.created'),
            'data' => new AppointmentResource($appointment),
        ], 201);
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load(['petType', 'petBreed', 'category']));
    }

    public function cancel(Appointment $appointment)
    {
        $appointment = $this->service->cancel($appointment);

        return response()->json([
            'message' => __('messages.appointment.cancelled'),
            'data' => new AppointmentResource($appointment),
        ]);
    }
}
