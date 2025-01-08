<?php

namespace Webkul\Warehouse\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Partner\Models\Address;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Warehouse\Database\Factories\WarehouseFactory;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Warehouse\Enums\DeliveryStep;
use Webkul\Warehouse\Enums\ReceptionStep;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes, SortableTrait;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'warehouses_warehouses';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'sort',
        'reception_steps',
        'delivery_steps',
        'partner_address_id',
        'company_id',
        'creator_id',
        'view_location_id',
        'lot_stock_location_id',
        'input_stock_location_id',
        'qc_stock_location_id',
        'output_stock_location_id',
        'pack_stock_location_id',
        'mto_pull_id',
        'pick_type_id',
        'pack_type_id',
        'out_type_id',
        'in_type_id',
        'internal_type_id',
        'qc_type_id',
        'store_type_id',
        'xdock_type_id',
        'crossdock_route_id',
        'reception_route_id',
        'delivery_route_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'reception_steps' => ReceptionStep::class,
        'delivery_steps'  => DeliveryStep::class,
    ];

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function partnerAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function viewLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'view_location_id');
    }

    public function lotStockLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'lot_stock_location_id');
    }

    public function inputStockLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'input_stock_location_id');
    }

    public function qcStockLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'qc_stock_location_id');
    }

    public function outputStockLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'output_stock_location_id');
    }

    public function packStockLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'pack_stock_location_id');
    }

    public function mtoPull(): BelongsTo
    {
        return $this->belongsTo(Rule::class, 'mto_pull_id');
    }

    public function pickType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'pick_type_id');
    }

    public function packType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'pack_type_id');
    }

    public function outType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'out_type_id');
    }

    public function inType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'in_type_id');
    }

    public function internalType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'internal_type_id');
    }

    public function qcType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'qc_type_id');
    }

    public function storeType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'store_type_id');
    }

    public function xdockType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class, 'xdock_type_id');
    }

    public function crossdockRoute(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'crossdock_route_id');
    }

    public function receptionRoute(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'reception_route_id');
    }

    public function deliveryRoute(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'delivery_route_id');
    }

    public function suppliedWarehouses(): BelongsToMany
    {
        return $this->belongsToMany(
            Warehouse::class,
            'warehouses_warehouse_resupplies',
            'supplier_warehouse_id',
            'supplied_warehouse_id'
        );
    }

    public function supplierWarehouses(): BelongsToMany
    {
        return $this->belongsToMany(
            Warehouse::class,
            'warehouses_warehouse_resupplies',
            'supplied_warehouse_id',
            'supplier_warehouse_id'
        );
    }

    protected static function newFactory(): WarehouseFactory
    {
        return WarehouseFactory::new();
    }
}
