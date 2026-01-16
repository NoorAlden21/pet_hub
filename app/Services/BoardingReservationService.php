<?php

namespace App\Services;

use App\Models\BoardingReservation;
use App\Models\BoardingService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BoardingReservationService
{
    protected UserNotificationsService $notificationService;

    public function __construct(UserNotificationsService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function listForUser($user)
    {
        return BoardingReservation::where('user_id', $user->id)
            ->latest()
            ->with(['petType'])
            ->get();
    }

    public function adminList()
    {
        return BoardingReservation::with(['user', 'petType'])->latest()->get();
    }

    public function quote(array $data): array
    {
        $startAt = Carbon::parse($data['start_at']);
        $endAt   = Carbon::parse($data['end_at']);

        if ($endAt->lessThanOrEqualTo($startAt)) {
            throw ValidationException::withMessages([
                'end_at' => 'تاريخ/وقت النهاية يجب أن يكون بعد البداية.',
            ]);
        }

        $minutes = $startAt->diffInMinutes($endAt);
        $billableHours = max(1, (int) ceil($minutes / 60)); // تبسيط: تقريب لأعلى للساعة

        $hourlyRate = (float) config('boarding.hourly_rate');
        $baseTotal = round($billableHours * $hourlyRate, 2);

        $servicesTotal = $this->calculateServicesTotal($data['services'] ?? []);
        $total = round($baseTotal + $servicesTotal, 2);

        return [
            'billable_hours' => $billableHours,
            'hourly_rate' => number_format($hourlyRate, 2, '.', ''),
            'base_total' => number_format($baseTotal, 2, '.', ''),
            'services_total' => number_format($servicesTotal, 2, '.', ''),
            'total' => number_format($total, 2, '.', ''),
        ];
    }

    public function create($user, array $data): BoardingReservation
    {
        return DB::transaction(function () use ($user, $data) {

            $quote = $this->quote($data);

            //BoardingReservation::create($data);
            $reservation = BoardingReservation::create([
                'user_id' => $user->id,
                'pet_type_id' => (int) $data['pet_type_id'],
                'pet_breed_id' => $data['pet_breed_id'] ? (int) $data['pet_breed_id'] : null,
                'age_months' => $data['age_months'] ?? null,
                'start_at' => Carbon::parse($data['start_at']),
                'end_at' => Carbon::parse($data['end_at']),
                'billable_hours' => (int) $quote['billable_hours'],
                'status' => 'pending',
                'total' => (float) $quote['total'],
                'notes' => $data['notes'] ?? null,
            ]);

            $servicesInput = $data['services'] ?? [];
            if (!empty($servicesInput)) {
                $serviceIds = collect($servicesInput)->pluck('id')->unique()->values()->all();

                $services = BoardingService::whereIn('id', $serviceIds)
                    ->where('is_active', true)
                    ->get()
                    ->keyBy('id');

                if (count($serviceIds) !== $services->count()) {
                    throw ValidationException::withMessages([
                        'services' => __('messages.boarding_reservations.invalid_services'),
                    ]);
                }

                $attach = [];
                foreach ($servicesInput as $item) {
                    $id  = (int) $item['id'];
                    $qty = (int) ($item['quantity'] ?? 1);
                    $attach[$id] = ['quantity' => $qty];
                }

                $reservation->services()->attach($attach);
            }

            // ✅ كوّن بيانات الرسالة
            $reservation->loadMissing(['petType', 'petBreed']);
            $petLabel = $reservation->petBreed?->name ?? $reservation->petType?->name ?? 'Pet';

            // ✅ notify admins بوجود حجز جديد
            $this->notificationService->notifyAdmins(
                'boarding_reservation_created',
                __('notifications.boarding_reservation_created_title'),
                __('notifications.boarding_reservation_created_body', [
                    'pet_name' => $petLabel,
                    'start_date' => $reservation->start_at?->format('Y-m-d H:i'),
                    'end_date' => $reservation->end_at?->format('Y-m-d H:i'),
                ]),
                ['reservation_id' => $reservation->id, 'status' => $reservation->status]
            );

            return $reservation->load('services');
        });
    }

    public function getDetails(BoardingReservation $reservation): BoardingReservation
    {
        return $reservation->load(['user', 'petType', 'petBreed', 'services']);
    }

    public function cancel(BoardingReservation $reservation): BoardingReservation
    {
        return DB::transaction(function () use ($reservation) {

            if (!in_array($reservation->status, ['pending'])) {
                throw ValidationException::withMessages([
                    'status' => __('messages.boarding_reservations.cannot_cancel'),
                ]);
            }

            $reservation->update(['status' => 'cancelled']);

            $this->notificationService->notifyAdmins(
                'boarding_reservation_cancelled',
                __('notifications.boarding_reservation_cancelled_title'),
                __('notifications.boarding_reservation_cancelled_body'),
                ['reservation_id' => $reservation->id, 'status' => 'cancelled']
            );

            return $reservation->fresh()->load('services');
        });
    }

    public function updateStatus(BoardingReservation $reservation, array $data): BoardingReservation
    {
        return DB::transaction(function () use ($reservation, $data) {

            if (in_array($reservation->status, ['cancelled', 'completed'])) {
                throw ValidationException::withMessages([
                    'status' => __('messages.boarding_reservations.cannot_change_status'),
                ]);
            }

            $newStatus = (string) $data['status'];

            $reservation->update(['status' => $newStatus]);

            $key = match ($newStatus) {
                'confirmed' => 'boarding_reservation_confirmed',
                'rejected'  => 'boarding_reservation_rejected',
                'completed' => 'boarding_reservation_completed',
                'cancelled' => 'boarding_reservation_cancelled',
                default     => null,
            };

            if ($key) {
                $reservation->loadMissing(['petType', 'petBreed', 'user']);
                $petLabel = $reservation->petBreed?->name ?? $reservation->petType?->name ?? 'Pet';

                $this->notificationService->notifyUser(
                    $reservation->user,
                    $key,
                    __("notifications.{$key}_title"),
                    __("notifications.{$key}_body", [
                        'pet_name' => $petLabel,
                        'start_date' => $reservation->start_at?->format('Y-m-d H:i'),
                        'end_date' => $reservation->end_at?->format('Y-m-d H:i'),
                    ]),
                    ['reservation_id' => $reservation->id, 'status' => $newStatus]
                );
            }

            return $reservation->fresh()->load('services');
        });
    }


    private function calculateServicesTotal(array $servicesInput): float
    {
        if (empty($servicesInput)) return 0.0;

        $serviceIds = collect($servicesInput)->pluck('id')->unique()->values()->all();

        $services = BoardingService::whereIn('id', $serviceIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        if (count($serviceIds) !== $services->count()) {
            throw ValidationException::withMessages([
                'services' => 'في خدمات غير موجودة أو غير مفعلة.',
            ]);
        }

        $total = 0.0;
        foreach ($servicesInput as $item) {
            $id  = (int) $item['id'];
            $qty = (int) ($item['quantity'] ?? 1);

            // الخدمة سعر ثابت مرة واحدة
            $total += ((float) $services[$id]->price) * $qty;
        }

        return round($total, 2);
    }
}
