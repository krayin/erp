<?php

namespace Webkul\Core\Models;

use App\Models\User as BaseUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;

class User extends BaseUser implements FilamentUser
{
    use HasRoles, HasPanelShield, SoftDeletes;
}
