<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Inventory\Database\Factories\LocationFactory;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'inventories_locations';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'position_x',
        'position_y',
        'position_z',
        'type',
        'name',
        'full_name',
        'description',
        'parent_path',
        'barcode',
        'cyclic_inventory_frequency',
        'last_inventory_date',
        'next_inventory_date',
        'is_scrap',
        'is_replenish',
        'is_dock',
        'parent_id',
        'storage_category_id',
        'warehouse_id',
        'company_id',
        'creator_id',
        'deleted_at',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'type'                => LocationType::class,
        'last_inventory_date' => 'date',
        'next_inventory_date' => 'date',
        'is_scrap'            => 'boolean',
        'is_replenish'        => 'boolean',
        'is_dock'             => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function storageCategory(): BelongsTo
    {
        return $this->belongsTo(StorageCategory::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bootstrap any application services.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function (self $location) {
            static::updateParentPath($location);

            static::updateFullName($location);
        });

        static::updated(function (self $location) {
            static::updateParentPath($location);

            static::updateFullName($location);
        });

        static::saving(function (self $location) {
            static::updateParentPath($location);

            static::updateFullName($location);
        });
    }

    /**
     * Update the parent path without triggering additional events
     */
    protected static function updateParentPath(self $location)
    {
        $parentPath = $location->parent
            ? $location->parent->parent_path.$location->id.'/'
            : $location->id.'/';

        // Use query builder to avoid triggering another update event
        static::withoutEvents(function () use ($location, $parentPath) {
            $location->newQuery()
                ->withTrashed()
                ->where('id', $location->id)
                ->update(['parent_path' => $parentPath]);
        });
    }

    /**
     * Update the full name without triggering additional events
     */
    protected static function updateFullName(self $location)
    {
        if ($location->type === LocationType::VIEW) {
            $fullName = $location->name;
        } else {
            $fullName = $location->parent
                ? $location->parent->full_name.'/'.$location->name
                : $location->name;
        }

        // Use query builder to avoid triggering another update event
        static::withoutEvents(function () use ($location, $fullName) {
            $location->newQuery()
                ->withTrashed()
                ->where('id', $location->id)
                ->update(['full_name'   => $fullName]);
        });
    }

    protected static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }
}
