<?php

namespace App\Policies\v1;

use App\Models\v1\Role;
use App\Models\v1\User;

class UserPolicy
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


    public function viewAny(User $user)
    {
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN) {
                    return true;
                }
            }
        }

        return false;
    }

    public function toggleRole(User $user)
    {
        if (count($user->roles)) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN) {
                    return true;
                }
            }
        }

        return false;
    }

    public function delete(User $user)
    {
        if (count($user->roles)) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN) {
                    return true;
                }
            }
        }

        return false;
    }
}
