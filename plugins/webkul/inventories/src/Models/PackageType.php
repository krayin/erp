<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Inventory\Database\Factories\PackageTypeFactory;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class PackageType extends Model
{
    use HasFactory, SortableTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'inventories_package_types';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sort',
        'barcode',
        'height',
        'width',
        'packaging_length',
        'base_weight',
        'max_weight',
        'shipper_package_code',
        'package_carrier_type',
        'company_id',
        'creator_id',
    ];

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): PackageTypeFactory
    {
        return PackageTypeFactory::new();
    }
}
