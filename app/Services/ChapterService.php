<?php

namespace App\Services;

use App\Models\v1\Manga;
use App\Models\v1\Team;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use RuntimeException;
use ZipArchive;

class ChapterService
{
    use HttpResponse;

    protected ?Manga $manga = null;
    protected ?Team $team = null;
    protected ?array $data = null;
    protected ?int $width = 768;

    public function __construct(
        ?Manga $manga = null,
        ?Team $team = null,
        ?array $data = null,
    ) {
        $this->manga = $manga;
        $this->team = $team;
        $this->data = $data;
    }

    /**
     * Set a with for the images to scale, without exceeding
     * its original width
     * default to 768
     *
     */
    public function setImageWidth(int $width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Proccess and store chapter pages
     *
     * @throws HttpResponseException
     */
    public function uploadChapterPages()
    {
        $publicDir = 'mangas/' . $this->manga->slug . '/' . $this->team->id . '/' . $this->data['number'];

        try {
            $zip = new ZipArchive();
            $zip->open(storage_path('app/' . $this->data['pages']));

            if ($zip->numFiles <= 1) {
                throw new RuntimeException(__('chapter.pages.empty'), 422);
            }

            $pages = [];
            $noNames = [];

            for ($i = 0; $i < $zip->numFiles - 1; $i++) {
                try {
                    $zipFileName = $zip->getNameIndex($i + 1);
                    [
                        'filename' => $fileName,
                    ] = pathinfo($zipFileName);

                    if (!is_numeric($fileName)) {
                        $noNames[] = $i + 1;
                        continue;
                    }

                    /**
                     * This is a workaround for getStreamIndex getting
                     * undefined error by Intelephense
                     *
                     * @var mixed $zip
                     */
                    $resource = $zip->getStreamName($zipFileName);

                    if (!Storage::disk('public')->exists($publicDir)) {
                        $status = Storage::disk('public')->makeDirectory($publicDir);

                        if (!$status) {
                            throw new RuntimeException(
                                __('chapter.failed.general'),
                                422
                            );
                        }
                    }

                    if (!$resource) {
                        throw new RuntimeException(
                            __('chapter.failed'),
                            422
                        );
                    }

                    $manager = new  ImageManager(new Driver());
                    $image = $manager->read($resource)
                        ->scaleDown(width: $this->width)
                        ->toWebp(100);
                    $destinationPath = $publicDir . '/' . $fileName . '.webp';
                    $image->save(storage_path('app/public/' . $destinationPath));

                    $pages[] = [
                        'id' => $i + 1,
                        'path' => Storage::url($destinationPath)
                    ];
                } catch (\Throwable $e) {
                    $key = $e->getCode() === 422 ? 'pages' : 'message';
                    $this->failure([
                        $key => $e->getMessage(),
                    ], $e->getCode());
                }
            }
        } catch (\Throwable $e) {
            $key = $e->getCode() === 422 ? 'pages' : 'message';
            $this->failure([
                $key => $e->getMessage(),
            ], $e->getCode());
        } finally {
            $zip->close();
            if (Storage::exists($this->data['pages'])) {
                Storage::delete($this->data['pages']);
            }
        }

        return $pages;
    }
}
