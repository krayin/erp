<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ChatAttachment extends Model
{
    protected $fillable = [
        'chat_id',
        'file_path',
        'original_file_name',
        'mime_type',
        'file_size',
    ];

    protected $appends = ['url', 'size'];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getSizeAttribute(): string
    {
        $sizeInMb = $this->file_size / (1024 * 1024);

        return number_format($sizeInMb, 2).' MB';
    }
}
