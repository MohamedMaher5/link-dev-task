<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReportTest extends TestCase
{
    public function test_admin_can_fetch_bookings_per_provider_report()
    {
        $admin = User::factory()->create()->assignRole('admin');

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/reports/bookings-per-provider');

        $response->assertStatus(200)
            ->assertJsonStructure([['provider_id', 'total']]);
    }
}
