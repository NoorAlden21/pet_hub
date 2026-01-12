<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    public function __construct(protected UserNotificationsService $notificationService)
    {
    }

    public function indexFor(User $user, int $perPage = 15)
    {
        $query = Appointment::with(['petType', 'petBreed', 'category', 'user'])
            ->orderByDesc('created_at');

        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate($perPage);
    }

    public function show(Appointment $appointment): Appointment
    {
        return $appointment->load(['petType', 'petBreed', 'category', 'user']);
    }

    public function create(User $user, array $data): Appointment
    {
        $category = AppointmentCategory::find($data['appointment_category_id'] ?? null);

        return DB::transaction(function () use ($user, $data, $category) {
            $appointment = Appointment::create([
                'user_id' => $user->id,
                'pet_type_id' => $data['pet_type_id'],
                'pet_breed_id' => $data['pet_breed_id'] ?? null,
                'appointment_category_id' => $data['appointment_category_id'],
                'appointment_date' => $data['appointment_date'],
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
            ]);

            $this->notificationService->notifyAdmins(
                'appointment_created',
                __('notifications.appointment_created_title'),
                __('notifications.appointment_created_body', [
                    'date' => $appointment->appointment_date->toDateString(),
                    'category' => $category->name,
                ]),
                ['appointment_id' => $appointment->id, 'status' => $appointment->status]
            );

            return $appointment->load(['petType', 'petBreed', 'category', 'user']);
        });
    }

    public function cancel(Appointment $appointment): Appointment
    {
        return DB::transaction(function () use ($appointment) {
            if (!in_array($appointment->status, ['pending', 'approved'], true)) {
                throw ValidationException::withMessages([
                    'status' => [__('messages.appointment.cannot_cancel')],
                ]);
            }

            $appointment->update(['status' => 'cancelled']);

            $this->notificationService->notifyAdmins(
                'appointment_cancelled',
                __('notifications.appointment_cancelled_title'),
                __('notifications.appointment_cancelled_body', [
                    'appointment_id' => $appointment->id,
                ]),
                ['appointment_id' => $appointment->id, 'status' => 'cancelled']
            );

            return $appointment->fresh()->load(['petType', 'petBreed', 'category', 'user']);
        });
    }

    public function updateStatus(Appointment $appointment, string $status, ?string $rejectionReason = null): Appointment
    {
        return DB::transaction(function () use ($appointment, $status, $rejectionReason) {

            if ($status === 'approved' && $appointment->status !== 'pending') {
                throw ValidationException::withMessages(['status' => [__('messages.appointment.cannot_change_status')]]);
            }

            if ($status === 'rejected') {
                if ($appointment->status !== 'pending') {
                    throw ValidationException::withMessages(['status' => [__('messages.appointment.cannot_change_status')]]);
                }
                // if (!$rejectionReason) {
                //     throw ValidationException::withMessages(['rejection_reason' => [__('messages.appointment.rejection_reason_required')]]);
                // }
            }

            if (in_array($status, ['completed', 'missed'], true) && $appointment->status !== 'approved') {
                throw ValidationException::withMessages(['status' => [__('messages.appointment.cannot_change_status')]]);
            }

            $appointment->update([
                'status' => $status,
                'rejection_reason' => $status === 'rejected' ? $rejectionReason : null,
            ]);

            $key = match ($status) {
                'approved' => 'appointment_approved',
                'rejected' => 'appointment_rejected',
                'completed' => 'appointment_completed',
                'missed' => 'appointment_missed',
                default => null,
            };

            if ($key) {
                $payload = ['appointment_id' => $appointment->id, 'status' => $status];

                $bodyParams = [
                    'date' => $appointment->appointment_date->toDateString(),
                    'category' => $appointment->category?->name ?? '',
                    'reason' => $appointment->rejection_reason ?? '',
                ];

                $this->notificationService->notifyUser(
                    $appointment->user,
                    $key,
                    __("notifications.{$key}_title"),
                    __("notifications.{$key}_body", $bodyParams),
                    $payload
                );
            }

            return $appointment->fresh()->load(['petType', 'petBreed', 'category', 'user']);
        });
    }

    public function markMissedAutomatically(): int
    {
        $count = 0;

        Appointment::with(['user', 'category'])
            ->where('status', 'approved')
            ->whereDate('appointment_date', '<', now()->toDateString())
            ->orderBy('id')
            ->chunkById(200, function ($appointments) use (&$count) {
                foreach ($appointments as $appointment) {
                    $appointment->update(['status' => 'missed']);

                    $this->notificationService->notifyUser(
                        $appointment->user,
                        'appointment_missed',
                        __('notifications.appointment_missed_title'),
                        __('notifications.appointment_missed_body', [
                            'date' => $appointment->appointment_date->toDateString(),
                            'category' => $appointment->category?->name ?? '',
                        ]),
                        ['appointment_id' => $appointment->id, 'status' => 'missed']
                    );

                    $count++;
                }
            });

        return $count;
    }
}
