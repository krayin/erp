<?php

namespace Webkul\SavedFilters\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'filters' => 'array',
    ];
}
