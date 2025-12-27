<?php

namespace App\Services;

use App\Models\BoardingService;
use App\Models\User;

class BoardingServiceService
{
    public function list(User $user)
    {
        if ($user->hasRole('admin')) {
            return BoardingService::all();
        }
        return BoardingService::where('is_active', true)->get();
    }

    public function create(array $data): BoardingService
    {
        return BoardingService::create($data);
    }

    public function update(BoardingService $service, array $data): BoardingService
    {
        $service->update($data);
        return $service;
    }

    public function delete(BoardingService $service): void
    {
        $service->delete();
    }
}
