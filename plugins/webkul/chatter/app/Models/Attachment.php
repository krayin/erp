<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $table = 'chatter_attachments';

    protected $fillable = [
        'company_id',
        'creator_id',
        'file_size',
        'name',
        'messageable',
        'file_path',
        'original_file_name',
        'mime_type',
    ];

    protected $appends = ['url'];

    public function messageable()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
