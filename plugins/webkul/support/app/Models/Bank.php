<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'street2',
        'city',
        'state',
        'country',
        'zip',
        'user_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
