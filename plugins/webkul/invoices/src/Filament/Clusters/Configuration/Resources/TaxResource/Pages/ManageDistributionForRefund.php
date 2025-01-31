<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource\Pages;

use Filament\Pages\SubNavigationPosition;
use Webkul\Invoice\Enums;
use Filament\Resources\Pages\ManageRelatedRecords;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxResource;
use Webkul\Invoice\Traits\TaxPartition;

class ManageDistributionForRefund extends ManageRelatedRecords
{
    use TaxPartition;

    protected static string $resource = TaxResource::class;

    protected static string $relationship = 'distributionForRefund';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public function getDocumentType(): string
    {
        return Enums\DocumentType::REFUND->value;
    }

    public function getSubNavigationPosition(): SubNavigationPosition
    {
        return SubNavigationPosition::Top;
    }

    public static function getNavigationLabel(): string
    {
        return __('Distribution for Refunds');
    }
}
