<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PetType\PetTypeStoreRequest;
use App\Http\Requests\Admin\PetType\PetTypeUpdateRequest;
use App\Http\Resources\PetTypeResource;
use App\Models\PetType;
use App\Services\PetTypeService;
use Illuminate\Http\Request;
use Throwable;

class PetTypeController extends Controller
{
    public function __construct(private PetTypeService $petTypeService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $petTypes = $this->petTypeService->index($perPage);

        return PetTypeResource::collection($petTypes);
    }

    public function store(PetTypeStoreRequest $request)
    {
        $petType = $this->petTypeService->create($request->validated());
        return response()->json([
            'message' => __('messages.pet_type.created'),
            'type' => new PetTypeResource($petType),
        ], 201);
    }

    public function update(PetTypeUpdateRequest $request, PetType $petType)
    {
        $petType = $this->petTypeService->update($petType, $request->validated());
        return response()->json([
            'message' => __('messages.pet_type.updated'),
            'petType' => new PetTypeResource($petType)
        ], 200);
    }

    public function destroy(PetType $petType)
    {
        $this->petTypeService->delete($petType);
        return response()->json([
            'message' => __('messages.pet_type.deleted'),
        ], 200);
    }
}
