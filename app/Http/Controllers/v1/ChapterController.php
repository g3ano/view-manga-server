<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Chapter\StoreChapterRequest;
use App\Http\Resources\v1\ChapterCollection;
use App\Http\Resources\v1\ChapterResource;
use App\Models\v1\Chapter;
use App\Models\v1\Manga;
use App\Models\v1\Page;
use App\Models\v1\Team;
use App\Models\v1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

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
        /**
         * @var Team $team
         */
        $team = Team::where('id', $data['team_id'])->first();

        if (!$manga || !$team) {
            $this->failedAsNotFound('Chapter');
        }
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var Chapter $chapter
         */
        $chapter = Chapter::where([
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
                    'number' => 'تعذر إضافة الفصل',
                ]);
            }
        }

        if (
            is_null($chapter->title) && !is_null($data['title'])
        ) {
            $chapter->title($data['title']);
            $chapter->save();
        }

        [
            'allowed' => $allowed,
            'message' => $message,
            'code' => $code,
        ] = Gate::inspect('create', [$chapter, $team->id])->toArray();

        if (!$allowed) {
            if ($code === 302) {
                $this->failure([
                    'number' => $message,
                ]);
            }
            $this->failedAsNotFound('Chapter');
        }

        $tempDir = 'temp/' . $manga->slug . '/' . $data['number'] . '/';
        $publicDir = 'public/mangas/' . $manga->slug . '/' . $data['number'] . '/';
        $pages = $request->file('pages');
        $fileOriginalName = substr($pages->getClientOriginalName(), 0, strpos(
            $pages->getClientOriginalName(),
            '.'
        ));

        $path = Storage::put(
            $tempDir,
            $pages,
            'private'
        );

        $zip = new ZipArchive();
        $isOpened = $zip->open(storage_path('app/' . $path));

        if (!$isOpened) {
            $this->failure([
                'pages' => 'حطأ في التعامل مع الملف، يرجى المحاولة لاحقا'
            ]);
        }

        //TODO: need a way to deal with this, by default is naming the folder
        //at which the contents is extracted using the file original name
        //which is unsecure
        $isExtracted = $zip->extractTo(storage_path('app/' . $tempDir));
        $isClosed = $zip->close();

        if (!$isExtracted || !$isClosed) {
            $this->failure([
                'pages' => 'حطأ في التعامل مع الملف، يرجى المحاولة لاحقا'
            ]);
        }

        $files = Storage::files($tempDir . $fileOriginalName);

        $pages = [];
        foreach ($files as $key => $fileName) {
            $pos = strrpos($fileName, '/');
            $path = $publicDir . substr($fileName, $pos + 1);
            Storage::move($fileName, $path);
            $pages[] = [
                'id' => $key + 1,
                'path' => Storage::url($path),
            ];
        }

        $page = Page::create([
            'data' => json_encode($pages),
        ]);
        $status = Storage::deleteDirectory($tempDir);

        if (!$status || !$page) {
            $this->failure([
                'status' => 'Failed to add the pages to the chapter',
            ]);
        }

        $team->chapters()->attach($chapter->id, [
            'page_id' => $page->id,
        ]);

        return $this->success([
            'status' => 'success',
        ]);
    }

    public function show(Request $request, string $slug, string $id)
    {

        return [
            'slug' => $slug,
            'id' => $id,
        ];
        /**
         * @var Chapter $chapter
         */
        $chapter = Chapter::where('id', $id)
            ->first();

        if (!$chapter) {
            $this->failedAsNotFound('Chapter');
        }

        return new ChapterResource($chapter);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function getTeamChapters(Request $request, string $slug)
    {
        $page = $request->query('page') ?? 1;
        $limit = $request->query('limit') ?? 25;

        /**
         * @var Team $team
         */
        $team = Team::where('slug', $slug)->first();
        $chapters = $team->chapters()->with('manga')
            ->orderByPivot('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        return new ChapterCollection($chapters);
    }
}
