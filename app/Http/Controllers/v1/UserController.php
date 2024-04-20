<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\AddOrRemoveRoleRequest;
use App\Http\Resources\v1\TeamCollection;
use App\Http\Resources\v1\UserCollection;
use App\Http\Resources\v1\UserResource;
use App\Models\v1\Role;
use App\Models\v1\Team;
use App\Models\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user || $user->cant('viewAny', User::class)) {
            $this->failedAsNotFound('User');
        }

        $page = $request->query('page') ?? 1;

        $users = User::orderBy('updated_at', 'desc')
            ->paginate(25, ['*'], 'page', $page);

        return new UserCollection($users);
    }

    public function search(Request $request)
    {
        $querySearch = $request->query('query');
        $page = $request->query('page') ?? 1;

        $users = User::where('username', 'LIKE', '%' . $querySearch . '%')
            ->paginate(10, ['*'], 'page', $page);

        return new UserCollection($users);
    }

    public function toggleRole(string $id, AddOrRemoveRoleRequest $request)
    {
        /** @var User $auth */
        $auth = Auth::user();

        /** @var User $user */
        $user = User::with('roles')->where('id', $id)->first();


        if (!$auth || $auth->cant('toggleRole', User::class) || !$user) {
            $this->failedAsNotFound('User');
        }

        $data = $request->validated();

        $toAddRole = Role::where('name', $data['role'])->first();

        if (!$toAddRole) {
            $this->failedAsNotFound('Role');
        }

        $isDirty = false;

        if (!empty($user->roles)) {
            foreach ($user->roles as $role) {
                if ($role->name === $toAddRole->name) {
                    $user->roles()->detach($role->id);
                    $isDirty = true;
                }
            }
        }

        if (!$isDirty) {
            $user->roles()->attach($user->id, [
                'user_id' => $user->id,
                'role_id' => $toAddRole->id,
            ]);
        }

        return $this->success([
            'message' => $isDirty
                ? 'Role is removed from the User'
                : 'Role is added to the User',
        ]);
    }

    public function chekcAuth()
    {
        return $this->success([
            'status' => Auth::check(),
        ]);
    }

    public function show(Request $request, string $slug)
    {
        if ($slug === 'auth') {
            /**
             * @var User $user
             */
            $user = Auth::user();

            $user->isBelowLimit = count($user->teams()->get()) < Team::TEAM_LIMIT;
        } else {
            $user = User::where('slug', $slug)
                ->first();
        }

        if (!$user) {
            $this->failedAsNotFound('User');
        }

        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(string $id)
    {
        /**
         * @var User $auth
         */
        $auth = Auth::user();

        $user = User::with([])->where('id', $id)->first();

        if (!$user || !$auth || $auth->cant('delete', User::class)) {
            $this->failedAsNotFound('User');
        }

        $status = $user->delete();

        if (!$status) {
            $this->failure([
                'message' => 'Failed to delete the User',
            ]);
        }

        return $this->success([
            'message' => 'User is deleted',
        ], 200);
    }

    public function getUserTeams(string $slug)
    {
        if ($slug === 'auth') {
            /**
             * @var User $user
             */
            $user = Auth::user();
        } else {
            /**
             * @var User $user
             */
            $user = User::where('slug', $slug)->first();
        }

        if (!$user) {
            $this->failedAsNotFound('User');
        }

        $teams =  $user->teams()
            ->wherePivot('is_pending', Team::MEMBER_ACTIVE)
            ->orderByPivot('created_at')
            ->get();

        if (!$teams) {
            $this->failedAsNotFound('Team');
        }

        return new TeamCollection($teams);
    }
}
