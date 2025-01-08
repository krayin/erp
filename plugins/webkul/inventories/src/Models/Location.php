<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Inventory\Database\Factories\LocationFactory;
use Webkul\Inventory\Enums\LocationType;

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

    protected static function newFactory(): LocationFactory
    {
        return LocationFactory::new();
    }
}
