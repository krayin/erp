<?php

namespace Webkul\Security\Models;

use App\Models\User as BaseUser;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Webkul\Support\Models\Company;

class User extends BaseUser implements FilamentUser
{
    use HasRoles, SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'default_company_id' => 'integer',
    ];

    /**
     * Determine if the user can access the Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * The teams that belong to the user.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'user_team', 'user_id', 'team_id');
    }

    /**
     * The companies that the user owns.
     */
    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    /**
     * The companies that the user is allowed to access.
     */
    public function allowedCompanies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'user_allowed_companies', 'user_id', 'company_id');
    }

    /**
     * The user's default company.
     */
    public function defaultCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'default_company_id');
    }
}
