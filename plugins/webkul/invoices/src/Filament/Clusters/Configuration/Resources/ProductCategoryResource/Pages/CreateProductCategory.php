<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\ProductCategoryResource;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages\CreateProductCategory as BaseCreateProductCategory;

class CreateProductCategory extends BaseCreateProductCategory
{
    protected static string $resource = ProductCategoryResource::class;
}
