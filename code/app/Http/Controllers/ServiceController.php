<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    protected ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function index(): JsonResponse
    {
        $services = $this->serviceService->getPublishedServices();
        return response()->json(ServiceResource::collection($services));
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        $service = $this->serviceService->createService($request->validated());
        return response()->json(new ServiceResource($service), 201);
    }

    public function show(int $id): JsonResponse
    {
        $service = $this->serviceService->getServiceById($id);
        return response()->json(new ServiceResource($service));
    }
}
