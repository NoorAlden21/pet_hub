<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PetBreed\PetBreedStoreRequest;
use App\Http\Requests\Admin\PetBreed\PetBreedUpdateRequest;
use App\Http\Resources\PetBreedResource;
use App\Models\PetBreed;
use App\Services\PetBreedService;
use Illuminate\Http\Request;
use Throwable;

class PetBreedController extends Controller
{
    public function __construct(private PetBreedService $petBreedService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $petBreeds = $this->petBreedService->index($perPage);

        return PetBreedResource::collection($petBreeds);
    }

    public function store(PetBreedStoreRequest $request)
    {
        $petBreed = $this->petBreedService->create($request->validated());
        return response()->json([
            'message' => __('messages.pet_breed.created'),
            'breed' => new PetBreedResource($petBreed),
        ], 201);
    }

    public function show(PetBreed $petBreed)
    {
        $petBreed = $this->petBreedService->getDetails($petBreed);
        return new PetBreedResource($petBreed);
    }

    public function update(PetBreedUpdateRequest $request, PetBreed $petBreed)
    {
        $petBreed = $this->petBreedService->update($petBreed, $request->validated());
        return response()->json([
            'message' => __('messages.pet_breed.updated'),
            'breed' => new PetBreedResource($petBreed)
        ], 200);
    }

    public function destroy(PetBreed $petBreed)
    {
        $this->petBreedService->delete($petBreed);
        return response()->json([
            'message' => __('messages.pet_breed.deleted'),
        ], 200);
    }
}
