<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StageJob extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'recruitments_stages_jobs';

    protected $fillable = [
        'stage_id',
        'job_id',
    ];
}
