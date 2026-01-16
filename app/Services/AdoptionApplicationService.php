<?php

namespace App\Services;

use App\Models\AdoptionApplication;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;

class AdoptionApplicationService
{
    protected UserNotificationsService $notificationService;

    public function __construct(UserNotificationsService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function getApplicationsForUser($user)
    {
        if ($user->hasRole('admin')) {
            return AdoptionApplication::with(['pet.coverImage', 'user'])->get();
        }

        return AdoptionApplication::with(['pet.coverImage'])->where('user_id', $user->id)->get();
    }

    public function getApplicationsForPet($petId)
    {
        return AdoptionApplication::with(['user'])->where('pet_id', $petId)->get();
    }

    public function createApplication($user, $data)
    {
        return DB::transaction(function () use ($user, $data) {

            $exists = $user->adoptionApplications()
                ->where('pet_id', $data['pet_id'])
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($exists) {
                throw new \Exception(__('errors.application_already_exists'));
            }

            $app = AdoptionApplication::create([
                'pet_id'     => $data['pet_id'],
                'user_id'    => $user->id,
                'motivation' => $data['motivation'],
                'status'     => 'pending',
            ]);

            $this->notificationService->notifyAdmins(
                'adoption_application_submitted',
                __('notifications.adoption_application_submitted_title'),
                __('notifications.adoption_application_submitted_body', ['user' => $user->name]),
                ['application_id' => $app->id, 'pet_id' => $app->pet_id]
            );

            return $app;
        });
    }
    public function showDetails($applicationId)
    {
        return AdoptionApplication::with(['pet.coverImage', 'user'])->findOrFail($applicationId);
    }

    public function updateApplication($applicationId, $data)
    {
        return DB::transaction(function () use ($applicationId, $data) {

            $application = AdoptionApplication::with(['user', 'pet'])->lockForUpdate()->findOrFail($applicationId);
            $application->update($data);

            $status = (string) $application->status;

            if ($status === 'approved') {
                $application->pet->update(['is_adoptable' => false]);
                $application->pet->owner_id = $application->user_id;
                $application->pet->save();

                //reject the other adoptionApplication for this specific pet and notify thier users
                $otherApplications = AdoptionApplication::with('user')
                    ->where('pet_id', $application->pet_id)
                    ->where('id', '!=', $application->id)
                    ->where('status', '!=', 'rejected')
                    ->get();

                foreach ($otherApplications as $otherApp) {
                    $otherApp->update(['status' => 'rejected']);

                    $this->notificationService->notifyUser(
                        $otherApp->user,
                        'adoption_application_rejected',
                        __("notifications.adoption_application_rejected_title"),
                        __("notifications.adoption_application_rejected_body", [
                            'status' => __('notifications.status_rejected')
                        ]),
                        [
                            'application_id' => $otherApp->id,
                            'pet_id' => $otherApp->pet_id,
                            'status' => 'rejected',
                        ]
                    );
                }
            }

            // ✅ اختر نوع الإشعار حسب الحالة
            $notifKey = match ($status) {
                'approved' => 'adoption_application_approved',
                'rejected' => 'adoption_application_rejected',
                default    => 'adoption_application_status_updated',
            };

            // ✅ لو استخدمت الرسالة العامة، الأفضل ترجمة status للعرض
            $statusLabel = match ($status) {
                'approved' => __('notifications.status_approved'),
                'rejected' => __('notifications.status_rejected'),
                'pending'  => __('notifications.status_pending'),
                default    => $status,
            };

            $this->notificationService->notifyUser(
                $application->user,
                $notifKey,
                __("notifications.{$notifKey}_title"),
                __("notifications.{$notifKey}_body", [
                    'status' => $statusLabel, // فقط لو الرسالة تحتاج :status
                ]),
                [
                    'application_id' => $application->id,
                    'pet_id' => $application->pet_id,
                    'status' => $status,
                ]
            );

            return $application;
        });
    }


    public function deleteApplication($applicationId)
    {
        $application = AdoptionApplication::findOrFail($applicationId);
        $application->delete();
    }
}
