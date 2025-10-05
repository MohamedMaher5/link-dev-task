<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateBookingTest extends TestCase
{
    public function test_customer_can_create_booking()
    {
        $customer = User::factory()->create()->assignRole('customer');
        $service  = Service::factory()->create();

        Sanctum::actingAs($customer);

        $response = $this->postJson('/api/bookings', [
            'service_id' => $service->id,
            'date' => now()->addDay()->toDateString(),
            'start_time' => now()->addDay()->format('H:i'),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'service', 'status']
            ]);
    }
}
