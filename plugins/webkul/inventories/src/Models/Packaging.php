<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Product\Models\Packaging as BasePackaging;

class Packaging extends BasePackaging
{
    /**
     * Create a new Eloquent model instance.
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->mergeFillable([
        ]);

        $this->mergeCasts([

        ]);
    }

    public function packageType(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }
}
