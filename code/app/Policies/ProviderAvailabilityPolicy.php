<?php

namespace App\Policies;

use App\Models\ProviderAvailability;
use App\Models\User;

class ProviderAvailabilityPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('provider') || $user->hasRole('admin');
    }
}
