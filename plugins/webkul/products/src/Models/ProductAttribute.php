<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

class ProductAttribute extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_product_attributes';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'product_id',
        'attribute_id',
        'creator_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
