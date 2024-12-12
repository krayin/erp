<?php

namespace Webkul\Project\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Project\Database\Factories\TaskStageFactory;
use Webkul\Security\Models\User;
use Webkul\Security\Models\Company;

class TaskStage extends Model
{
    use HasFactory;

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
        'is_active' => 'boolean',
        'is_collapsed' => 'boolean',
    ];

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
