<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Product\Database\Factories\CategoryFactory;
use Webkul\Security\Models\User;

class Category extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'products_categories';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'full_name',
        'parent_path',
        'parent_id',
        'creator_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            $category->updateFullName();
        });

        static::updated(function ($category) {
            if ($category->wasChanged('full_name')) {
                $category->updateChildrenFullNames();
            }
        });
    }

    protected function updateFullName(): void
    {
        if ($this->parent) {
            $this->full_name = $this->parent->full_name.' / '.$this->name;
        } else {
            $this->full_name = $this->name;
        }
    }

    protected function updateChildrenFullNames(): void
    {
        $this->children->each(function ($child) {
            $child->updateFullName();
            $child->save();

            $child->updateChildrenFullNames();
        });
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
