<?php

namespace App\Policies\v1;

use App\Models\v1\Chapter;
use App\Models\v1\Role;
use App\Models\v1\User;
use Illuminate\Auth\Access\Response;

class ChapterPolicy
{
    public function before(User $user, string $ability)
    {
        if ($ability === 'create') {
            return null;
        }

        foreach ($user->roles as $role) {
            if ($role->id === Role::ADMIN) {
                return true;
            }
        }

        return null;
    }

    public function create(User $user, Chapter $chapter, int $teamId): Response
    {
        $passed = false;

        if ($user->roles) {
            foreach ($user->roles as $role) {
                if ($role->id === Role::TEAM_MEMBER || $role->id === Role::TEAM_LEADER) {
                    $passed = true;
                }
            }
        }

        foreach ($chapter->teams as $team) {
            if ($team->id === $teamId) {
                return Response::deny('', 302);
            }
        }

        return $passed
            ? Response::allow()
            : Response::deny('', 404);
    }

    public function update(User $user, Chapter $chapter): bool
    {
        return false;
    }

    public function delete(User $user, Chapter $chapter): bool
    {
        return false;
    }
}
