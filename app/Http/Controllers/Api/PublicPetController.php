<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use App\Services\PetService;
use Illuminate\Http\Request;

class PublicPetController extends Controller
{
    public function __construct(private PetService $petService)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);
        $pets = $this->petService->publicIndex($perPage);
        return PetResource::collection($pets);
    }

    public function show(Pet $pet)
    {
        // optionally ensure it's adoptable/public
        abort_unless($pet->is_adoptable, 404);

        return new PetResource($this->petService->getDetails($pet));
    }
}
