<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Partner\Models\Partner;
use Webkul\Project\Database\Factories\ProjectFactory;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'projects_projects';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tasks_label',
        'description',
        'visibility',
        'color',
        'tags',
        'sort',
        'start_date',
        'end_date',
        'allocated_hours',
        'allow_timesheets',
        'allow_milestones',
        'allow_task_dependencies',
        'is_active',
        'stage_id',
        'partner_id',
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
        'start_date'              => 'date',
        'end_date'                => 'date',
        'is_active'               => 'boolean',
        'allow_timesheets'        => 'boolean',
        'allow_milestones'        => 'boolean',
        'start_date'              => 'date',
        'end_date'                => 'date',
        'tags'                    => 'array',
        'is_active'               => 'boolean',
        'allow_timesheets'        => 'boolean',
        'allow_milestones'        => 'boolean',
        'allow_task_dependencies' => 'boolean',
    ];

    /**
     * Get the user's first name.
     */
    protected function plannedDate(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['start_date'].' - '.$attributes['end_date'],
        );
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(ProjectStage::class);
    }

    public function taskStages(): HasMany
    {
        return $this->hasMany(TaskStage::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'projects_project_tag', 'project_id', 'tag_id');
    }

    protected static function newFactory(): ProjectFactory
    {
        return ProjectFactory::new();
    }
}
