<?php

namespace App\Jobs\v1;

use App\Models\v1\Chapter;
use App\Models\v1\Page;
use App\Models\v1\Team;
use App\Services\ChapterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;

class ProcessChapterPages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deleteWhenMissingModels = true;
    public Chapter $chapter;
    public array $data;

    public function __construct(Chapter $chapter, array $data)
    {
        $this->chapter = $chapter;
        $this->data = $data;
    }

    public function handle(ChapterService $chapterService): void
    {
        /**
         * @var Team $team
         */
        $team = Team::where('id', $this->data['team_id'])->first();

        $chapterService = new ChapterService(
            $this->chapter->manga,
            $team,
            $this->data
        );
        $pagesImages =  $chapterService
            ->setImageWidth(768)
            ->uploadChapterPages();

        $pages = Page::create([
            'data' => json_encode($pagesImages),
        ]);

        if (!$pages) {
            throw new RuntimeException(__('chapter.pages.create'));
        }

        $team->chapters()->attach($this->chapter->id, [
            'page_id' => $pages->id,
        ]);
    }
}
