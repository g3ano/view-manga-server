<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(Manga::class)
            ->withTimestamps();
    }
}
