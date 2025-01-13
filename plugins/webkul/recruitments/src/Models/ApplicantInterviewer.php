<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantInterviewer extends Model
{
    protected $table = 'recruitments_applicant_interviewers';

    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'interviewer_id',
    ];
}
