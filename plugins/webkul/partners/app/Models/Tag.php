<?php

namespace Webkul\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Partner\Database\Factories\TagFactory;
use Webkul\Security\Models\User;

class Tag extends Model
{
    use HasFactory;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'creator_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }
}
