<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Webkul\Account\Enums;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;
use Webkul\Invoice\Traits\TaxPartition;

class ManageDistributionForInvoice extends ManageRelatedRecords
{
    use TaxPartition;

    protected static string $resource = TaxResource::class;

    protected static string $relationship = 'distributionForInvoice';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public function getDocumentType(): string
    {
        return Enums\DocumentType::INVOICE->value;
    }

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/tax/pages/manage-distribution-for-invoice.navigation.title');
    }
}
