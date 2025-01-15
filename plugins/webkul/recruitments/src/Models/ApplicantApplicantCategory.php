<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantApplicantCategory extends Model
{
    protected $table = 'recruitments_applicant_applicant_categories';

    protected $fillable = ['applicant_id', 'applicant_category_id'];

    public $timestamps = false;
}
