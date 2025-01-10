<?php

namespace Webkul\Recruitment\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Webkul\Security\Models\User;

class RefuseReason extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort',
    ];

    protected $table = 'recruitments_refuse_reasons';

    protected $fillable = ['creator_id', 'sort', 'name', 'is_active'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
