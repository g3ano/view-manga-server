<?php

namespace App\Policies\v1;

use App\Models\v1\Role;
use App\Models\v1\Team;
use App\Models\v1\User;

class TeamPolicy
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
        if ($user->roles) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::ADMIN) {
                    return true;
                };
            }
        }
        return count($user->teams) < Team::TEAM_LIMIT;
    }

    public function viewUnapprovedMangaTeam(User $user)
    {
        foreach ($user->roles as $role) {
            if ($role->id === Role::MODERATOR) {
                return true;
            }
        }

        return false;
    }

    /**
     * This includes actions: view, accept and refuse
     */
    public function handlePendingMembers(User $user, Team $team)
    {
        foreach ($team->members  as $teamMember) {
            if (
                $teamMember->pivot->is_leader &&
                $teamMember->id === $user->id
            ) {
                return true;
            }
        }
    }

    public function update(User $user, Team $team)
    {
        if (in_array(
            Role::ADMIN,
            array_column($user->roles->toArray(), 'id')
        )) {
            return true;
        }

        if (in_array(Role::TEAM_LEADER, array_column(
            $user->roles->toArray(),
            'id'
        ))) {
            foreach ($team?->members as $member) {
                if ($member->pivot->user_id === $user->id && $member->pivot->is_leader === 1) {
                    return true;
                }
            }
        }

        return false;
    }

    public function delete(User $user)
    {
        return in_array(Role::ADMIN, array_column($user->roles->toArray(), 'id'));
    }
}
