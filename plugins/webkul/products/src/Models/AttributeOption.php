<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Database\Factories\AttributeOptionFactory;
use Webkul\Security\Models\User;
use Spatie\EloquentSortable\SortableTrait;

class AttributeOption extends Model
{
    use HasFactory, SortableTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_attribute_options';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
        'extra_price',
        'sort',
        'attribute_id',
        'creator_id',
    ];

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): AttributeOptionFactory
    {
        return AttributeOptionFactory::new();
    }
}
