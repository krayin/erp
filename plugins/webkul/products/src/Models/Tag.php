<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Product\Database\Factories\TagFactory;
use Webkul\Security\Models\User;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_tags';

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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }
}
