<?php

namespace App\Services;

use App\Events\BookingStatusChanged;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function getUserBookings(User $user): Collection
    {
        if ($user->hasRole('customer')) {
            return $user->customerBookings()->latest()->get();
        }

        if ($user->hasRole('provider')) {
            return $user->providerBookings()->latest()->get();
        }

        return Booking::all();
    }

    public function createBooking(User $customer, array $data): Booking
    {
        $service = Service::findOrFail($data['service_id']);

        $start = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['start_time']);
        $end   = $start->copy()->addMinutes($service->duration);

        if ($start->isPast()) {
            throw ValidationException::withMessages([
                'start_time' => 'Cannot book in the past.'
            ]);
        }

        $overlap = Booking::where('provider_id', $service->provider_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_time', '<=', $start)
                            ->where('end_time', '>=', $end);
                    });
            })
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_time' => 'Slot already booked.'
            ]);
        }

        return Booking::create([
            'service_id'  => $service->id,
            'provider_id' => $service->provider_id,
            'customer_id' => $customer->id,
            'start_time'  => $start,
            'end_time'    => $end,
            'status'      => 'pending',
        ]);
    }

    public function changeStatusAsProvider(Booking $booking, string $newStatus): Booking
    {
        if ($booking->provider_id !== auth()->id()) {
            throw ValidationException::withMessages([
                'booking' => 'You are not allowed to modify this booking.',
            ]);
        }

        if (! $booking->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Invalid status transition from {$booking->status} to {$newStatus}",
            ]);
        }

        $booking->status = $newStatus;
        $booking->save();

        event(new BookingStatusChanged($booking, $newStatus));

        return $booking;
    }
}
