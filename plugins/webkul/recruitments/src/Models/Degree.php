<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Security\Models\User;

class Degree extends Model implements Sortable
{
    use SortableTrait;

    protected $table = 'recruitments_degrees';

    protected $fillable = ['name', 'sort', 'creator_id'];

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
