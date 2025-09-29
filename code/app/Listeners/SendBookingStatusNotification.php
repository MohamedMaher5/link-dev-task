<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use App\Notifications\BookingStatusNotification;

class SendBookingStatusNotification
{
    /**
     * Handle the event.
     */
    public function handle(BookingStatusChanged $event): void
    {
        $booking = $event->booking;

        $booking->customer->notify(new BookingStatusNotification($booking, $event->newStatus));
    }
}
