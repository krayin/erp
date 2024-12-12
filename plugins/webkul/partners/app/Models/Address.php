<?php

namespace Webkul\Partner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Partner\Database\Factories\AddressFactory;
use Webkul\Partner\Enums\AccountType;
use Webkul\Support\Models\Country;
use Webkul\Support\Models\State;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;

class Address extends Model
{
    use HasFactory;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'partners_addresses';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'street1',
        'street2',
        'city',
        'zip',
        'state_id',
        'country_id',
        'creator_id',
        'partner_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'type' => AccountType::class,
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): AddressFactory
    {
        return AddressFactory::new();
    }
}
