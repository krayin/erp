<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Security\Models\User;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

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
