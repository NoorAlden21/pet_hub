<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Boarding\BoardingServiceStoreRequest;
use App\Http\Requests\Admin\Boarding\BoardingServiceUpdateRequest;
use App\Http\Resources\BoardingServiceResource;
use App\Models\BoardingService;
use App\Services\BoardingServiceService;
use Illuminate\Http\Request;

class  BoardingServiceController extends Controller
{
    public function __construct(private BoardingServiceService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return BoardingServiceResource::collection($this->service->list($user));
    }

    public function store(BoardingServiceStoreRequest $request)
    {
        $service = $this->service->create($request->validated());
        return response()->json([
            'message' => __('messages.boarding_service.created'),
            'boardingService' => new BoardingServiceResource($service),
        ], 201);
    }

    public function update(BoardingServiceUpdateRequest $request, BoardingService $boardingService)
    {
        $service = $this->service->update($boardingService, $request->validated());
        return response()->json([
            'message' => __('messages.boarding_service.updated'),
            'boardingService' => new BoardingServiceResource($service),
        ]);
    }

    public function destroy(BoardingService $boardingService)
    {
        $this->service->delete($boardingService);
        return response()->json([
            'message' => __('messages.boarding_service.deleted'),
        ], 204);
    }
}
