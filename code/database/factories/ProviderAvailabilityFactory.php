<?php

namespace Database\Factories;

use App\Models\ProviderAvailability;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProviderAvailability>
 */
class ProviderAvailabilityFactory extends Factory
{
    protected $model = ProviderAvailability::class;

    public function definition(): array
    {
        $start = $this->faker->time('H:i', '15:00');
        $end   = $this->faker->time('H:i', '23:00');

        return [
            'provider_id' => User::factory()->provider(),
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'start_time'  => $start,
            'end_time'    => $end,
        ];
    }
}
