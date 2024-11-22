<?php

namespace Webkul\SavedFilters\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\User;

class SavedFilter extends Model
{
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'icon',
        'color',
        'is_favorite',
        'is_public',
        'filters',
        'user_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'filters' => 'array',
    ];

    /**
     * Get the user that owns the saved filter.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
