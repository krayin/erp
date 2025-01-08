<?php

namespace Webkul\Warehouse\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Partner\Models\Address;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Warehouse\Database\Factories\RuleFactory;

class Rule extends Model
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
        'route_sequence',
        'delay',
        'group_propagation_option',
        'action',
        'procure_method',
        'auto',
        'push_domain',
        'location_dest_from_rule',
        'propagate_cancel',
        'propagate_carrier',
        'source_location_id',
        'destination_location_id',
        'route_id',
        'picking_type_id',
        'partner_address_id',
        'warehouse_id',
        'propagate_warehouse_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'location_dest_from_rule' => 'boolean',
        'propagate_cancel'        => 'boolean',
        'propagate_carrier'       => 'boolean',
    ];

    public function sourceLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function pickingType(): BelongsTo
    {
        return $this->belongsTo(PickingType::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function propagateWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function partnerAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): RuleFactory
    {
        return RuleFactory::new();
    }
}
