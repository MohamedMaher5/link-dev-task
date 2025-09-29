<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailabilityRequest;
use App\Http\Resources\AvailabilityResource;
use App\Models\ProviderAvailability;

class AvailabilityController extends Controller
{
    public function store(AvailabilityRequest $request)
    {
        $data = $request->validated();
        $data['provider_id'] = auth()->id();

        $availability = ProviderAvailability::create($data);

        return new AvailabilityResource($availability);
    }
}
