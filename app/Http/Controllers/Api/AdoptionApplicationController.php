<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AdoptionApplication\AdoptionApplicationStoreRequest;
use App\Http\Resources\AdoptionApplicationResource;
use App\Models\AdoptionApplication;
use App\Models\User;
use App\Services\AdoptionApplicationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdoptionApplicationController extends Controller
{

    public function __construct(private AdoptionApplicationService $adoptionApplicationService)
    {
    }

    public function index()
    {
        $user = auth()->user();

        $applications = $this->adoptionApplicationService->getApplicationsForUser($user);

        return AdoptionApplicationResource::collection($applications);
    }

    public function petApplications(int $petId)
    {
        $applications = $this->adoptionApplicationService->getApplicationsForPet($petId);

        return AdoptionApplicationResource::collection($applications);
    }

    public function store(AdoptionApplicationStoreRequest $request)
    {
        $data = $request->validated();

        $application = $this->adoptionApplicationService->createApplication($request->user(), $data);

        return new AdoptionApplicationResource($application);
    }

    public function show(AdoptionApplication $adoptionApplication)
    {
        $application = $this->adoptionApplicationService->showDetails($adoptionApplication->id);

        return new AdoptionApplicationResource($application);
    }

    public function update(AdoptionApplication $adoptionApplication, Request $request)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $application = $this->adoptionApplicationService->updateApplication($adoptionApplication->id, $data);

        return new AdoptionApplicationResource($application);
    }

    public function cancel(AdoptionApplication $adoptionApplication)
    {
        $application = $this->adoptionApplicationService->cancelApplication($adoptionApplication->id);

        return new AdoptionApplicationResource($application);
    }

    public function destroy(AdoptionApplication $adoptionApplication)
    {
        $this->adoptionApplicationService->deleteApplication($adoptionApplication->id);

        return response()->json(null, 204);
    }
}
