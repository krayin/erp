<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Inventory\Database\Factories\PickingTypeFactory;
use Webkul\Inventory\Enums\CreateBackorder;
use Webkul\Inventory\Enums\MoveType;
use Webkul\Inventory\Enums\PickingType as PickingTypeEnum;
use Webkul\Inventory\Enums\ReservationMethod;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class PickingType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'inventories_picking_types';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'sort',
        'sequence_code',
        'reservation_method',
        'reservation_days_before',
        'reservation_days_before_priority',
        'product_label_format',
        'lot_label_format',
        'package_label_to_print',
        'barcode',
        'create_backorder',
        'move_type',
        'show_entire_packs',
        'use_create_lots',
        'use_existing_lots',
        'print_label',
        'show_operations',
        'auto_show_reception_report',
        'auto_print_delivery_slip',
        'auto_print_return_slip',
        'auto_print_product_labels',
        'auto_print_lot_labels',
        'auto_print_reception_report',
        'auto_print_reception_report_labels',
        'auto_print_packages',
        'auto_print_package_label',
        'return_picking_type_id',
        'source_location_id',
        'destination_location_id',
        'warehouse_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'type'                               => PickingTypeEnum::class,
        'reservation_method'                 => ReservationMethod::class,
        'create_backorder'                   => CreateBackorder::class,
        'move_type'                          => MoveType::class,
        'show_entire_packs'                  => 'boolean',
        'use_create_lots'                    => 'boolean',
        'use_existing_lots'                  => 'boolean',
        'print_label'                        => 'boolean',
        'show_operations'                    => 'boolean',
        'auto_show_reception_report'         => 'boolean',
        'auto_print_delivery_slip'           => 'boolean',
        'auto_print_return_slip'             => 'boolean',
        'auto_print_product_labels'          => 'boolean',
        'auto_print_lot_labels'              => 'boolean',
        'auto_print_reception_report'        => 'boolean',
        'auto_print_reception_report_labels' => 'boolean',
        'auto_print_packages'                => 'boolean',
        'auto_print_package_label'           => 'boolean',
    ];

    public function returnPickingType(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function storageCategory(): BelongsTo
    {
        return $this->belongsTo(StorageCategory::class);
    }

    public function sourceLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
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

    protected static function newFactory(): PickingTypeFactory
    {
        return PickingTypeFactory::new();
    }
}
