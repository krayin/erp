<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages\EditProductCategory as BaseEditProductCategory;

class EditProductCategory extends BaseEditProductCategory
{
    protected static string $resource = ProductCategoryResource::class;
}
