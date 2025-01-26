<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'departure',
        'destination',
        'departure_time',
        'arrival_time',
        'quota',
        'price'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }
}
