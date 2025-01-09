<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCategory extends Model
{
    protected $table = 'recruitments_applicant_categories';

    protected $fillable = ['name', 'color', 'creator_id'];
}
