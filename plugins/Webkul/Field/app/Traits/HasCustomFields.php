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

            $model->mergeCasts($customFields->select('code', 'type', 'is_multiselect')->get());
        });
    }

    /**
     * Get all custom field codes for this model
     */
    protected function getCustomFields()
    {
        return Field::where('customizable_type', get_class($this));
    }

    /**
     * Add custom fields to fillable
     */
    public function mergeFillable(array $attributes): void
    {
        $this->fillable = array_unique(array_merge($this->fillable, $attributes));
    }

    /**
     * Add custom fields to fillable
     *
     * @param  array  $casts
     * @return $this
     */
    public function mergeCasts($attributes)
    {
        foreach ($attributes as $attribute) {
            match ($attribute->type) {
                'select' => $this->casts[$attribute->code] = $attribute->is_multiselect ? 'array' : 'string',
                'checkbox' => $this->casts[$attribute->code] = 'boolean',
                'toggle' => $this->casts[$attribute->code] = 'boolean',
                'checkbox_list' => $this->casts[$attribute->code] = 'array',
                default => $this->casts[$attribute->code] = 'string',
            };
        }

        return $this;
    }
}
