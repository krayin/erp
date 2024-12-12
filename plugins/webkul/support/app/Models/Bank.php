<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Support\Database\Factories\BankFactory;
use Webkul\Security\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory, SoftDeletes;

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
