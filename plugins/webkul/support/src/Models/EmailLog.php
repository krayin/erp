<?php

namespace Webkul\Support\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'email_template_id',
        'recipient_email',
        'recipient_name',
        'subject',
        'variables',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'variables' => 'array',
        'sent_at'   => 'datetime',
    ];
}
