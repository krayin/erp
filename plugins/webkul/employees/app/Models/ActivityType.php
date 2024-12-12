<?php

namespace Webkul\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

class ActivityType extends Model
{
    protected $fillable = [
        'sequence',
        'delay_count',
        'default_user_id',
        'user_id',
        'delay_unit',
        'delay_from',
        'icon',
        'decoration_type',
        'chaining_type',
        'category',
        'name',
        'summary',
        'default_note',
        'active',
        'keep_done',
    ];

    protected $casts = [
        'name'         => 'array',
        'summary'      => 'array',
        'default_note' => 'array',
        'active'       => 'boolean',
        'keep_done'    => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function defaultUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_user_id');
    }

    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();

        return $this->name[$locale] ?? $this->name['en'] ?? null;
    }

    public function getLocalizedSummaryAttribute()
    {
        $locale = app()->getLocale();

        return $this->summary[$locale] ?? $this->summary['en'] ?? null;
    }

    public function getLocalizedDefaultNoteAttribute()
    {
        $locale = app()->getLocale();

        return $this->default_note[$locale] ?? $this->default_note['en'] ?? null;
    }
}
