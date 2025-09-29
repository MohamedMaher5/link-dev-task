<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Service;
use App\Models\User;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SlotsController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'provider_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'days' => 'integer|min:1|max:30'
        ]);

        $provider = User::findOrFail($request->input('provider_id'));
        $service = Service::findOrFail($request->input('service_id'));

        if ($service->provider_id !== $provider->id) {
            return response()->json(['message' => 'Service does not belong to provider'], 403);
        }

        $days = $request->input('days', 7);

        $slots = $this->availabilityService->generateSlots($provider, $service, $days);

        return response()->json(['provider' => new UserResource($provider), 'service' => $service->id, 'slots' => $slots]);
    }
}
