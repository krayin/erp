<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use phpDocumentor\Reflection\Types\Boolean;
use Webkul\Employee\Models\EmployeeJobPosition;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Stage extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    protected $table = 'recruitments_stages';

    protected $fillable = [
        'sort',
        'is_default',
        'creator_id',
        'name',
        'legend_blocked',
        'legend_done',
        'legend_normal',
        'requirements',
        'fold',
        'hired_stage',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'hired_stage' => 'boolean',
        'fold' => 'boolean',
    ];

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function jobs()
    {
        return $this->belongsToMany(EmployeeJobPosition::class, 'recruitments_stages_jobs', 'stage_id', 'job_id');
    }
}
