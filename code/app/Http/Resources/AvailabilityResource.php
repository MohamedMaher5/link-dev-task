<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'provider_id' => $this->provider_id,
            'day_of_week' => $this->day_of_week,
            'day_name'    => Carbon::create()->startOfWeek()->addDays($this->day_of_week)->format('l'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time
        ];
    }
}
