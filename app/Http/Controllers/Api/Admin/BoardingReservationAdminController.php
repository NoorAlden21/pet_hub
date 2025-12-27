<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Boarding\BoardingReservationStatusRequest;
use App\Http\Resources\BoardingReservationResource;
use App\Models\BoardingReservation;
use App\Services\BoardingReservationService;

class BoardingReservationAdminController extends Controller
{
    public function __construct(private BoardingReservationService $service)
    {
    }

    public function index()
    {
        return BoardingReservationResource::collection($this->service->adminList());
    }

    public function show(BoardingReservation $boardingReservation)
    {
        $boardingReservation->load('services');
        return new BoardingReservationResource($boardingReservation);
    }

    public function updateStatus(BoardingReservationStatusRequest $request, BoardingReservation $boardingReservation)
    {
        $reservation = $this->service->updateStatus($boardingReservation, $request->validated());
        return new BoardingReservationResource($reservation);
    }

    public function delete(BoardingReservation $boardingReservation)
    {
        $boardingReservation->delete();
        return response()->json([], 204);
    }
}
