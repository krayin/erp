<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Inventory\Database\Factories\PackageFactory;
use Webkul\Inventory\Enums\PackageUse;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Package extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'inventories_packages';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'package_use',
        'pack_date',
        'package_type_id',
        'location_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'package_use' => PackageUse::class,
        'pack_date'   => 'date',
    ];

    public function packageType(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function quantities(): HasMany
    {
        return $this->hasMany(ProductQuantity::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): PackageFactory
    {
        return PackageFactory::new();
    }
}
