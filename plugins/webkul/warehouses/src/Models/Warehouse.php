<?php

namespace Webkul\Warehouse\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Warehouse\Database\Factories\WarehouseFactory;

class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

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
        'parent_path',
        'company_id',
        'partner_id',
        'creator_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): WarehouseFactory
    {
        return WarehouseFactory::new();
    }
}
