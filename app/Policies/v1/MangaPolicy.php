<?php

namespace App\Policies\v1;

use App\Models\v1\Manga;
use App\Models\v1\Role;
use App\Models\v1\User;

class MangaPolicy
{
    private $roles = [
        Role::ADMIN,
        Role::MODERATOR,
    ];

    public function before(User $user, string $ability)
    {
        foreach ($user->roles as $role) {
            if ($role->id === Role::ADMIN) {
                return true;
            }
        }

        return null;
    }

    public function viewUnapprovedAny(User $user)
    {
        if (count($user->roles)) {
            foreach ($user->roles as $role) {
                if (in_array($role->id, $this->roles)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function viewUnapprovedTeam(User $user, int $teamId)
    {
        $userRoles = array_column($user->roles->toArray(), 'id');

        if (in_array(Role::MODERATOR, $userRoles)) {
            return true;
        }

        if (
            in_array(Role::TEAM_LEADER, $userRoles) ||
            in_array(Role::TEAM_MEMBER, $userRoles)
        ) {
            foreach ($user->teams as $team) {
                if ($team->id === $teamId) {
                    return true;
                }
            }
        }

        return false;
    }

    public function viewUnapproved(User $user, Manga $manga)
    {
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if (in_array($role->id, $this->roles)) {
                    return true;
                }
            }
        }

        if ($user->teams) {
            foreach ($user->teams as $team) {
                if ($team->id === $manga->team_id) {
                    return true;
                }
            }
        }

        return false;
    }

    public function approve(User $user, Manga $manga)
    {
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if (in_array($role->id, $this->roles)) {
                    if ($manga->is_approved === 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function create(User $user)
    {
        $userRoles = array_column($user->roles->toArray(), 'id');

        if (
            in_array(Role::TEAM_MEMBER, $userRoles) ||
            in_array(Role::TEAM_LEADER, $userRoles)
        ) {
            return true;
        }

        return false;
    }

    public function update(User $user)
    {
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if (in_array($role->id, $this->roles)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function delete(User $user)
    {
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if (in_array($role->id, $this->roles)) {
                    return true;
                }
            }
        }

        return false;
    }
}
