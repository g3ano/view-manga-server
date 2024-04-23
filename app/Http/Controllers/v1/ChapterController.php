<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Chapter\StoreChapterRequest;
use App\Http\Resources\v1\ChapterCollection;
use App\Http\Resources\v1\ChapterResource;
use App\Http\Resources\v1\MangaResource;
use App\Http\Resources\v1\PageResource;
use App\Jobs\v1\ProcessChapterPages;
use App\Models\v1\Chapter;
use App\Models\v1\Manga;
use App\Models\v1\Page;
use App\Models\v1\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChapterController extends Controller
{
    protected $relationships = [
        'manga',
        'pages',
    ];

    public function index(Request $request)
    {
        $includes = $this->includeRelationship($request);
        $limit = $request->query('limit') ?? 10;
        $page = $request->query('page') ?? 1;

        $chapters = Chapter::with($includes)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        return new ChapterCollection($chapters);
    }

    public function store(StoreChapterRequest $request)
    {
        $data = $request->validated();

        /**
         * @var Manga $manga
         */
        $manga = Manga::where('id', $data['manga_id'])->first();
        $result = DB::table('chapters')
            ->join('chapter_team', 'chapters.id', '=', 'chapter_team.chapter_id')
            ->where('manga_id', $manga->id)
            ->select(DB::raw('MAX(number) as last'))
            ->first();
        $last = (float) $result->last;

        if ($data['number'] > $last + 1) {
            $this->failure([
                'number' => __('chapter.number.max'),
            ]);
        }

        /**
         * @var Team $team
         */
        $team = Team::where('id', $data['team_id'])->first();

        if (!$manga || !$team) {
            $this->failedAsNotFound('Chapter');
        }

        /**
         * @var Chapter $chapter
         */
        $chapter = Chapter::with('manga')
            ->where([
                ['manga_id', '=', $manga->id],
                ['number', '=', $data['number']]
            ])->first();

        if (!$chapter) {
            /**
             * @var Chapter $chapter
             */
            $chapter = Chapter::create([
                'manga_id' => $manga->id,
                'number' => $data['number'],
                'title' => $data['title'],
            ]);

            if (!$chapter) {
                $this->failure([
                    'number' => __('chapter.failed.create'),
                ]);
            }
        }

        if (
            is_null($chapter->title) && !is_null($data['title'])
        ) {
            $chapter->title = $data['title'];
            $chapter->save();
        }

        [
            'allowed' => $allowed,
            'code' => $code,
        ] = Gate::inspect('create', [$chapter, $team->id])->toArray();

        if (!$allowed) {
            if ($code === 302) {
                $this->failure([
                    'number' => __('chapter.unique'),
                ]);
            }
            $this->failedAsNotFound('Chapter');
        }

        $tempDir = 'temp/mangas/';
        $path = $request->file('pages')->store($tempDir, 'local');
        $data['pages'] = $path;

        ProcessChapterPages::dispatch($chapter, $data);

        return $this->success([
            'status' => 'success',
        ]);
    }

    public function show(Request $request, string $mangaSlug, string $teamSlug, string $id)
    {
        $manga = Manga::where('slug', $mangaSlug)->first();
        $team = Team::where('slug', $teamSlug)->first();

        if (!$manga || !$team) {
            $this->success([
                'status' => 'failed',
            ]);
        }
        /**
         * @var Chapter $chapter
         */
        $chapter = Chapter::with('teams')
            ->where([
                ['id', '=', $id],
                ['manga_id', '=', $manga->id],
            ])
            ->first();

        if (!$chapter) {
            $this->success([]);
        }

        $pageId = $chapter->teams()
            ->wherePivot('team_id', $team->id)
            ->first(['page_id'])->page_id;
        $pages = Page::where('id', $pageId)->first();

        if (!$pages) {
            $this->success([
                'status' => 'failed',
            ]);
        }

        return $this->success([
            'chapter' => new ChapterResource($chapter->unsetRelation('teams')),
            'manga' => new MangaResource($manga),
            'pages' => new PageResource($pages),
        ]);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
