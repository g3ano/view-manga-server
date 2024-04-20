<?php

namespace App\Policies\v1;

use App\Models\v1\Role;
use App\Models\v1\User;

class TagPolicy
{
    public function before(User $user, string $ability)
    {
        foreach ($user->roles as $role) {
            if ($role->id === Role::ADMIN) {
                return true;
            }
        }
        return null;
    }

    public function create(User $user)
    {
        if (!empty($user->roles)) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN || $role->id === Role::MODERATOR) {
                    return true;
                }
            }
        }

        return false;
    }

    public function update(User $user)
    {
        if (!empty($user->roles)) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN || $role->id === Role::MODERATOR) {
                    return true;
                }
            }
        }

        return false;
    }

    public function delete(User $user)
    {
        if (!empty($user->roles)) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN) {
                    return true;
                }
            }
        }

        return false;
    }
}
