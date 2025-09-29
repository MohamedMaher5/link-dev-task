<?php

namespace Database\Factories;

use App\Models\AvailabilityOverride;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AvailabilityOverride>
 */
class AvailabilityOverrideFactory extends Factory
{
    protected $model = AvailabilityOverride::class;

    public function definition(): array
    {
        $date  = $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d');
        $start = $this->faker->time('H:i', '15:00');
        $end   = $this->faker->time('H:i', '23:00');

        return [
            'provider_id' => User::factory()->provider(),
            'date'        => $date,
            'start_time'  => $start,
            'end_time'    => $end,
            'type'        => $this->faker->randomElement(['block', 'open']),
            'reason'      => $this->faker->randomElement(['Vacation', 'Personal', 'Meeting', 'Pray time']),
        ];
    }
}
