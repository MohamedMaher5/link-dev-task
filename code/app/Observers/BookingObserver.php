<?php

namespace App\Observers;

use App\Mail\BookingCreatedMail;
use App\Models\Booking;
use App\Notifications\BookingCreatedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingObserver
{
    public function created(Booking $booking): void
    {
        Log::info("New booking created: {$booking->id} for service {$booking->service_id}");

        Mail::to($booking->customer->email)->send(new BookingCreatedMail($booking));

        $booking->provider->notify(new BookingCreatedNotification($booking));
    }

    public function deleted(Booking $booking): void
    {
        Log::warning("Booking soft deleted: {$booking->id}");
    }
}
