<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        $user = $request->user();
//
//        $filteredBookings = $user->role === 'admin'
//            ? $this->bookings
//            : $this->bookings->where('user_id', $user->id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
//            'bookings' => BookingResource::collection($filteredBookings),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
