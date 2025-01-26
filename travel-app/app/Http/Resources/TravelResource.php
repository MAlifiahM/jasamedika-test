<?php

namespace App\Http\Resources;

use App\Constants\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'departure' => $this->departure,
            'destination' => $this->destination,
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'quota' => $this->quota,
            'price' => $this->price,
//            'bookings' => BookingResource::collection($this->bookings),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
