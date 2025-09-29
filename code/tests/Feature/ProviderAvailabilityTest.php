<?php

namespace Tests\Feature;

use App\Models\ProviderAvailability;
use App\Models\User;
use Tests\TestCase;

class ProviderAvailabilityTest extends TestCase
{
    public function test_provider_cannot_have_overlapping_availability()
    {
        $provider = User::factory()->create()->assignRole('provider');

        ProviderAvailability::create([
            'provider_id' => $provider->id,
            'day_of_week' => 1,
            'start_time' => '10:00',
            'end_time'   => '12:00',
        ]);

        $overlap = ProviderAvailability::where('provider_id', $provider->id)
            ->where('day_of_week', 1)
            ->where(function ($q) {
                $q->whereBetween('start_time', ['11:00', '11:30']);
            })->exists();

        $this->assertTrue($overlap);
    }
}
