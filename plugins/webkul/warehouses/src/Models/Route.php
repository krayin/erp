<?php

namespace Webkul\Warehouse\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Warehouse\Database\Factories\RouteFactory;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'warehouses_routes';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'sort',
        'name',
        'product_selectable',
        'product_category_selectable',
        'warehouse_selectable',
        'packaging_selectable',
        'supplied_warehouse_id',
        'supplier_warehouse_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'product_selectable'          => 'boolean',
        'product_category_selectable' => 'boolean',
        'warehouse_selectable'        => 'boolean',
        'packaging_selectable'        => 'boolean',
    ];

    public function suppliedWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function supplierWarehouse(): BelongsTo
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

    protected static function newFactory(): RouteFactory
    {
        return RouteFactory::new();
    }
}
