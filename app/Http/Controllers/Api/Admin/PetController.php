<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pet\PetStoreRequest;
use App\Http\Requests\Admin\Pet\PetUpdateRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Services\PetService;
use Illuminate\Http\Request;
use Throwable;

class PetController extends Controller
{
    public function __construct(private PetService $petService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $pets = $this->petService->index($perPage);

        return PetResource::collection($pets);
    }

    public function store(PetStoreRequest $request)
    {
        $pet = $this->petService->create($request->validated());
        return response()->json([
            'message' => __('messages.pet.created'),
            'pet' => new PetResource($pet),
        ], 201);
    }

    public function update(PetUpdateRequest $request, Pet $pet)
    {
        $pet = $this->petService->update($pet, $request->validated());
        return response()->json([
            'message' => __('messages.pet.updated'),
            'pet' => new PetResource($pet)
        ], 200);
    }

    public function destroy(Pet $pet)
    {
        $this->petService->delete($pet);
        return response()->json([
            'message' => __('messages.pet.deleted'),
        ], 200);
    }
}
