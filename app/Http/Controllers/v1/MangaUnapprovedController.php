<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\MangaCollection;
use App\Http\Resources\v1\MangaResource;
use App\Http\Resources\v1\TeamResource;
use App\Models\v1\Manga;
use App\Models\v1\Team;
use App\Models\v1\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MangaUnapprovedController extends Controller
{
    protected $relationships = [
        'tags',
        'team',
    ];

    public function index(Request $request)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user || $user->cant('viewUnapprovedAny', Manga::class)) {
            $this->failedAsNotFound('Manga unauthorized');
        }

        $page = $request->query('page') ?? 1;
        $limit = $request->query('limit') ?? 25;

        $mangas = Manga::where('is_approved', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        if (!$mangas) {
            return $this->success([]);
        }

        return new MangaCollection($mangas);
    }

    public function update(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var Manga $manga
         */
        $manga = Manga::where([
            ['is_approved', '=', 0], ['slug', '=', $slug]
        ])
            ->first();

        if (!$manga) {
            return $this->success([]);
        }

        if (!$user || $user->cant('update', $manga)) {
            $this->failedAsNotFound('Manga');
        }
    }

    public function show(Request $request, string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $manga = Manga::where('is_approved', 0)
            ->where('slug', $slug)
            ->first();

        if (!$user || $user->cant('viewUnapproved', $manga) || !$manga) {
            $this->failedAsNotFound('Manga');
        }

        return new MangaResource($manga);
    }

    public function destroy(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user || $user->cant('delete', Manga::class)) {
            $this->failedAsNotFound('Manga');
        }

        $manga = Manga::where([
            ['is_approved', '=', 0], ['slug', '=', $slug]
        ])
            ->first();

        if (!$manga) {
            return $this->success([]);
        }

        $status = $manga->delete();

        if (!$status) {
            $this->failure([
                'message' => 'Failed to delete the Manga',
            ]);
        }

        return $this->success([
            'status' => 'success',
        ], 200);
    }

    public function getUnapprovedMangaTeam(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user || $user->cant('viewUnapprovedMangaTeam', Team::class)) {
            $this->failedAsNotFound('Team');
        }

        $manga = Manga::with('team')->where([
            ['slug', '=', $slug],
            ['is_approved', '=', 0],
        ])
            ->first();

        if (!$manga) {
            return $this->success([]);
        }

        return new TeamResource($manga->team);
    }

    public function approve(string $slug)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var Manga $manga
         */
        $manga = Manga::where([
            ['is_approved', '=', 0],
            ['slug', '=', $slug]
        ])
            ->first();

        if (!$manga) {
            return $this->success([]);
        }

        if (!$user || $user->cant('approve', $manga)) {
            $this->failedAsNotFound('Manga');
        }

        $manga->is_approved = 1;
        $manga->created_at = Carbon::now();
        $manga->updated_at = Carbon::now();
        $status = $manga->save();

        if (!$status) {
            $this->failure([
                'message' => 'Failed to approve the Manga',
            ]);
        }

        return $this->success([
            'status' => 'success',
        ]);
    }
}
