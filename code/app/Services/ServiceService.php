<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class ServiceService
{
    public function getPublishedServices(): Collection
    {
        return Service::where('is_published', true)->get();
    }

    public function getServiceById(int $id): Service
    {
        return Service::findOrFail($id);
    }

    public function createService(array $data): Service
    {
        $data['provider_id'] = auth()->id();
        return Service::create($data);
    }
}
