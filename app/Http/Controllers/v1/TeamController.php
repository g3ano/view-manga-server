<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Team\StoreTeamRequest;
use App\Http\Requests\v1\Team\UpdateTeamRequest;
use App\Http\Resources\v1\MangaCollection;
use App\Http\Resources\v1\TeamCollection;
use App\Http\Resources\v1\TeamResource;
use App\Http\Resources\v1\UserCollection;
use App\Models\v1\Manga;
use App\Models\v1\Team;
use App\Models\v1\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    protected $relationships = [
        'members',
        'mangas',
    ];

    public function index(Request $request)
    {
        $page = $request->query('page') ?? 1;

        $teams = Team::orderBy('updated_at', 'desc')
            ->paginate(25, ['*'], 'page', $page);

        return new TeamCollection($teams);
    }

    public function store(StoreTeamRequest $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user) {
            $this->failure([
                'message' => __('user.unauthenticated'),
            ]);
        }

        if ($user->cant('create', Team::class)) {
            $this->failure([
                'message' => __('team.limit'),
            ]);
        }
        $data = $request->validated();

        $data['email'] = $user->email;
        $data['slug'] = Str::slug($data['name']);

        /**
         * @var Team $team
         */
        $team = Team::create($data);

        if (!$team) {
            $this->failure([
                'message' => __('team.failed.create'),
            ]);
        }

        $team->members()->attach($user->id, [
            'is_leader' => 1,
            'is_pending' => Team::MEMBER_ACTIVE,
        ]);

        return $this->success([
            'status' => 'success',
        ], 201);
    }

    public function show(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var Team $team
         */
        $team = Team::where('slug', $slug)
            ->first();

        if (!$team) {
            $this->success(['team' => [], ]);
        }
        if ($user) {
            foreach ($team->members as $teamMember) {
                if ($teamMember->slug === $user->slug) {
                    if (
                        $teamMember->pivot->is_pending !== Team::MEMBER_REFUSED && $teamMember->pivot->is_pending !== Team::MEMBER_PENDING
                    ) {
                        $team->isMember = true;

                        if ($teamMember->pivot->is_leader) {
                            $team->isLeader = true;
                        }
                    }
                    if ($teamMember->pivot->is_pending === Team::MEMBER_REFUSED) {
                        $team->isRefused = true;
                    }
                    if ($teamMember->pivot->is_pending === Team::MEMBER_PENDING) {
                        $team->isPending = true;
                    }
                }
            }
        }

        return new TeamResource($team->unsetRelation('members'));
    }

    public function update(UpdateTeamRequest $request, string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var Team $team
         */
        $team = Team::where('slug', $slug)->first();

        if (!$team) {
            $this->success([
                'team' => [],
            ]);
        }

        if ($user->cannot('update', $team)) {
            $this->failedAsNotFound('Team');
        }

        $data = $request->validated();
        $data['email'] = Auth::user()->email;
        $data['members'][] = Auth::user()->id;

        $status = $team->update($data);

        if (!$status) {
            $this->failure([
                'message' => __('team.failed.update'),
            ]);
        }

        $oldMembers = $team->members->pluck('id')->toArray();

        $toDelete = array_diff($oldMembers, $data['members']);
        $toAdd = array_diff($data['members'], $oldMembers);

        if (!empty($toDelete)) {
            $team->members()->detach($toDelete);
        }

        if (!empty($toAdd)) {
            foreach ($toAdd as $member) {
                $team->members()->attach($team->id, [
                    'team_id' => $team->id,
                    'user_id' => $member,
                    'is_leader' => $member === Auth::user()->id ? 1 : 0
                ]);
            }
        }

        return $this->success([
            'status' => 'success',
        ]);
    }

    public function destroy(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($user->cannot('delete', Team::class)) {
            $this->failedAsNotFound('Team');
        }

        /**
         * @var Team $team
         */
        $team = Team::where('slug', $slug)->first();

        if (!$team) {
            $this->success([
                'team' => [],
            ]);
        }

        $status = $team->delete();

        if (!$status) {
            $this->failure([
                'message' => __('team.failed.delete'),
            ]);
        }

        return $this->success([
            'status' => 'success',
        ]);
    }

    public function joinTeamRequest(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        /**
         * @var Team $team
         */
        $team = Team::where('slug', $slug)->first();
        if (!$team || !$user) {
            $this->success([
                'team' => [],
            ]);
        }

        $isExist = $team->members()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('is_pending', Team::MEMBER_PENDING)
            ->first();
        if ($isExist) {
            $this->failure([
                'message' => __('team.member_exists'),
            ]);
        }

        $team->members()->attach(
            $user->id,
        );

        return $this->success([
            'status' => 'success',
        ]);
    }

    public function acceptJoinRequest(Request $request, string $slug)
    {
        [
            'pendingMemberSlug' => $pendingMemberSlug,
        ] = $request->validate([
            'pendingMemberSlug' => ['required', Rule::exists('users', 'slug')],
        ]);

        /**
         * @var User $auth
         */
        $auth = Auth::user();

        /**
         * @var Team $team
         */
        $team = Team::with('members')->where('slug', $slug)->first();

        if (!$team || !$auth || $auth->cant('handlePendingMembers', $team)) {
            $this->failedAsNotFound('Team');
        }

        foreach ($team->members as $teamMember) {
            if (
                $teamMember->slug === $pendingMemberSlug &&
                $teamMember->pivot->is_pending === Team::MEMBER_PENDING
            ) {
                $isUpdated = $team->members()->updateExistingPivot($teamMember->id, [
                    'is_pending' => Team::MEMBER_ACTIVE,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if (!$isUpdated) {
            $this->failure([
                'status' => 'failed',
                'message' => __('team.failed.accept_join'),
            ]);
        }

        return $this->success([
            'status' => 'succes',
        ]);
    }

    public function refuseJoinRequest(Request $request, string $slug)
    {
        [
            'pendingMemberSlug' => $pendingMemberSlug,
        ] = $request->validate([
            'pendingMemberSlug' => ['required', Rule::exists('users', 'slug')],
        ]);

        /**
         * @var User $auth
         */
        $auth = Auth::user();

        /**
         * @var Team $team
         */
        $team = Team::with('members')->where('slug', $slug)->first();

        if (!$team || !$auth || $auth->cant('handlePendingMembers', $team)) {
            $this->failedAsNotFound('Team');
        }

        foreach ($team->members as $teamMember) {
            if (
                $teamMember->slug === $pendingMemberSlug &&
                $teamMember->pivot->is_pending === Team::MEMBER_PENDING
            ) {
                $isUpdated = $team->members()->updateExistingPivot($teamMember->id, [
                    'is_pending' => Team::MEMBER_REFUSED,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if (!$isUpdated) {
            $this->failure([
                'status' => 'failed',
                'message' => __('team.failed.refuse_join'),
            ]);
        }

        return $this->success([
            'status' => 'succes',
        ]);
    }

    public function getTeamMangas(Request $request, string $slug)
    {
        $page = $request->query('page') ?? 1;
        $limit = $request->query('limit') ?? 25;

        $team = Team::where('slug', $slug)->first();
        $mangas = Manga::where([
            ['team_id', '=', $team->id],
            ['is_approved', '=', 1]
        ])
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        return new MangaCollection($mangas);
    }

    public function getTeamUnapprovedMangas(Request $request, string $slug)
    {
        /**
         * @var Team $team
         */
        $team = Team::where('slug', $slug)->first();

        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user || $user->cant('viewUnapprovedTeam', [
            Manga::class, $team->id,
        ])) {
            $this->failedAsNotFound('shit');
        }

        $mangas = $team->mangas()->where('is_approved', 0)->get();

        if (!$mangas) {
            $this->success([
                'mangas' => [],
            ]);
        }

        return new MangaCollection($mangas);
    }

    public function getTeamMembers(string $slug)
    {
        /**
         * @var Team $team
         */
        $team = Team::with('members')
            ->where('slug', $slug)
            ->first();

        $members = $team->members()
            ->wherePivot('is_pending', Team::MEMBER_ACTIVE)
            ->orderByPivot('is_leader', 'desc')
            ->orderByPivot('created_at')
            ->get();

        return new UserCollection($members);
    }

    public function getTeamPendingMembers(string $slug)
    {
        /**
         * @var Team $team
         */
        $team = Team::with('members')
            ->where('slug', $slug)
            ->first();

        $members = $team->members()
            ->wherePivot('is_pending', Team::MEMBER_PENDING)
            ->orderByPivot('created_at')
            ->get();

        return new UserCollection($members);
    }
}
