<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'category'    => $this->category,
            'duration'    => $this->duration,
            'price'       => $this->price,
            'is_published'=> $this->is_published,
            'provider'    => [
                'id' => $this->provider?->id,
                'name' => $this->provider?->name,
                'email' => $this->provider?->email,
            ],
        ];
    }
}
