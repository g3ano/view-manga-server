<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'manga_id',
        'number',
        'title',
    ];

    protected $casts = [
        'number' => 'float',
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withTimestamps()
            ->withPivot([
                'page_id'
            ]);
    }
}
