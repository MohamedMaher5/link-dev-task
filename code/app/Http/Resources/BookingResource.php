<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'status'    => $this->status,
            'start_time'=> $this->start_time->toDateTimeString(),
            'end_time'  => $this->end_time->toDateTimeString(),

            'service'   => [
                'id'    => $this->service->id,
                'name'  => $this->service->name,
                'price' => $this->service->price,
                'duration' => $this->service->duration,
            ],

            'provider'  => [
                'id'   => $this->provider->id,
                'name' => $this->provider->name,
            ],

            'customer'  => [
                'id'   => $this->customer->id,
                'name' => $this->customer->name,
            ],

            'created_at'=> $this->created_at->toDateTimeString(),
        ];
    }
}
