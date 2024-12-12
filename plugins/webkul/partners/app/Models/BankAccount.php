<?php

namespace Webkul\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Support\Models\Bank;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;

class BankAccount extends Model
{
    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'account_number',
        'account_holder_name',
        'is_active',
        'can_send_money',
        'creator_id',
        'partner_id',
        'bank_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'is_active' => 'boolean',
        'can_send_money' => 'boolean',
    ];

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
