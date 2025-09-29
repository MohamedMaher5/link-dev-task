<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{

    public function view(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return $booking->customer_id === $user->id || $booking->provider_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('customer') || $user->hasRole('admin');
    }

    public function confirm(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return $user->hasRole('provider') && $booking->provider_id === $user->id;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return $user->hasRole('provider') && $booking->provider_id === $user->id;
    }
}
