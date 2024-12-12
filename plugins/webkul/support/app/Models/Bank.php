<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;
use Webkul\Support\Database\Factories\BankFactory;

class Bank extends Model
{
    use HasFactory;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'street2',
        'city',
        'zip',
        'state_id',
        'country_id',
        'creator_id',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory(): BankFactory
    {
        return BankFactory::new();
    }
}
