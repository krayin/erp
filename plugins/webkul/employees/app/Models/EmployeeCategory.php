<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;

class EmployeeCategory extends Model
{
    use HasCustomFields, SoftDeletes;

    protected $fillable = ['name', 'color', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
