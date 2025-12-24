<?php

namespace App\Services;

use App\Models\AdoptionApplication;
use Illuminate\Support\Facades\DB;

class AdoptionApplicationService
{
    public function getApplicationsForUser($user)
    {
        if ($user->hasRole('admin')) {
            return AdoptionApplication::with(['pet', 'user'])->get();
        }

        return AdoptionApplication::with(['pet'])->where('user_id', $user->id)->first();
    }

    public function getApplicationsForPet($petId)
    {
        return AdoptionApplication::with(['user'])->where('pet_id', $petId)->get();
    }

    public function createApplication($user, $data)
    {
        $exists = $user->adoptionApplications()
            ->where('pet_id', $data['pet_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            throw new \Exception(__('errors.application_already_exists'));
        }

        return AdoptionApplication::create([
            'pet_id' => $data['pet_id'],
            'user_id' => $user->id,
            'motivation' => $data['motivation'],
            'status' => 'pending',
        ]);
    }

    public function showDetails($applicationId)
    {
        return AdoptionApplication::with(['pet', 'user'])->findOrFail($applicationId);
    }

    public function updateApplication($applicationId, $data)
    {
        $application = AdoptionApplication::findOrFail($applicationId);
        $application->update($data);
        return $application;
    }

    public function deleteApplication($applicationId)
    {
        $application = AdoptionApplication::findOrFail($applicationId);
        $application->delete();
    }
}
