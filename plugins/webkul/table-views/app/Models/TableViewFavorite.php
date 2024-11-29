<?php

namespace Webkul\TableViews\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Support\Models\User;

class TableViewFavorite extends Model
{
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'is_favorite',
        'view_type',
        'view_key',
        'user_id',
    ];

    /**
     * Get the user that owns the saved filter.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
