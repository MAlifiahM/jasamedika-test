<?php

namespace App\Http\Controllers;

use App\Constants\BookingStatus;
use App\Constants\Messages;
use App\Constants\Roles;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);

        $user = auth()->user();

        if ($user->role === Roles::ADMIN) {
            $paymentsData = Payment::paginate($limit);
        } else {
            $paymentsData = Payment::whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->paginate($limit);
        }

        $payments = PaymentResource::collection($paymentsData);

        return $this->sendResponse(
            [
                'payments' => $payments,
                'meta' => [
                    'current_page' => $paymentsData->currentPage(),
                    'last_page' => $paymentsData->lastPage(),
                    'per_page' => $paymentsData->perPage(),
                    'total' => $paymentsData->total(),
                ],
                'links' => [
                    'first' => $paymentsData->url(1),
                    'last' => $paymentsData->url($paymentsData->lastPage()),
                    'prev' => $paymentsData->previousPageUrl(),
                    'next' => $paymentsData->nextPageUrl(),
                ],
            ],
            'Payment list'
        );
    }

    public function show($id): JsonResponse
    {
        $user = auth()->user();

        if ($user->role === Roles::ADMIN) {
            $payment = Payment::find($id);
        } else {
            $payment = Payment::whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('id', $id)->first();
        }

        if (!$payment) {
            return $this->sendError(Messages::PAYMENT_NOT_FOUND, []);
        }

        return $this->sendResponse(
            new PaymentResource($payment),
            'payment detail'
        );
    }

    public function update($id, Request $request): JsonResponse
    {
        $user = auth()->user();
        $payment = Payment::find($id);

        $errorResponse = $this->validatePaymentAndBooking($payment, $user);

        if ($errorResponse) {
            return $errorResponse;
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $filepath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $payment->fill(['payment_proof' => $filepath]);
        $payment->save();

        $booking = Booking::where('id', $payment->booking_id)->first();
        $booking->update(['status' => BookingStatus::CONFIRMED]);
        $booking->save();

        return $this->sendResponse(
            new PaymentResource($payment),
            'Payment updated'
        );
    }

    private function validatePaymentAndBooking($payment, $user): ?JsonResponse
    {
        if (!$payment) {
            return $this->sendError(Messages::PAYMENT_NOT_FOUND, []);
        }

        $booking = Booking::find($payment->booking_id);

        if (!$booking) {
            return $this->sendError(Messages::BOOKING_NOT_FOUND, []);
        }

        if ($booking->user_id !== $user->id) {
            return $this->sendError('Unauthorized', [], 403);
        }

        return null;
    }
}
