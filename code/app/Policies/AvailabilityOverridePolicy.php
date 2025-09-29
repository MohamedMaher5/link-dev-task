<?php

namespace App\Policies;

use App\Models\User;

class AvailabilityOverridePolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('provider') || $user->hasRole('admin');
    }
}
