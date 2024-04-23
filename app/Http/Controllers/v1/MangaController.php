<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Manga\StoreMangaRequest;
use App\Http\Requests\v1\Manga\UpdateMangaRequest;
use App\Http\Resources\v1\ChapterCollection;
use App\Http\Resources\v1\MangaCollection;
use App\Http\Resources\v1\MangaResource;
use App\Http\Resources\v1\TeamResource;
use App\Models\v1\Chapter;
use App\Models\v1\Manga;
use App\Models\v1\Role;
use App\Models\v1\Team;
use App\Models\v1\User;
use App\Notifications\v1\UnapprovedMangaNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MangaController extends Controller
{
    protected $relationships = [
        'tags',
        'team',
    ];

    public function index(Request $request)
    {
        $page = $request->query('page') ?? 1;
        $limit = $request->query('limit') ?? 25;
        $includes = $this->includeRelationship($request);

        $mangas = Manga::with($includes)
            ->where('is_approved', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        if ($mangas->isEmpty()) {
            $this->failedAsNotFound('Manga');
        }

        return new MangaCollection($mangas);
    }

    public function store(StoreMangaRequest $request)
    {
        /**
         * @var User $user
         **/
        $user = Auth::user();

        if (!$user || $user->cant('create', Manga::class)) {
            $this->failedAsNotFound('Manga');
        }

        $data = $request->validated();
        $data['translation_status'] = 'ongoing';
        $data['slug'] = Str::slug($data['title']);

        $imagePath = $request->file('cover')->store('manga_covers', 'public');

        if (!$imagePath) {
            $this->failure([
                'message' => 'Couldn\'t store the image',
            ]);
        }

        $data['cover'] = $imagePath;

        /**
         * @var Manga $manga
         */
        $manga = Manga::create($data);

        if (!$manga) {
            $this->failure([
                'message' => 'Failed to created the manga',
            ]);
        }

        if ($data['tags']) {
            foreach ($data['tags'] as $tagId) {
                $manga->tags()->attach($tagId);
            }
        }

        $notifiedUsers = User::with(['roles'])
            ->whereHas('roles', function (Builder $query) {
                $query->whereIn('roles.id', [
                    Role::ADMIN, Role::MODERATOR
                ]);
            })
            ->get();

        Notification::send($notifiedUsers, new UnapprovedMangaNotification($manga));

        return $this->success([
            'message' => 'Manga is created',
        ]);
    }

    public function show(Request $request, string $slug)
    {
        $includes = $this->includeRelationship($request);

        $manga = Manga::with($includes)
            ->where('is_approved', 1)
            ->where('slug', $slug)
            ->first();

        if (!$manga) {
            $this->failedAsNotFound('Manga');
        }

        return new MangaResource($manga);
    }

    public function update(UpdateMangaRequest $request, string $id)
    {
        //
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

        $manga = Manga::where('is_approved', 1)
            ->where('slug', $slug)
            ->first();

        if (!$manga) {
            $this->failedAsNotFound('Manga');
        }

        $status = $manga->delete();

        if (!$status) {
            $this->failure([
                'message' => 'Failed to delete the Manga',
            ]);
        }

        return $this->success([
            'message' => 'Manga is deleted',
        ], 200);
    }

    public function getMangaTeam(string $slug)
    {
        $team = Team::whereHas('mangas', function (Builder $query) use ($slug) {
            $query->where([
                ['slug', '=', $slug],
                ['is_approved', '=', 1],
            ]);
        })
            ->first();

        if (!$team) {
            $this->failedAsNotFound('Team');
        }

        return new TeamResource($team);
    }

    public function getMangaChapters(Request $request, string $slug)
    {
        $page = $request->query('page') ?? 1;
        $limit = $request->query('limit') ?? 25;

        $manga = Manga::where('slug', $slug)->first();
        $chapters = Chapter::with('teams', 'manga')->where('manga_id', $manga->id)
            ->orderBy('number', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        $chapters = $chapters->map(function ($model, $key) {
            /**
             * @var Chapter $chapter
             */
            $chapter = $model;
            $chapter->teams = $chapter->teams()->orderByPivot('created_at', 'desc')->get();

            return $chapter;
        })->all();

        return new ChapterCollection($chapters);
    }

    public function latet()
    {
    }
}
