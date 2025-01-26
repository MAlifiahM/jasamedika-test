<?php

namespace App\Constants;

class BookingStatus
{
    public const PENDING = 'pending';
    public const CONFIRMED = 'confirmed';
    public const CANCELLED = 'cancelled';

    public const ALL = [
        self::PENDING,
        self::CONFIRMED,
        self::CANCELLED
    ];
}
