<?php

namespace Webkul\Chatter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;
use Webkul\Core\Models\User;
use Webkul\Field\Traits\HasCustomFields;

class Task extends Model
{
    use HasChatter, HasCustomFields, HasLogActivity;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'created_by',
        'assigned_to'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
