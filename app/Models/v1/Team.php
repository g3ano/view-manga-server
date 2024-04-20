<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    public const TEAM_LIMIT = 5;

    //TODO: I probably have used an enum
    public const MEMBER_ACTIVE = 1;
    public const MEMBER_PENDING = 2;
    public const MEMBER_REFUSED = 3;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'email',
        'facebook',
        'website',
        'twitter',
        'discord'
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot([
                'is_leader',
                'is_pending',
            ]);
    }

    public function mangas(): HasMany
    {
        return $this->hasMany(Manga::class);
    }

    public function chapters(): BelongsToMany
    {
        return $this->belongsToMany(Chapter::class)
            ->withTimestamps()
            ->withPivot([
                'page_id'
            ]);
    }
}
