<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Boarding\BoardingQuoteRequest;
use App\Http\Requests\User\Boarding\BoardingReservationStoreRequest;
use App\Http\Resources\BoardingReservationResource;
use App\Models\BoardingReservation;
use App\Services\BoardingReservationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BoardingReservationController extends Controller
{
    public function __construct(private BoardingReservationService $service)
    {
    }

    // POST /api/boarding-reservations/quote
    public function quote(BoardingQuoteRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->service->quote($request->validated())]);
    }

    // GET /api/my/boarding-reservations
    public function index(Request $request)
    {
        $reservations = $this->service->listForUser($request->user());
        return BoardingReservationResource::collection($reservations);
    }

    // POST /api/my/boarding-reservations
    public function store(BoardingReservationStoreRequest $request): JsonResponse
    {
        $reservation = $this->service->create($request->user(), $request->validated());

        return (new BoardingReservationResource($reservation))
            ->response()
            ->setStatusCode(201);
    }

    // GET /api/my/boarding-reservations/{boardingReservation}
    public function show(BoardingReservation $boardingReservation)
    {
        $boardingReservation = $this->service->getDetails($boardingReservation);
        return new BoardingReservationResource($boardingReservation);
    }

    // POST /api/my/boarding-reservations/{boardingReservation}/cancel
    public function cancel(BoardingReservation $boardingReservation): JsonResponse
    {
        $reservation = $this->service->cancel($boardingReservation);

        return response()->json([
            'data' => (new BoardingReservationResource($reservation))->resolve(),
        ]);
    }
}
