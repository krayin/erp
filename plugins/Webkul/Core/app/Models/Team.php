<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The users that belong to the team.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
