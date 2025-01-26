<?php

namespace App\Http\Controllers;

use App\Constants\Messages;
use App\Http\Resources\TravelResource;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);

        $travelsData = Schedule::orderBy('created_at', 'asc')->paginate($limit);

        $travels = TravelResource::collection($travelsData);

        return $this->sendResponse(
            [
                'travels' => $travels,
                'meta' => [
                    'current_page' => $travelsData->currentPage(),
                    'last_page' => $travelsData->lastPage(),
                    'per_page' => $travelsData->perPage(),
                    'total' => $travelsData->total(),
                ],
                'links' => [
                    'first' => $travelsData->url(1),
                    'last' => $travelsData->url($travelsData->lastPage()),
                    'prev' => $travelsData->previousPageUrl(),
                    'next' => $travelsData->nextPageUrl(),
                ],
            ],
            'Schedule list'
        );
    }

    public function show($id): JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return $this->sendError(Messages::SCHEDULE_NOT_FOUND, []);
        }

        return $this->sendResponse(
            new TravelResource($schedule),
            'Schedule detail'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'departure' => ['required', 'string'],
            'destination' => ['required', 'string'],
            'departure_time' => ['required', 'date'],
            'arrival_time' => ['required', 'date'],
            'quota' => ['required', 'integer'],
            'price' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ],
            [
                'price.regex' => 'Price must be numeric with 2 decimal places',
            ]);

        $store = Schedule::create($data);

        return $this->sendResponse(
            new TravelResource($store),
            'Schedule created',
            201
        );
    }

    public function update($id, Request $request): JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return $this->sendError(Messages::SCHEDULE_NOT_FOUND, []);
        }

        $data = $request->validate([
            'departure' => ['sometimes', 'string'],
            'destination' => ['sometimes', 'string'],
            'departure_time' => ['sometimes', 'date'],
            'arrival_time' => ['sometimes', 'date'],
            'quota' => ['sometimes', 'integer'],
            'price' => ['sometimes', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
        ],
        [
            'price.regex' => 'Price must be numeric with 2 decimal places',
        ]);

        $schedule->fill($data);
        $schedule->save();

        return $this->sendResponse(
            new TravelResource($schedule),
            'Schedule updated'
        );
    }

    public function destroy($id): JsonResponse
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return $this->sendError(Messages::SCHEDULE_NOT_FOUND, []);
        }

        $schedule->delete();

        return $this->sendResponse(
            [],
            'Schedule deleted'
        );
    }
}
