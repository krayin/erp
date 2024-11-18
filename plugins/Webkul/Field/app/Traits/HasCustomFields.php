<?php

namespace Webkul\Field\Traits;

use Webkul\Field\Models\Field;

trait HasCustomFields
{
    /**
     * Boot the trait
     */
    protected static function bootHasCustomFields()
    {
        static::retrieved(function ($model) {
            $customFields = $model->getCustomFields();

            $model->mergeFillable($customFields->pluck('code')->toArray());

            $model->mergeCasts($customFields->pluck('code', 'type')->toArray());
        });
    }

    /**
     * Get all custom field codes for this model
     */
    protected function getCustomFields(): array
    {
        return Field::where('customizable_type', get_class($this));
    }

    /**
     * Add custom fields to fillable
     */
    protected function mergeFillable(array $attributes): void
    {
        $this->fillable = array_unique(array_merge($this->fillable, $attributes));
    }

    /**
     * Add custom fields to fillable
     */
    protected function mergeCasts(array $attributes): void
    {
        
    }
}