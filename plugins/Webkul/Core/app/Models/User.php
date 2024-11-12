<?php

namespace Webkul\Core\Models;

use App\Models\User as BaseUser;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends BaseUser implements FilamentUser
{
    use HasPanelShield, HasRoles, SoftDeletes;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The teams that belong to the user.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }
}
