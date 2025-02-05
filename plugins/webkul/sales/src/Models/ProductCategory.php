<?php

namespace Webkul\Sale\Models;

use InvalidArgumentException;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Security\Models\User;
use Webkul\Product\Models\Category as BaseProductCategory;

class ProductCategory extends BaseProductCategory
{
    use HasChatter;

    protected array $logAttributes = [
        'name'            => 'Name',
        'completed_name'  => 'Completed Name',
        'createdBy.name'  => 'Created By',
        'parent.name'     => 'Parent',
    ];

    public function __construct(array $attributes = [])
    {
        $this->mergeFillable([
            'product_properties_definition',
            'property_account_income_category_id',
            'property_account_expense_category_id',
            'property_account_down_payment_category_id'
        ]);

        parent::__construct($attributes);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($productCategory) {
            if (! static::validateNoRecursion($productCategory)) {
                throw new InvalidArgumentException('Circular reference detected in product category hierarchy');
            }

            static::handleProductCategoryData($productCategory);
        });

        static::updating(function ($productCategory) {
            if (! static::validateNoRecursion($productCategory)) {
                throw new InvalidArgumentException('Circular reference detected in product category hierarchy');
            }

            static::handleProductCategoryData($productCategory);
        });
    }

    protected static function validateNoRecursion($productCategory)
    {
        if (! $productCategory->parent_id) {
            return true;
        }

        if (
            $productCategory->exists
            && $productCategory->id == $productCategory->parent_id
        ) {
            return false;
        }

        $visitedIds = [$productCategory->exists ? $productCategory->id : -1];
        $currentParentId = $productCategory->parent_id;

        while ($currentParentId) {
            if (in_array($currentParentId, $visitedIds)) {
                return false;
            }

            $visitedIds[] = $currentParentId;
            $parent = static::find($currentParentId);

            if (! $parent) {
                break;
            }

            $currentParentId = $parent->parent_id;
        }

        return true;
    }

    protected static function handleProductCategoryData($productCategory)
    {
        if ($productCategory->parent_id) {
            $parent = static::find($productCategory->parent_id);

            if ($parent) {
                $productCategory->parent_path = $parent->parent_path . $parent->id . '/';
            } else {
                $productCategory->parent_path = '/';
                $productCategory->parent_id = null;
            }
        } else {
            $productCategory->parent_path = '/';
        }

        $productCategory->full_name = static::getCompleteName($productCategory);
    }

    protected static function getCompleteName($productCategory)
    {
        $names = [];
        $names[] = $productCategory->name;

        $currentProductCategory = $productCategory;
        while ($currentProductCategory->parent_id) {
            $currentProductCategory = static::find($currentProductCategory->parent_id);
            if ($currentProductCategory) {
                array_unshift($names, $currentProductCategory->name);
            } else {
                break;
            }
        }

        return implode(' / ', $names);
    }
}
