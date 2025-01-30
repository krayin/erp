<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;

use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTaxGroup extends CreateRecord
{
    protected static string $resource = TaxGroupResource::class;
}
