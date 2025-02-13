<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;

class UtmStage extends Model
{
    use HasFactory;

    protected $table = 'utm_stages';

    protected $fillable = [
        'sort',
        'name',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
