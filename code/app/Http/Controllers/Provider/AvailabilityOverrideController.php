<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailabilityOverrideRequest;
use App\Http\Resources\AvailabilityOverrideResource;
use App\Models\AvailabilityOverride;
use Illuminate\Http\Request;

class AvailabilityOverrideController extends Controller
{
    public function store(AvailabilityOverrideRequest $request)
    {
        $data = $request->validated();
        $data['provider_id'] = auth()->id();

        $override = AvailabilityOverride::create($data);

        return new AvailabilityOverrideResource($override);
    }
}
