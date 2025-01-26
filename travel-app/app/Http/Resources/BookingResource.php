<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'user_id' => $this->user_id,
            'schedule_id' => $this->schedule_id,
            'status' => $this->status,
            'qty' => $this->qty,
            'schedule' => new TravelResource($this->schedule),
            'payment' => new PaymentResource($this->payment),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
