<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $provider = User::factory()->provider()->create();
        $service = Service::factory()->for($provider, 'provider')->create();
        $customer = User::factory()->customer()->create();

        $start = Carbon::now()->addDays(rand(1, 10))->setTime(rand(9, 18), 0);
        $end = (clone $start)->addMinutes($service->duration_minutes);

        return [
            'provider_id' => $provider->id,
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'start_time' => $start,
            'end_time' => $end,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn() => ['status' => 'confirmed']);
    }

    public function expired(): static
    {
        return $this->state(fn() => [
            'status' => 'pending',
            'created_at' => now()->subDays(3),
        ]);
    }
}
