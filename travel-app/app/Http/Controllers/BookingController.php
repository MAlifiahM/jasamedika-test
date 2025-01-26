<?php

namespace App\Http\Controllers;

use App\Constants\BookingStatus;
use App\Constants\Messages;
use App\Constants\Roles;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);

        $user = auth()->user();

        if ($user->role === Roles::ADMIN) {
            $bookingsData = Booking::orderBy('created_at', 'asc')->paginate($limit);
        } else {
            $bookingsData = Booking::where('user_id', $user->id)->orderBy('created_at', 'asc')->paginate($limit);
        }

        $bookings = BookingResource::collection($bookingsData);

        return $this->sendResponse(
            [
                'bookings' => $bookings,
                'meta' => [
                    'current_page' => $bookingsData->currentPage(),
                    'last_page' => $bookingsData->lastPage(),
                    'per_page' => $bookingsData->perPage(),
                    'total' => $bookingsData->total(),
                ],
                'links' => [
                    'first' => $bookingsData->url(1),
                    'last' => $bookingsData->url($bookingsData->lastPage()),
                    'prev' => $bookingsData->previousPageUrl(),
                    'next' => $bookingsData->nextPageUrl(),
                ],
            ],
            'Bookings list'
        );
    }

    public function show($id): JsonResponse
    {
        $user = auth()->user();

        if ($user->role === Roles::ADMIN) {
            $booking = Booking::find($id);
        } else {
            $booking = Booking::where('user_id', $user->id)->where('id', $id)->first();
        }

        if (!$booking) {
            return $this->sendError(Messages::BOOKING_NOT_FOUND, []);
        }

        return $this->sendResponse(
            new BookingResource($booking),
            'Booking detail'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $schedule = Schedule::where('id', $data['schedule_id'])->first();

        if ($schedule->quota < $data['qty']) {
            return $this->sendError('Quota not available', [], 400);
        }

        $user_id = $data['user_id'] ?? auth()->user()->id;

        $data['user_id'] = $user_id;
        $data['status'] = BookingStatus::PENDING;

        $store = Booking::create($data);

        $paymentData = [
            'booking_id' => $store->id,
            'amount' => $schedule->price * $data['qty'],
        ];

        Payment::create($paymentData);

        $schedule->update(['quota' => $schedule->quota - $data['qty']]);
        $schedule->save();
        return $this->sendResponse(
            new BookingResource($store),
            'Booking created',
            201
        );
    }

    public function update($id, Request $request): JsonResponse
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return $this->sendError(Messages::BOOKING_NOT_FOUND, []);
        }

        $user = auth()->user();

        $error = $this->validateAuthorizationAndConfirm($booking, $user, $request);
        if ($error) {
            return $error;
        }

        $data = $request->validate([
            'status' => ['required', 'string', 'in:'. implode(',', BookingStatus::ALL)],
        ]);

        $booking->fill($data);
        $booking->save();

        return $this->sendResponse(
            new BookingResource($booking),
            'Booking updated'
        );
    }

    private function validateAuthorizationAndConfirm($booking, $user, Request $request): ?JsonResponse
    {
        if ($booking->user_id !== $user->id && $user->role !== Roles::ADMIN) {
            return $this->sendError('Unauthorized', [], 403);
        }

        $payment = Payment::where('booking_id', $booking->id)->first();

        if ($request->input('status') === BookingStatus::CONFIRMED && !$payment->payment_proof) {
            return $this->sendError('Payment proof is required', [], 400);
        }

        return null;
    }
}
