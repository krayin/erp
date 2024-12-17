<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Project\Database\Factories\TaskStageFactory;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class TaskStage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'projects_task_stages';

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
        'project_id',
        'company_id',
        'user_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'is_active'    => 'boolean',
        'is_collapsed' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function newFactory(): TaskStageFactory
    {
        return TaskStageFactory::new();
    }
}
