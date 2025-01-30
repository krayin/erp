<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Invoice\Traits\TaxPartition;

class TaxPartitionRelationManager extends RelationManager
{
    use TaxPartition;

    protected static string $relationship = 'taxPartitions';

    protected static ?string $title = 'Tax Partitions';
}
