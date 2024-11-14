<?php

namespace Webkul\Field\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'custom_fields';

    /**
     * Table name.
     *
     * @var string
     */
    protected $casts = [
        'options' => 'array',
        'form_settings' => 'array',
        'table_settings' => 'array',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'type',
        'input_type',
        'datalist',
        'options',
        'form_settings',
        'table_settings',
        'sort_order',
        'customizable_type',
    ];
}
