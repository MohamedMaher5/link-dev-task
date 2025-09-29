<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function view(User $user = null, Service $service): bool
    {
        if (!$user) {
            return (bool) $service->is_published;
        }

        if ($user->hasRole('admin')) return true;

        if ($user->hasRole('provider') && $service->provider_id === $user->id) {
            return true;
        }

        return $service->is_published;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('provider') || $user->hasRole('admin');
    }

    public function publish(User $user, Service $service): bool
    {
        if ($user->hasRole('admin')) return true;

        return $user->hasRole('provider') && $service->provider_id === $user->id;
    }
}
