<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Project\Database\Factories\TaskFactory;
use Webkul\Security\Models\User;
use Webkul\Security\Models\Company;
use Webkul\Partner\Models\Partner;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'projects_tasks';

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'color',
        'priority',
        'state',
        'tags',
        'sort',
        'is_active',
        'is_recurring',
        'deadline',
        'working_hours_open',
        'working_hours_close',
        'allocated_hours',
        'remaining_hours',
        'effective_hours',
        'total_hours_spent',
        'overtime',
        'progress',
        'stage_id',
        'project_id',
        'partner_id',
        'parent_id',
        'company_id',
        'creator_id',
    ];

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'tags' => 'array',
        'deadline' => 'datetime',
        'priority' => 'boolean',
        'is_active' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function subTasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(TaskStage::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'projects_task_users');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'projects_task_tag', 'task_id', 'tag_id');
    }

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
