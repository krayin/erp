<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Project\Database\Factories\ProjectStageFactory;
use Webkul\Security\Models\User;
use Webkul\Security\Models\Company;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectStage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'projects_project_stages';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'is_collapsed',
        'sort',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_collapsed' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function newFactory(): ProjectStageFactory
    {
        return ProjectStageFactory::new();
    }
}
