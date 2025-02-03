<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\FiscalPositionResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Invoice\Traits\FiscalPositionTax;

class FiscalPositionTaxRelationManager extends RelationManager
{
    use FiscalPositionTax;

    protected static string $relationship = 'fiscalPositionTaxes';

    protected static ?string $title = 'Fiscal Position Taxes';
}
