<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Invoice\Enums;
use Webkul\Invoice\Traits\TaxPartition;

class DistributionForRefundRelationManager extends RelationManager
{
    use TaxPartition;

    protected static string $relationship = 'distributionForRefund';

    protected static ?string $title = 'Distribution for Refund';

    public function getDocumentType(): string
    {
        return Enums\DocumentType::INVOICE->value;
    }
}
