<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class Attachment extends Model
{
    protected $table = 'chatter_attachments';

    protected $fillable = [
        'company_id',
        'creator_id',
        'message_id',
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

    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($attachment) {
            $filePath = $attachment->file_path;

            if (
                $filePath
                && Storage::disk('public')->exists($filePath)
            ) {
                Storage::disk('public')->delete($filePath);
            }
        });
    }
}
