<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUser extends Pivot
{
    public $increment = true;

    protected $casts = [
        'is_leader' => 'boolean',
        'is_pending' => 'integer',
    ];
}
