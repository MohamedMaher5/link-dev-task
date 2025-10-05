<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingAuthorizationTest extends TestCase
{
    public function test_provider_cannot_confirm_another_providers_booking()
    {
        $provider1 = User::factory()->create()->assignRole('provider');
        $provider2 = User::factory()->create()->assignRole('provider');

        $booking = Booking::factory()->for(Service::factory()->for($provider1, 'provider'))->create();

        Sanctum::actingAs($provider2);

        $response = $this->patchJson("/api/provider/bookings/{$booking->id}/confirm");

        $response->assertStatus(422);
    }
}
