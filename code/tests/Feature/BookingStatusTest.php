<?php

namespace Tests\Feature;

use App\Models\Booking;
use Tests\TestCase;

class BookingStatusTest extends TestCase
{
    public function test_booking_can_transition_from_pending_to_confirmed()
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $this->assertTrue($booking->canTransitionTo('confirmed'));
    }

    public function test_booking_cannot_transition_directly_from_pending_to_completed()
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $this->assertFalse($booking->canTransitionTo('completed'));
    }
}
