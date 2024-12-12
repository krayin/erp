<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Fields\Traits\HasCustomFields;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class EmploymentType extends Model
{
    use HasCustomFields;

    protected $fillable = [
        'name',
        'company_id',
        'user_id',
        'code',
        'sequence',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
