<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Tag\StoreTagRequest;
use App\Http\Requests\v1\Tag\UpdateTagRequest;
use App\Http\Resources\v1\TagResource;
use App\Models\v1\Tag;
use App\Models\v1\User;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::orderBy('updated_at', 'desc')->get();

        if (empty($tags)) {
            $this->failedAsNotFound('Tag');
        }

        return $this->success([
            'tags' => TagResource::collection($tags)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || $user->cant('create', Tag::class)) {
            $this->failedAsNotFound('Tag');
        }

        $data = $request->validated();
        $tag = Tag::create($data);

        if (!$tag) {
            $this->failure([
                'message' => 'Failed to created new Tag',
            ]);
        }

        return $this->success([
            'message' => 'Tag is created',
            'tag' => new TagResource($tag)
        ]);
    }

    public function show(string $id)
    {
        $tag = Tag::where('id', $id)->first();

        if (!$tag) {
            $this->failedAsNotFound('Tag');
        }

        return $this->success([
            'tag' => new TagResource($tag),
        ]);
    }

    public function getMangaTags(string $slug)
    {
    }

    public function update(UpdateTagRequest $request, string $id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user || $user->cant('update', Tag::class)) {
            $this->failedAsNotFound('Tag');
        }

        $data = $request->validated();

        /**
         * @var Tag $tag 
         */
        $tag = Tag::where('id', $id)->first();

        if (!$tag) {
            $this->failedAsNotFound('Tag');
        }

        $status = $tag->update($data);

        if (!$status) {
            $this->failure([
                'message' => 'Enable to Update the Tag',
            ]);
        }

        return $this->success([
            'message' => 'Tag is updated',
            'tag' => new TagResource($tag),
        ]);
    }

    public function destroy(string $id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        /**
         * @var Tag $tag
         */
        $tag = Tag::where('id', $id)->first();

        if (!$user || $user->cant('delete', Tag::class) || !$tag) {
            $this->failedAsNotFound('Tag');
        }

        $status = $tag->delete();

        if (!$status) {
            $this->failure([
                'message' => 'Enable to Delete the Tag',
            ]);
        }

        return $this->success([
            'message' => 'Tag is Deleted',
        ]);
    }
}
