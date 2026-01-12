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
        $query = Appointment::with(['petType', 'petBreed', 'category', 'user'])
            ->orderByDesc('created_at');

        // if ($status = $request->get('status')) {
        //     $query->where('status', $status);
        // }

        // if ($categoryId = $request->get('appointment_category_id')) {
        //     $query->where('appointment_category_id', $categoryId);
        // }

        // if ($from = $request->get('from')) {
        //     $query->whereDate('appointment_date', '>=', $from);
        // }

        // if ($to = $request->get('to')) {
        //     $query->whereDate('appointment_date', '<=', $to);
        // }

        return AppointmentResource::collection($query->paginate((int) $request->get('per_page', 15)));
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load(['petType', 'petBreed', 'category', 'user']));
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment)
    {
        $data = $request->validated();

        $appointment = $this->service->updateStatus($appointment, $data['status'] ?? null, $data['rejection_reason'] ?? null);

        return response()->json([
            'message' => __('messages.appointment.status_updated'),
            'data' => new AppointmentResource($appointment),
        ]);
    }
}
