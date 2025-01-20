<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Inventory\Database\Factories\MoveFactory;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Illuminate\Support\Facades\Auth;
use Webkul\Support\Models\UOM;
use Webkul\Inventory\Enums;

class Move extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'inventories_moves';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'state',
        'origin',
        'procure_method',
        'reference',
        'description_picking',
        'next_serial',
        'next_serial_count',
        'is_favorite',
        'product_qty',
        'product_uom_qty',
        'qty',
        'is_picked',
        'is_scraped',
        'is_inventory',
        'reservation_date',
        'scheduled_at',
        'product_id',
        'uom_id',
        'source_location_id',
        'destination_location_id',
        'final_location_id',
        'partner_id',
        'operation_id',
        'rule_id',
        'operation_type_id',
        'origin_returned_move_id',
        'restrict_partner_id',
        'warehouse_id',
        'product_packaging_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'state'            => Enums\MoveState::class,
        'is_favorite'      => 'boolean',
        'is_picked'        => 'boolean',
        'is_scraped'       => 'boolean',
        'is_inventory'     => 'boolean',
        'reservation_date' => 'date',
        'scheduled_at'     => 'datetime',
        'deadline'         => 'datetime',
        'alert_Date'       => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UOM::class);
    }

    public function sourceLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function finalLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    public function operationType(): BelongsTo
    {
        return $this->belongsTo(OperationType::class);
    }

    public function originReturnMove(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function restrictPartner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function packageLevel(): BelongsTo
    {
        return $this->belongsTo(PackageLevel::class);
    }

    public function productPackaging(): BelongsTo
    {
        return $this->belongsTo(Packaging::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(MoveLine::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($move) {
            $product = Product::find($move->product_id);
            
            $move->fill([
                'name' => $product->name,
                'procure_method' => Enums\ProcureMethod::MAKE_TO_STOCK,
                'state' => Enums\MoveState::DRAFT,
                'uom_id' => $product->uom_id,
                'product_uom_qty' => $move->product_qty,
            ]);

            // Get parent operation details if available
            if ($move->operation) {
                $move->fill([
                    'operation_type_id' => $move->operation->operation_type_id,
                    'source_location_id' => $move->operation->source_location_id,
                    'destination_location_id' => $move->operation->destination_location_id,
                    'scheduled_at' => $move->operation->scheduled_at ?? now(),
                    'reference' => $move->operation->name,
                ]);
            }
        });

        static::creating(function ($move) {
            // Fill default attributes during creation
            $product = Product::find($move->product_id);
            
            $move->fill([
                'name' => $product->name,
                'procure_method' => Enums\ProcureMethod::MAKE_TO_STOCK,
                'state' => Enums\MoveState::DRAFT,
                'uom_id' => $product->uom_id,
                'product_uom_qty' => $move->product_qty,
            ]);

            if ($move->operation) {
                $move->fill([
                    'operation_type_id' => $move->operation->operation_type_id,
                    'source_location_id' => $move->operation->source_location_id,
                    'destination_location_id' => $move->operation->destination_location_id,
                    'scheduled_at' => $move->operation->scheduled_at ?? now(),
                    'reference' => $move->operation->name,
                ]);
            }
        });

        static::saved(function ($move) {
            if ($move->qty > 0) {
                $move->createOrUpdateMoveLine();
            } else {
                $move->lines()->delete();
            }
        });
    }

    public function createOrUpdateMoveLine()
    {
        $this->lines()->updateOrCreate(
            ['move_id' => $this->id],
            [
                'lot_name' => null,
                'state' => $this->state,
                'reference' => $this->reference,
                'picking_description' => $this->description_picking,
                'quantity' => $this->qty,
                'quantity_product_uom' => $this->product_uom_qty,
                'is_picked' => $this->is_picked,
                'scheduled_at' => $this->scheduled_at,
                'operation_id' => $this->operation_id,
                'product_id' => $this->product_id,
                'uom_id' => $this->uom_id,
                'source_location_id' => $this->source_location_id,
                'destination_location_id' => $this->destination_location_id,
                'company_id' => $this->company_id,
                'creator_id' => $this->creator_id,
            ]
        );
    }

    protected static function newFactory(): MoveFactory
    {
        return MoveFactory::new();
    }
}
